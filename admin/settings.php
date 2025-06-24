<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

$success = $error = '';
// Fetch current price per litre
$stmt = $pdo->query("SELECT price_per_litre FROM settings LIMIT 1");
$current_price = ($row = $stmt->fetch(PDO::FETCH_ASSOC)) ? $row['price_per_litre'] : 1.00;

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_price = floatval($_POST['price_per_litre'] ?? 0);
    if ($new_price <= 0) {
        $error = "Please enter a valid price per litre.";
    } else {
        $update = $pdo->prepare("UPDATE settings SET price_per_litre = ? LIMIT 1");
        $update->execute([$new_price]);
        $success = "Price per litre updated successfully!";
        $current_price = $new_price;
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5" style="max-width: 500px;">
            <h3 class="fw-bold mb-4 text-center">Set Litre Price</h3>
            <?php if ($success): ?>
                <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off" class="shadow p-4 rounded-2 bg-white">
                <div class="mb-3">
                    <label for="price_per_litre" class="form-label fw-semibold">Price Per Litre (₦)</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="price_per_litre" name="price_per_litre" required value="<?= htmlspecialchars($current_price) ?>">
                </div>
                <button type="submit" class="btn gradient-btn w-100">Update Price</button>
            </form>
        </div>
    </div>
</body>

</html>