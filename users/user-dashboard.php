<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

// Fetch user info
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>
    <div class="container py-5 mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Welcome, <?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                <p class="text-muted">Place a new order or view your order history below.</p>
                <a href="place_order.php" class="btn btn-primary mt-2">
                    <i class="fa fa-plus"></i> Place New Order
                </a>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Your Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Quantity</th>
                                <th>Delivery Address</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Ordered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $i => $order): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                                        <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                                        <td><?= $order['delivery_date'] ? htmlspecialchars(date('Y-m-d', strtotime($order['delivery_date']))) : '-' ?></td>
                                        <td>
                                            <?php
                                            $status = $order['status'];
                                            $badge = 'secondary';
                                            if ($status === 'Pending') $badge = 'warning';
                                            elseif ($status === 'In Progress') $badge = 'info';
                                            elseif ($status === 'Delivered') $badge = 'success';
                                            ?>
                                            <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                                        </td>
                                        <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>