<?php
$user_type = 'User';
if (isset($_SESSION['admin_id'])) {
    $user_type = 'Admin';
} elseif (isset($_SESSION['user_id'])) {
    $user_type = 'User';
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-3" style="border-radius: 0.5rem;">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">
            <i class="fa-solid fa-tint text-primary"></i>
            <?= htmlspecialchars($user_type) ?> Dashboard
        </span>
    </div>
</nav>