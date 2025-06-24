<?php
$user_type = 'User';
$username = 'Guest';

if (isset($_SESSION['admin_id'])) {
    $user_type = 'Admin';
    // Fetch admin name
    require_once __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT name FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $row['name'] ?? 'Admin';
} elseif (isset($_SESSION['user_id'])) {
    $user_type = 'User';
    require_once __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $row['name'] ?? 'User';
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-3" style="border-radius: 0.5rem;">
    <div class="container-fluid d-flex justify-content-between">
        <span class="navbar-brand fw-bold text-sm">
            <?= htmlspecialchars($user_type) ?> Dashboard
        </span>
        <span>
            Hi, <span class="text-primary fw-bold">
                <?= htmlspecialchars($username) ?>
            </span>
        </span>
    </div>
</nav>