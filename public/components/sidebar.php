<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <nav>
        <div class="header">
            <a href="dashboard.php">
                <span class="focus">
                    <img src="../../favicon.png" alt="favicon" width="50px" height="50px">
                </span>
                <span class="unfocus">ServiceHub</span>
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
                    <li class="item <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
                        <a href="services.php">
                            <i class="fa fa-concierge-bell"></i>
                            <span class="item-text">Services</span>
                            <span class="item-tooltip">Services</span>
                        </a>
                    </li>
                    <li class="item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                        <a href="profile.php">
                            <i class="fa fa-user"></i>
                            <span class="item-text">Profile</span>
                            <span class="item-tooltip">Profile</span>
                        </a>
                    </li>
                    <li class="item <?php echo ($current_page == 'clients.php') ? 'active' : ''; ?>">
                        <a href="clients.php">
                            <i class="fa fa-users"></i>
                            <span class="item-text">Clients</span>
                            <span class="item-tooltip">Clients</span>
                        </a>
                    </li>
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
                    ?>
                        <li class="item <?php echo ($current_page == 'add_service.php') ? 'active' : ''; ?>">
                            <a href="add_service.php">
                                <i class="fa fa-plus"></i>
                                <span class="item-text">Add Service</span>
                                <span class="item-tooltip">Add Service</span>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
                    ?>
                        <li class="item <?php echo ($current_page == 'add_service.php') ? 'active' : ''; ?>">
                            <a href="add_service.php">
                                <i class="fa fa-plus"></i>
                                <span class="item-text">Add Service</span>
                                <span class="item-tooltip">Add Service</span>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="item <?php echo ($current_page == 'appointments.php') ? 'active' : ''; ?>">
                        <a href="appointments.php">
                            <i class="fa fa-calendar-alt"></i>
                            <span class="item-text">Appointments</span>
                            <span class="item-tooltip">Appointments</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="section settings-section">
                <div class="title-wrapper">
                    <span class="title">Settings</span>
                </div>
                <ul class="items">
                    <?php
                    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'Admin') {
                    ?>
                        <li class="item <?php echo ($current_page == 'manage_app.php') ? 'active' : ''; ?>">
                            <a href="manage_app.php">
                                <i class="fa fa-cog"></i>
                                <span class="item-text">Manage App</span>
                                <span class="item-tooltip">Manage App</span>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="item <?php echo ($current_page == 'update_password.php') ? 'active' : ''; ?>">
                        <a href="update_password.php">
                            <i class="fa fa-lock"></i>
                            <span class="item-text">Change Password</span>
                            <span class="item-tooltip">Change Password</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="/servicehub/logout.php">
                            <i class="fa fa-key"></i>
                            <span class="item-text">Logout</span>
                            <span class="item-tooltip">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</aside>