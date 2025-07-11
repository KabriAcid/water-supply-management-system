<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
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

// In Progress orders
$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'In Progress'");
$in_progress_orders = $stmt->fetchColumn();

// Total users
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

// Fetch all orders (no JOIN)
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
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
                            <p class="card-title text-secondary text-sm">Total Orders</p>
                            <h3 class="card-text fw-bolder"><?= $total_orders ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-warning mb-2"><i class="fa-solid fa-clock"></i></div>
                            <p class="card-title text-secondary text-sm">Pending Orders</p>
                            <h3 class="card-text fw-bolder"><?= $pending_orders ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-success mb-2"><i class="fa-solid fa-check-circle"></i></div>
                            <p class="card-title text-secondary text-sm">Delivered Orders</p>
                            <h3 class="card-text fw-bolder"><?= $delivered_orders ?></h3>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card shadow text-center p-4">
                            <div class="card-icon text-info mb-2"><i class="fa-solid fa-users"></i></div>
                            <p class="card-title text-secondary text-sm">Total Users</p>
                            <h3 class="card-text fw-bolder"><?= $total_users ?></h3>
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
                                            <td>
                                                <?php
                                                // Fetch user info for each order (no JOIN)
                                                $user_stmt = $pdo->prepare("SELECT name, phone FROM users WHERE id = ?");
                                                $user_stmt->execute([$order['user_id']]);
                                                $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
                                                echo htmlspecialchars($user['name'] ?? 'Unknown');
                                                ?>
                                            </td>
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
                                                <span class="badge bg-<?= $badge ?>"><?= $status ?></span>
                                            </td>
                                            <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                                            <td>
                                                <a href="view_orders.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="orders.php" class="btn gradient-btn-alt btn-sm">View All Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>