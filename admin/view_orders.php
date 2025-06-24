<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

// Get order ID from query
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if (!$order_id) {
    echo "<div class='alert alert-danger m-4'>Invalid order ID.</div>";
    exit;
}

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<div class='alert alert-danger m-4'>Order not found.</div>";
    exit;
}

// Fetch user info
$user_stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$user_stmt->execute([$order['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Fetch transaction for this order
$txn_stmt = $pdo->prepare("SELECT * FROM transactions WHERE order_id = ? AND user_id = ? AND status = 'successful' LIMIT 1");
$txn_stmt->execute([$order['id'], $order['user_id']]);
$transaction = $txn_stmt->fetch(PDO::FETCH_ASSOC);
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5" style="max-width: 700px;">
            <h3 class="fw-bold mb-4 text-center gradient-text text-uppercase">Order Details</h3>
            <div class="card shadow p-4 mb-4">
                <h5 class="mb-3 text-center primary-color">Order Info</h5>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($order['id']) ?></p>
                <p><strong>User:</strong> <?= htmlspecialchars($user['name'] ?? 'Unknown') ?> (<?= htmlspecialchars($user['email'] ?? '-') ?>)</p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? '-') ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?> litres</p>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
                <p><strong>Delivery Date:</strong> <?= htmlspecialchars($order['delivery_date']) ?></p>
                <p><strong>Status:</strong> <span class="badge bg-<?= $order['status'] === 'Pending' ? 'warning' : ($order['status'] === 'In Progress' ? 'info' : 'success') ?>"><?= htmlspecialchars($order['status']) ?></span></p>
                <p><strong>Created At:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
            </div>
            <div class="card shadow p-4 mb-4">
                <h5 class="mb-3 text-center primary-color">Payment Info</h5>
                <?php if ($transaction): ?>
                    <p><strong>Payment Status:</strong> <span class="badge bg-success text-white">Successful</span></p>
                    <p><strong>Amount Paid:</strong> ₦<?= number_format($transaction['amount'], 2) ?></p>
                    <p><strong>Payment Reference:</strong> <?= htmlspecialchars($transaction['payment_reference']) ?></p>
                    <p><strong>Payment Method:</strong> <?= htmlspecialchars($transaction['payment_method']) ?></p>
                    <p><strong>Transaction Date:</strong> <?= htmlspecialchars($transaction['created_at']) ?></p>
                <?php else: ?>
                    <p><strong>Payment Status:</strong> <span class="badge bg-danger">Not Paid</span></p>
                <?php endif; ?>
            </div>
            <?php if ($transaction && $order['status'] !== 'Delivered'): ?>
                <form method="post" class="mt-3">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Update Order Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="In Progress" <?= $order['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="Delivered" <?= $order['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                    </div>
                    <button type="submit" class="btn gradient-btn">Update Status</button>
                </form>
            <?php endif;
            if ($order['status'] === 'Delivered'): ?>
                <div class="alert alert-success mt-3 text-center fw-bold">This order has already been delivered.</div>
                <a href="dashboard.php" class="d-block btn btn-secondary w-100">Back to Dashboard</a>
            <?php endif;
            ?>
        </div>
    </div>
</body>

</html>

<?php
// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $new_status = $_POST['status'];
    $order_id = intval($_POST['order_id']);
    $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update->execute([$new_status, $order_id]);
    echo "<script>alert('Order status updated!');window.location.href='view_orders.php?order_id=$order_id';</script>";
    exit;
}
?>