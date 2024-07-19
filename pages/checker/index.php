<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/user_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <input type="hidden" id="checker_name" value="<?= $_SESSION['name']; ?>">
          <input type="hidden" id="checker_id" value="<?= $_SESSION['emp_id']; ?>">
          <div class="small-box bg-warning">
            <div class="inner">
              <?php
              require '../../process/conn.php';
              $checker_id = $_SESSION['emp_id'];

              $sql = "SELECT DISTINCT COUNT(*) as total FROM t_training_record WHERE checker_status = 'Pending' AND checker_id = :checker_id";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'pending' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
              $stmt->execute();

              if ($stmt->rowCount() > 0) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                  echo '<h3>' . $row['total'] . '</h3>';
                }
              } else {
                echo '<h3>0</h3>';
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
              $checker_id = $_SESSION['emp_id'];

              // $sql = "SELECT COUNT(serial_no) as total FROM t_training_record WHERE checker_status = 'Approved' AND uploader_name = :uploader_name";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'approved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE checker_status = 'Approved' AND approver_status = 'Approved' AND checker_id = :checker_id";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
              $stmt->execute();

              if ($stmt->rowCount() > 0) {
                // Output data of each row
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output data of each row
                foreach ($rows as $row) {
                  echo '<h3>' . $row['total'] . '</h3>';
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
              $checker_id = $_SESSION['emp_id'];

              $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE checker_status = 'Disapproved' AND approver_status = '' AND checker_id = :checker_id";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'disapproved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
              $stmt->execute();

              if ($stmt->rowCount() > 0) {
                // Output data of each row
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output data of each row
                foreach ($rows as $row) {
                  echo '<h3>' . $row['total'] . '</h3>';
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

      <!-- end of alert -->
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">FOR CHECKING</h1>
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
          <!-- STORE IN -->
          <div class="card card-secondary card-outline">
            <div class="card-header">
              <h3 class="card-title text-uppercase"><i class="fa fa-user-check"></i>&nbsp; </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                  <i class="fas fa-expand"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!-- <h6>content here...</h6> -->
              <div class="row">
                <div class="col-12">

                  <div class="card">
                    <div class="card-header">
                      <div class="col-md-12">
                        <div class="row">
                          <input type="hidden" name="checker_id" id="checker_id" value="<?php echo $_SESSION['emp_id']; ?>">
                          <div class="col-md-3">
                            <label for="">Search:</label>
                            <input type="search" class="form-control" name="search_by" id="search_by" placeholder="search by serial no.">
                          </div>
                          <div class="col-md-3">
                            <label for="">Status:</label>
                            <select name="status" id="status" class="form-control">
                              <!-- <option value="">--All--</option> -->
                              <option value="pending">Pending</option>
                              <option value="approved">Approved</option>
                              <option value="disapproved">Disapproved</option>
                            </select>
                          </div>
                        
                          <div class="col-md-3">
                            <label for="">From:</label>
                            <input type="date" class="form-control" name="search_by_date_from" id="search_by_date_from">
                          </div>
                          <div class="col-md-3">
                            <label for="">To:</label>
                            <input type="date" class="form-control" name="search_by_date_to" id="search_by_date_to">
                          </div>

                        </div>
                        <div class="row">
                          <div class="col-md-3 ml-auto">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn_check_refresh" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary" style="background-color: var(--gray);" onclick="location.reload();">
                              <i class="fas fa-sync-alt"></i>&nbsp;
                              Refresh
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card-body table-responsive p-0" style="height: 600px;">
                    <table class="table table-head-fixed text-nowrap table-hover " id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Status</th>
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Filenames</th>
                          <th>Uploaded By</th>
                          <!-- <th>Date</th> -->

                        </tr>
                      </thead>
                      <tbody id="checker_table"> </tbody>
                    </table>
                    <div id="c_load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline p-2 mt-3 " style="cursor: pointer;">Load More...</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <!-- <h6>another content here...</h6> -->
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card end-->


        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
</section>
</div>

<?php include 'plugins/js/index_script.php' ?>;
<?php include 'plugins/footer.php'; ?>
<?php //include 'plugins/js/pagination_script.php';
?>