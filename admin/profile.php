<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

// Determine if admin or user
$is_admin = isset($_SESSION['admin_id']);
$is_user = isset($_SESSION['user_id']) && !$is_admin;

if ($is_admin) {
    $stmt = $pdo->prepare("SELECT name, email, phone FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($is_user) {
    $stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: ../auth/login.php");
    exit;
}

// Handle update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!$name || !$email) {
        $error = "Name and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        if ($is_admin) {
            $update = $pdo->prepare("UPDATE admins SET name = ?, email = ?, phone = ? WHERE id = ?");
            $update->execute([$name, $email, $phone, $_SESSION['admin_id']]);
        } else {
            $update = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            $update->execute([$name, $email, $phone, $_SESSION['user_id']]);
        }
        $success = "Profile updated successfully.";
        $profile['name'] = $name;
        $profile['email'] = $email;
        $profile['phone'] = $phone;
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5" style="max-width: 600px;">
            <h3 class="fw-bold mb-4 text-center">My Profile</h3>
            <?php if ($success): ?>
                <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off" class="shadow p-4 rounded bg-white">
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($profile['name'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                </div>
                <button type="submit" class="btn gradient-btn w-100">Update Profile</button>
            </form>
        </div>
    </div>
</body>

</html>