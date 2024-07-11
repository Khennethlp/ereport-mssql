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
            <li class="breadcrumb-item ">Dashboard</li>
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
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                require '../../process/conn.php';

                $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE checker_status = 'pending'";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                  // Output data of each row
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  // Output data of each row
                  foreach ($rows as $row) {
                    echo '<h3>'.$row['total'].'</h3>';
                  }
                } else {
                  echo '<h3>No Record.</h3>';
                }
                ?>
                
                <p>Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
              <?php
                require '../../process/conn.php';

                $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE approver_status = 'approved'";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                  // Output data of each row
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  // Output data of each row
                  foreach ($rows as $row) {
                    echo '<h3>'.$row['total'].'</h3>';
                  }
                } else {
                  echo '<h3>No Record.</h3>';
                }
                ?>
                <p>Approved</p>
              </div>
              <div class="icon">
                <i class="fas fa-thumbs-up"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
              <?php
                require '../../process/conn.php';

                $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE approver_status = 'disapproved'";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                  // Output data of each row
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  // Output data of each row
                  foreach ($rows as $row) {
                    echo '<h3>'.$row['total'].'</h3>';
                  }
                } else {
                  echo '<h3>No Record.</h3>';
                }
                ?>
                <p>Disapproved</p>
              </div>
              <div class="icon">
                <i class="fas fa-thumbs-down"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>

        </div>
      <!-- <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</h3>
            </div>

            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-12 mb-4">
                  <div class="col-12 col-md-6 col-lg-3 float-right">
                    <div class="input-group input-group-sm" style="margin: 8px;">
                      <input type="search" name="table_search" id="partsin_search" style="height: 40px;" class="form-control float-right" placeholder="Search" autocomplete="off">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-default" id="searchReqBtn" onclick="search(1)">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="card-body table-responsive p-0" style="height: 350px;">
                    <table class="table table-head-fixed text-nowrap" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Fullname</th>
                          <th>Username</th>
                          <th>Address</th>
                        </tr>
                      </thead>
                      <tbody id="table"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <hr>
          </div>
        </div>
      </div> -->
    </div>
  </section>
</div>

<?php include 'plugins/footer.php'; ?>