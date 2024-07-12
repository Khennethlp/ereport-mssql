<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/uploader_bar.php'; ?>

<style>

</style>
<div class="content-wrapper">
  <div class="content-header">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <input type="hidden" id="uploader_name" value="<?= $_SESSION['name']; ?>">
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                require '../../process/conn.php';
                $uploader_name = $_SESSION['name'];

                $sql = "SELECT DISTINCT COUNT(checker_status) as total FROM t_training_record WHERE checker_status = 'Pending' AND uploader_name = :uploader_name";
                // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'pending' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);
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
                $uploader_name = $_SESSION['name'];

                // $sql = "SELECT COUNT(serial_no) as total FROM t_training_record WHERE checker_status = 'Approved' AND uploader_name = :uploader_name";
                // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'approved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
                $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE checker_status = 'Approved' AND uploader_name = :uploader_name";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);
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
                $uploader_name = $_SESSION['name'];

                $sql = "SELECT COUNT(checker_status) as total FROM t_training_record WHERE checker_status = 'Disapproved' AND uploader_name = :uploader_name";
                // $sql = "SELECT COUNT(*) as total FROM ( SELECT a.serial_no FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE a.checker_status = 'disapproved' AND a.uploader_name = :uploader_name GROUP BY a.serial_no ) as grouped_records";
                $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);
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
        <div class="row">
          <div class="col-sm-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title text-uppercase"><i class="fas fa-upload mr-2"></i>Upload</h3>
              </div>

              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-12 mb-0">
                    <div class="row">
                      <div class="row col-12 mb-0">
                        <div class="col-md-3">
                          <div class="col-md-12 mb-2">
                            <label for="">Date To:</label>
                            <input type="date" id="search_date" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="col-md-12 mb-2">
                            <label for="">Date From:</label>
                            <input type="date" id="search_date" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="col-md-12 mb-2">
                            <label for="">Batch No:</label>
                            <input type="text" id="search_batch" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="col-md-12 mb-2">
                            <label for="">Status:</label>
                            <select name="status" id="status" class="form-control">
                              <option value="pending">Pending</option>
                              <option value="approved">Approved</option>
                              <option value="disapproved">Disapproved</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="row ml-auto">

                            <div class="col-md-3 mb-1">
                              <label for="">&nbsp;</label>
                              <button class="form-control btn btn-danger" onclick="load_data();">
                                <i class="fas fa-search"></i>&nbsp;
                                Search
                              </button>
                            </div>
                            <div class="col-md-6 mb-1">
                              <label for="">&nbsp;</label>
                              <button class="form-control btn_Submit" id="" data-toggle="modal" data-target="#upload">
                                <i class="fas fa-upload"></i>&nbsp;
                                Upload
                              </button>
                            </div>
                            <div class="col-md-3 mb-1">
                              <label for="">&nbsp;</label>
                              <button class="form-control btn btn-secondary" onclick="refresh();">
                                <i class="fas fa-sync-alt"></i>&nbsp;
                                Refresh
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <div class="card-body table-responsive p-0" id="table_container" style="height: 600px; overflow: auto;">
                    <table class="table table-head-fixed text-nowrap text-center table-hover" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Status</th> <!--Checker Status-->
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No</th>
                          <th>Training Group</th>
                          <th>Check By</th>
                          <th>Checked Date</th>
                          <th>Approve By</th>
                          <th>Approver Status</th>
                          <th>Approved Date</th>
                        </tr>
                      </thead>
                      <tbody id="t_table"></tbody>
                    </table>
                    <div id="load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline p-2 mt-3 " style="cursor: pointer;">Load More...</p>
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
</div>

<?php include 'plugins/js/upload_script.php'; ?>
<?php include 'plugins/footer.php'; ?>