<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <nav>
        <div class="header">
            <a href="dashboard.php">
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