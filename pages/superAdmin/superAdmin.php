<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin Page</title>

    <link rel="icon" href="../../dist/img/e-report-icon.png" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.min.css">

    <link rel="stylesheet" href="../../dist/css/datatable/dataTables.dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="../../plugins/sweetalert2/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="../../plugins/datatable/dist/dataTables.dataTables.min.css">


</head>
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

<!-- BODY -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0"><i class="fas fa-download"></i> STORE IN</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item ">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Uploaded Record Masterlist</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="row">
                                            <label for="sortBy">Sort by:</label>
                                            
                                            <div class="col-md-1">
                                                <select name="sortBy" id="sortBy" class="form-control">
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 520px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Filename</th>
                                                    <th>Action</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Accounts Masterlist</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable2">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Document</th>
                                                    <!-- <th>Sub Document</th> -->
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Revised Files Masterlist</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable3">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Document</th>
                                                    <!-- <th>Sub Document</th> -->
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<footer class="main-footer text-sm">
    Developed by: <em>Khennethlp</em>
    <div class="float-right d-none d-sm-inline-block">
        <strong>Copyright &copy;
            <script>
                var currentYear = new Date().getFullYear();
                if (currentYear !== 2024) {
                    document.write("2024 - " + currentYear);
                } else {
                    document.write(currentYear);
                };
            </script>.
        </strong>
        All rights reserved.
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // new DataTable('#myDataTable');
        // $('#myDataTable').DataTable();
        // new DataTable('#myDataTable2');
        // new DataTable('#myDataTable3');
    })
</script>
<?php
include '../../modals/super_record_masterlist.php';
?>

<script src="../../plugins/jquery/dist/jquery.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../dist/js/adminlte.js"></script>
<script src="../../dist/js/popup_center.js"></script>
<script src="plugins/js/functions.js"></script>
</body>

</html>