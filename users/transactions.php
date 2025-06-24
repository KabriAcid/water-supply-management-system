<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

// Fetch user's transactions
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all related orders in one go (to avoid join)
$order_map = [];
if ($transactions) {
    $order_ids = array_column($transactions, 'order_id');
    if (!empty($order_ids)) {
        $in  = str_repeat('?,', count($order_ids) - 1) . '?';
        $order_stmt = $pdo->prepare("SELECT id, quantity FROM orders WHERE id IN ($in)");
        $order_stmt->execute($order_ids);
        foreach ($order_stmt->fetchAll(PDO::FETCH_ASSOC) as $order) {
            $order_map[$order['id']] = $order;
        }
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5">
            <h3 class="fw-bold mb-4">Transaction History</h3>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Quantity</th>
                                    <th>Amount (₦)</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No transactions found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $i => $txn): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($txn['order_id']) ?></td>
                                            <td>
                                                <?= isset($order_map[$txn['order_id']]['quantity']) ? htmlspecialchars($order_map[$txn['order_id']]['quantity']) . ' lts' : '-' ?>
                                            </td>
                                            <td>₦<?= number_format($txn['amount'], 2) ?></td>
                                            <td>
                                                <?php
                                                $badge = 'secondary';
                                                if ($txn['status'] === 'successful') $badge = 'success';
                                                elseif ($txn['status'] === 'pending') $badge = 'warning';
                                                elseif ($txn['status'] === 'failed') $badge = 'danger';
                                                ?>
                                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($txn['status']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($txn['payment_reference']) ?></td>
                                            <td><?= htmlspecialchars($txn['payment_method']) ?></td>
                                            <td><?= date('Y-m-d H:i', strtotime($txn['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>