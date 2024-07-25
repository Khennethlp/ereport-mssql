<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0"><i class="fas fa-download"></i> STORE IN</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item ">Accounts</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-users mr-2"></i>Accounts</h3>
                        </div>

                        <div class="card-body">
                            <div class="col-md-12 mb-4">
                                <div class="row ">
                                    <div class="col-md-3">
                                        <input type="search" name="" id="search_account" style="height: 38px;" class="form-control float-right" placeholder="Search" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn " style="background-color: #3765AA; color: #fff;" id="searchReqBtn" onclick="load_accounts();">
                                            <i class="fas fa-search"></i>
                                            Search
                                        </button>
                                    </div>

                                    <div class="col-md-2 ml-auto">
                                        <button class="form-control active " data-toggle="modal" data-target="#add_acc"><i class="fas fa-user-plus"></i> ADD USER</button>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap sortable" id="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee ID</th>
                                                    <th>Fullname</th>
                                                    <th>Username</th>
                                                    <!-- <th>Email</th> -->
                                                    <th>Role</th>
                                                </tr>
                                            </thead>
                                            <tbody id="accounts_table"></tbody>
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



<?php include 'plugins/js/accounts_script.php'; ?>
<?php include 'plugins/footer.php'; ?>