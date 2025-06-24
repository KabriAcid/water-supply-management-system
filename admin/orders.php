<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

// Approve order if order_id is sent via GET and approve=yes
if (isset($_GET['order_id']) && isset($_GET['approve'])) {
    $order_id = intval($_GET['order_id']);
    if ($_GET['approve'] === 'yes') {
        // Only approve if order is currently Pending
        $stmt = $pdo->prepare("UPDATE orders SET status = 'In Progress' WHERE id = ? AND status = 'Pending'");
        $stmt->execute([$order_id]);
    } elseif ($_GET['approve'] === 'no') {
        // Delete the order
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
    }
    header("Location: orders.php");
    exit;
}

// Fetch all orders (no JOIN)
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    if (in_array($status, ['Pending', 'In Progress', 'Delivered'])) {
        $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $update->execute([$status, $order_id]);
        header("Location: orders.php");
        exit;
    }
}
?>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="section-title mb-3">
                    <h4 class="fw-bold">All Orders</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle bg-white">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Quantity</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Update Status</th>
                                <th>Approve</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No orders found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $i => $order): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <?php
                                        // Fetch user info for each order (no JOIN)
                                        $user_stmt = $pdo->prepare("SELECT name, phone FROM users WHERE id = ?");
                                        $user_stmt->execute([$order['user_id']]);
                                        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <td><?= htmlspecialchars($user['name'] ?? 'Unknown') ?></td>
                                        <td><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($order['quantity']) ?> lts</td>
                                        <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                                        <td>
                                            <?php
                                            $status = $order['status'];
                                            $badge = 'secondary';
                                            if ($status === 'Pending') $badge = 'warning';
                                            elseif ($status === 'In Progress') $badge = 'info';
                                            elseif ($status === 'Delivered') $badge = 'success';
                                            ?>
                                            <span class="badge text-bg-<?= $badge ?>"><?= $status ?></span>
                                        </td>
                                        <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <form method="POST" class="d-flex gap-2 align-items-center">
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <select name="status" class="form-control form-select-sm" style="width: auto;">
                                                    <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="In Progress" <?= $status === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="Delivered" <?= $status === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php if ($order['status'] === 'Pending'): ?>
                                                <a href="orders.php?order_id=<?= $order['id'] ?>&approve=yes" class="btn btn-sm btn-success" onclick="return confirm('Approve this order?');">Yes</a>
                                                <a href="orders.php?order_id=<?= $order['id'] ?>&approve=no" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?');">No</a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
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