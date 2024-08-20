<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon" style="background-color:#3765AA; color:#fff;"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">UPLOADER</span>
                            <span class="info-box-number">
                                <?php
                                include '../../process/conn.php';

                                $query = "SELECT COUNT(*) as total FROM m_accounts WHERE role = 'uploader' AND secret_id != 'IT'";
                                $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                  
                                if ($stmt->rowCount() > 0) {
                                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                                  foreach ($rows as $row) {
                                    echo '<h4>' . $row['total'] . '</h4>';
                                  }
                                } else {
                                  echo '<h3>No Record.</h3>';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon" style="background-color:#3765AA; color:#fff;"><i class="fas fa-user-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">CHECKER</span>
                            <span class="info-box-number">
                            <?php
                                include '../../process/conn.php';

                                $query = "SELECT COUNT(*) as total FROM m_accounts WHERE role = 'checker' AND secret_id != 'IT'";
                                $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                  
                                if ($stmt->rowCount() > 0) {
                                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                                  foreach ($rows as $row) {
                                    echo '<h4>' . $row['total'] . '</h4>';
                                  }
                                } else {
                                  echo '<h3>No Record.</h3>';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon" style="background-color:#3765AA; color:#fff;"><i class="fas fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">APPROVER</span>
                            <span class="info-box-number">
                            <?php
                                include '../../process/conn.php';

                                $query = "SELECT COUNT(*) as total FROM m_accounts WHERE role = 'approver' AND secret_id != 'IT'";
                                $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                  
                                if ($stmt->rowCount() > 0) {
                                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                                  // Output data of each row
                                  foreach ($rows as $row) {
                                    echo '<h4>' . $row['total'] . '</h4>';
                                  }
                                } else {
                                  echo '<h3>No Record.</h3>';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon" style="background-color:#3765AA; color:#fff;"><i class="fas fa-user-tie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">ADMIN</span>
                            <span class="info-box-number">
                            <?php
                                include '../../process/conn.php';

                                $query = "SELECT COUNT(*) as total FROM m_accounts WHERE role = 'admin' and secret_id != 'IT'";
                                $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                $stmt->execute();
                  
                                if ($stmt->rowCount() > 0) {
                                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                                  // Output data of each row
                                  foreach ($rows as $row) {
                                    echo '<h4>' . $row['total'] . '</h4>';
                                  }
                                } else {
                                  echo '<h3>No Record.</h3>';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item ">Accounts</li>
                    </ol>
                </div>
            </div> -->
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