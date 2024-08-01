<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <!-- <div class="row">
        <div class="col-lg-3 col-6">
          <input type="hidden" id="admin_name" value="<?= $_SESSION['name']; ?>">
          <input type="hidden" id="admin_id" value="<?= $_SESSION['emp_id']; ?>">
          <div class="small-box bg-info">
            <div class="inner">
              <?php
              require '../../process/conn.php';
              // $approver_id = $_SESSION['emp_id'];

              $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE checker_status = 'Pending'";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'pending' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);
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
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <?php
              require '../../process/conn.php';
              // $approver_id = $_SESSION['emp_id'];

              // $sql = "SELECT COUNT(serial_no) as total FROM t_training_record WHERE checker_status = 'Approved' AND uploader_name = :uploader_name";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'approved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE approver_status = 'Approved' AND checker_status = 'Approved'";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              // $stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);
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
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <?php
              require '../../process/conn.php';
              $approver_id = $_SESSION['emp_id'];

              $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE approver_status = 'Disapproved' ";
              // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'disapproved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
              $stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);
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
          </div>
        </div>
      </div> -->

      <!-- end of alert -->
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0">Tube Making Inventory</h1> -->
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
              <h3 class="card-title text-uppercase"><i class="fa fa-user-check"></i>&nbsp; admin dashboard</h3>
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
                          <input type="hidden" name="approver_id" id="approver_id" value="<?php echo $_SESSION['emp_id']; ?>">
                          <div class="col-md-3">
                            <label for="">Search By Serial No:</label>
                            <input type="search" class="form-control" name="" id="search_by_serialNo" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Batch No:</label>
                            <input type="search" class="form-control" name="" id="search_by_batchNo" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Group No:</label>
                            <input type="search" class="form-control" name="" id="search_by_groupNo" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Training Group:</label>
                            <!-- <input type="search" class="form-control" name="" id="search_by_tgroup" placeholder=""> -->
                            <select class="form-control" name="search_by_tgroup" id="search_by_tgroup">
                              <option value=""></option>
                              <?php
                              require '../../process/conn.php';

                              $sql = "SELECT DISTINCT training_title FROM t_training_group";
                              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                              $stmt->execute();

                              if ($stmt->rowCount() > 0) {
                                // Output data of each row
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Output data of each row
                                foreach ($rows as $row) {
                                  echo '<option value="' . $row["training_title"] . '">' . $row["training_title"] . '</option>';
                                }
                              } else {
                                echo '<option value="">No data available</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Document:</label>
                            <!-- <input type="search" class="form-control" name="" id="search_by_docs" placeholder=""> -->
                            <select class="form-control" name="search_by_docs" id="search_by_docs">
                              <option value="" selected></option>
                              <?php
                              require '../../process/conn.php';

                              $sql = "SELECT DISTINCT main_doc FROM m_report_title";
                              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                              $stmt->execute();

                              if ($stmt->rowCount() > 0) {
                                // Output data of each row
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Output data of each row
                                foreach ($rows as $row) {
                                  echo '<option value="' . $row["main_doc"] . '">' . $row["main_doc"] . '</option>';
                                }
                              } else {
                                echo '<option value="">No data available</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Filename:</label>
                            <input type="search" class="form-control" name="" id="search_by_filename" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <!-- <label for="">From:</label> -->
                            <!-- <input type="date" class="form-control" name="" id="search_by_date_from"> -->
                            <label for="">Month:</label>
                            <!-- <input type="date" id="search_date_from" class="form-control"> -->
                            <select name="search_by_date_from" id="search_by_month" class="form-control">
                              <option value=""></option>
                              <option value="01">JAN</option>
                              <option value="02">FEB</option>
                              <option value="03">MAR</option>
                              <option value="04">APR</option>
                              <option value="05">MAY</option>
                              <option value="06">JUN</option>
                              <option value="07">JUL</option>
                              <option value="08">AUG</option>
                              <option value="09">SEP</option>
                              <option value="10">OCT</option>
                              <option value="11">NOV</option>
                              <option value="12">DEC</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label for="">Year:</label>
                            <!-- <input type="date" class="form-control" name="" id="search_by_date_to"> -->
                            <select name="search_by_date_to" id="search_by_year" class="form-control">
                              <option value=""></option>
                              <?php
                              $currentYear = date('Y');
                              for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                                echo '<option value="' . $i . '"' . ($i == $currentYear ? ' selected' : '') . '>' . $i . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="col-md-3 ml-auto">
                            <label for="">&nbsp;</label>
                            <button class="form-control active" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-3 ">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary btn_check" style="background-color: var(--gray);" onclick="location.reload();">
                              <i class="fas fa-sync-alt"></i>&nbsp;
                              Refresh
                            </button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="card-body table-responsive p-0" style="height: 600px;">
                    <table class="table table-head-fixed text-nowrap table-hover" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No.</th>
                          <!-- <th>Document</th> -->
                          <th>Training Group</th>
                          <th>Filename</th>
                          <th>Approved Date</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="admin_dashboard_table"> </tbody>
                    </table>
                    <div id="a_load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline p-2 mt-3 " style="cursor: pointer;">Load More...</p>
                    </div>
                  </div>
                  <hr>
                  <div class="col-md-4">
                    <div class="row">
                      <span id="approved_count">Count: 0 </span>
                    </div>
                  </div>
                </div>
              </div>
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

<?php include 'plugins/js/dashboard_script.php'; ?>
<?php include 'plugins/footer.php'; ?>