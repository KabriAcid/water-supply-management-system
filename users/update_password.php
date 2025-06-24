<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$current_password || !$new_password || !$confirm_password) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        // Fetch current password hash from DB
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['password'] !== md5($current_password)) {
            $error = "Current password is incorrect.";
        } else {
            // Update password
            $hashed_new = md5($new_password);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed_new, $user_id])) {
                $success = "Password updated successfully.";
            } else {
                $error = "Failed to update password. Please try again.";
            }
        }
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5" style="max-width: 500px;">
            <h3 class="fw-bold mb-4">Change Password</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off" class="shadow p-4 rounded-2 bg-white">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" placeholder="Current Password" class="form-control" id="current_password" name="current_password" required minlength="6">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" placeholder="New Password" class="form-control" id="new_password" name="new_password" required minlength="6">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                <button type="submit" class="btn gradient-btn w-100">Update Password</button>
            </form>
        </div>
    </div>
</body>

</html>