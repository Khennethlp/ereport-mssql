<!-- NAVBAR -->
<nav class="main-header navbar navbar-expand navbar-light" style="background-color: #306BAC;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
</nav>

<!-- SIDEBAR -->
<aside class="main-sidebar sidebar-light-primary sidebar-light-primary elevation-4" id="sidebar">
    <a href="index.php" class="brand-link" style="background-color: #306BAC; color: #fff;">
        <img src="../../dist/img/e-report-icon.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light text-uppercase">&ensp; Super Admin</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../dist/img/user.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="superAdmin.php" class="d-block">SUPER ADMIN</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <?php if ($_SERVER['REQUEST_URI'] == "/e-report/pages/superAdmin/superAdmin.php") { ?>
                        <a href="superAdmin.php" class="nav-link active">
                        <?php } else { ?>
                            <a href="superAdmin.php" class="nav-link">
                            <?php } ?>
                            <i class="nav-icon fa fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                            </a>
                </li>
                <li class="nav-item">
                    <?php if ($_SERVER['REQUEST_URI'] == "/e-report/pages/superAdmin/settings.php") { ?>
                        <a href="settings.php" class="nav-link active">
                        <?php } else { ?>
                            <a href="settings.php" class="nav-link">
                            <?php } ?>
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                Settings
                            </p>
                            </a>
                </li>

                <li class="nav-item">
                    <a href="index.php" class="nav-link b-border">
                        <i class="nav-icon fas fa-sign-out-alt text-dark "></i>
                        <p class="text">Sign out</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="sidebar-bottom">
        <p class="text-muted text-center" style="font-size: 11px; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);">Version 1.0.0</p>
    </div>
</aside>