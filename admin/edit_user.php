<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';
$success = '';

// Fetch user data
$stmt = $pdo->prepare("SELECT id, name, email, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error = "User not found.";
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));

    if (!$name || !$email || !$phone) {
        $error = "All fields are required.";
    } else {
        // Check for duplicate email/phone (excluding current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
        $stmt->execute([$email, $phone, $user_id]);
        if ($stmt->fetch()) {
            $error = "Email or phone already exists for another user.";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            $updated = $stmt->execute([$name, $email, $phone, $user_id]);
            if ($updated) {
                $success = "User updated successfully.";
                // Refresh user data
                $stmt = $pdo->prepare("SELECT id, name, email, phone FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = "Failed to update user.";
            }
        }
    }
}
?>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container" style="max-width: 600px;">
                <div class="section-title mb-3">
                    <h4 class="fw-bold">Edit User</h4>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($user): ?>
                    <form method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required maxlength="15">
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="users.php" class="btn btn-secondary ms-2">Back</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>