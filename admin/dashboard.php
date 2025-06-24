<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

// Fetch dashboard metrics
$total_orders = 0;
$pending_orders = 0;
$delivered_orders = 0;
$total_users = 0;
$recent_orders = [];

// Total orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$total_orders = $stmt->fetchColumn();

// Pending orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'");
$pending_orders = $stmt->fetchColumn();

// Delivered orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Delivered'");
$delivered_orders = $stmt->fetchColumn();

// Total users
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

// Recent orders (last 5)
$stmt = $pdo->query("SELECT o.*, u.name, u.phone FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
        <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
            <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
            <div class="container-fluid">
                <div class="row g-4 my-3">
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-primary mb-2"><i class="fa-solid fa-list"></i></div>
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text fs-4"><?= $total_orders ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-warning mb-2"><i class="fa-solid fa-clock"></i></div>
                            <h5 class="card-title">Pending Orders</h5>
                            <p class="card-text fs-4"><?= $pending_orders ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-success mb-2"><i class="fa-solid fa-check-circle"></i></div>
                            <h5 class="card-title">Delivered Orders</h5>
                            <p class="card-text fs-4"><?= $delivered_orders ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-info mb-2"><i class="fa-solid fa-users"></i></div>
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text fs-4"><?= $total_users ?></p>
                        </div>
                    </div>
                </div>
                <div class="dashboard-section mt-4">
                    <div class="section-title">
                        <p class="fw-bold fs-5 mb-3">Recent Orders</p>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_orders)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No recent orders.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_orders as $i => $order): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($order['name']) ?></td>
                                            <td><?= htmlspecialchars($order['phone']) ?></td>
                                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                                            <td><?= htmlspecialchars($order['delivery_address']) ?></td>
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
                                            <td>
                                                <a href="orders.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="orders.php" class="btn btn-primary btn-sm">View All Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>