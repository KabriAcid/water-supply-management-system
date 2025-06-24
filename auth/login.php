<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/main-header.php';

// Helper function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'All fields are required.';
    } else {
        // Try to find user in users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Try to find admin in admins table if not found in users
        if (!$user) {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            if ($admin && $admin['password'] === md5($password)) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            // User login
            if ($user['password'] === md5($password)) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ../users/user-dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
    }
}
?>

<body>
    <div class="container-fluid py-5" id="login-bg">
        <div class="container text-center mt-5">
            <h1 class="display-4 fw-bold">Welcome Back!</h1>
            <p class="lead">Login to your account to continue</p>
        </div>
        <div class="container d-flex align-items-center justify-content-center mt-5">
            <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%;">
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required minlength="6">
                    </div>
                    <button type="submit" class="btn gradient-btn w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <span>Don't have an account?</span>
                    <a href="register.php" class="fw-bold">Register</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>