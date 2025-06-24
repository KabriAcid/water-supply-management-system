<?php
$current_page = basename($_SERVER['PHP_SELF']);

// Determine user type
$is_admin = isset($_SESSION['admin_id']);
$is_user = isset($_SESSION['user_id']) && !$is_admin;
?>

<aside class="sidebar">
    <nav>
        <div class="header">
            <a href="<?= $is_admin ? 'dashboard.php' : '../users/user-dashboard.php' ?>">
                <span class="focus">
                    <img src="/water-supply-system/assets/img/logo.png" alt="favicon" width="50px" height="50px">
                </span>
                <span class="unfocus">WMSystem</span>
            </a>
        </div>
        <div class="separator-wrapper">
            <hr class="separator" />
            <label for="minimize" class="minimize-btn">
                <input type="checkbox" id="minimize" />
                <i class="fa fa-angle-left"></i>
            </label>
        </div>
        <div class="navigations">
            <div class="section main-section">
                <div class="title-wrapper">
                    <span class="title">Main</span>
                </div>
                <ul class="items">
                    <?php if ($is_admin): ?>
                        <li class="item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                            <a href="dashboard.php">
                                <i class="fa fa-table-columns"></i>
                                <span class="item-text">Dashboard</span>
                                <span class="item-tooltip">Dashboard</span>
                            </a>
                        </li>
                        <li class="item <?php echo ($current_page == 'orders.php') ? 'active' : ''; ?>">
                            <a href="orders.php">
                                <i class="fa fa-truck"></i>
                                <span class="item-text">All Orders</span>
                                <span class="item-tooltip">All Orders</span>
                            </a>
                        </li>
                        <li class="item <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                            <a href="users.php">
                                <i class="fa fa-users"></i>
                                <span class="item-text">Users</span>
                                <span class="item-tooltip">Users</span>
                            </a>
                        </li>
                        <li class="item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                            <a href="profile.php">
                                <i class="fa fa-user"></i>
                                <span class="item-text">Profile</span>
                                <span class="item-tooltip">Profile</span>
                            </a>
                        </li>
                    <?php elseif ($is_user): ?>
                        <li class="item <?php echo ($current_page == 'user-dashboard.php') ? 'active' : ''; ?>">
                            <a href="../users/user-dashboard.php">
                                <i class="fa fa-table-columns"></i>
                                <span class="item-text">Dashboard</span>
                                <span class="item-tooltip">Dashboard</span>
                            </a>
                        </li>
                        <li class="item <?php echo ($current_page == 'place_order.php') ? 'active' : ''; ?>">
                            <a href="../users/place_order.php">
                                <i class="fa fa-plus"></i>
                                <span class="item-text">Place Order</span>
                                <span class="item-tooltip">Place Order</span>
                            </a>
                        </li>
                        <li class="item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                            <a href="profile.php">
                                <i class="fa fa-user"></i>
                                <span class="item-text">Profile</span>
                                <span class="item-tooltip">Profile</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="section settings-section">
                <div class="title-wrapper">
                    <span class="title">Settings</span>
                </div>
                <ul class="items">
                    <li class="item <?php echo ($current_page == 'update_password.php') ? 'active' : ''; ?>">
                        <a href="update_password.php">
                            <i class="fa fa-lock"></i>
                            <span class="item-text">Change Password</span>
                            <span class="item-tooltip">Change Password</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="/water-supply-system/auth/logout.php">
                            <i class="fa fa-sign-out-alt"></i>
                            <span class="item-text">Logout</span>
                            <span class="item-tooltip">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</aside>