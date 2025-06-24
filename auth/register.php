<?php
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/main-header.php';

// Helper function to sanitize input
function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = clean_input($_POST['name'] ?? '');
    $email    = clean_input($_POST['email'] ?? '');
    $phone    = clean_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$name || !$email || !$phone || !$password) {
        $error = "All fields are required.";
    } else {
        // Check if email or phone already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$email, $phone]);
        if ($stmt->fetch()) {
            $error = "Email or phone already exists.";
        } else {
            // Hash password with md5 (for legacy compatibility)
            $hashed_password = md5($password);

            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$name, $email, $phone, $hashed_password]);

            if ($result) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<body>
    <?php require __DIR__ . '/../components/navbar.php'; ?>
    <div class="container-fluid py-5 mt-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Create Your Account</h1>
            <p class="lead">Sign up to place water delivery orders</p>
        </div>
        <div class="container d-flex align-items-center justify-content-center my-5">
            <div class="card shadow-lg p-4" style="max-width: 700px; width: 100%;">
                <h3 class="text-center mb-4 fw-bold">Register</h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Jane Doe" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="08012345678" required maxlength="15">
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required minlength="6">
                        </div>
                    </div>
                    <button type="submit" class="btn gradient-btn w-100 mt-4">Register</button>
                </form>
                <div class="text-center mt-3">
                    <span>Already have an account?</span>
                    <a href="login.php" class="fw-bold">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>