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
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                require '../../process/conn.php';
                $uploader_name = $_SESSION['name'];

                $sql = "SELECT COUNT(*) as total
                FROM t_training_record
                WHERE uploader_name = :uploader_name
                  AND (
                      (checker_status = 'Pending')
                      OR (checker_status = '' AND approver_status = 'Pending')
                      OR (checker_status = 'Approved' AND approver_status = 'Pending')
                  )";

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
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                require '../../process/conn.php';
                $uploader_name = $_SESSION['name'];

                $sql = "SELECT COUNT(*) as total 
                FROM t_training_record 
                WHERE (
                    (checker_status = 'Approved' AND checker_status = '') OR
                    (approver_status = 'Approved')
                )
                AND uploader_name = :uploader_name";
        
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
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <?php
                require '../../process/conn.php';
                $uploader_name = $_SESSION['name'];

                $sql = "SELECT COUNT(*) as total FROM t_training_record WHERE 
                  (checker_status = 'Disapproved' OR approver_status = 'Disapproved')
                   AND uploader_name = :uploader_name";

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
                      <div class="row col-12 mb-1">
                        <div class="col-md-3 mb-2">
                          <label for="">Status:</label>
                          <select name="status" id="status" class="form-control bg-cyan">
                            <option value="pending">PENDING</option>
                            <option value="approved">APPROVED</option>
                            <option value="disapproved">DISAPPROVED</option>
                          </select>
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Search By Serial No:</label>
                          <input type="search" id="search_by_serialNo" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Search By Batch No:</label>
                          <input type="search" id="search_by_batchNo" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Search By Group No:</label>
                          <input type="search" id="search_by_groupNo" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Search By Training Group:</label>
                          <!-- <input type="search" id="search_by_tgroup" class="form-control"> -->
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
                        <div class="col-md-3 mb-2">
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
                        <div class="col-md-3 mb-2">
                          <label for="">Month:</label>
                          <select name="search_by_date_from" id="search_by_month" class="form-control">
                            <option value=""></option>
                            <option value="January">JANUARY</option>
                            <option value="February">FEBRUARY</option>
                            <option value="March">MARCH</option>
                            <option value="April">APRIL</option>
                            <option value="May">MAY</option>
                            <option value="June">JUNE</option>
                            <option value="July">JULY</option>
                            <option value="August">AUGUST</option>
                            <option value="September">SEPTEMBER</option>
                            <option value="October">OCTOBER</option>
                            <option value="November">NOVEMBER</option>
                            <option value="December">DECEMBER</option>
                          </select>
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Year:</label>
                          <select name="search_by_date_to" id="search_by_year" class="form-control">
                            <option value=""></option>
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                              echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            // ' . ($i == $currentYear ? ' selected' : '') . '
                            ?>
                          </select>
                        </div>

                        <!-- <div class="col-md-3 mb-2">
                          <label for="">Date From:</label>
                          <input type="date" id="search_date_from" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                          <label for="">Date To:</label>
                          <input type="date" id="search_date_to" class="form-control">
                        </div> -->

                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-3 mb-2">
                              <label for="">Search by Filename:</label>
                              <input type="search" id="search_by_filename" class="form-control">
                            </div>
                            <div class="col-md-3 mb-2 mb-1 ml-auto">
                              <label for="">&nbsp;</label>
                              <button class="form-control" style="background-color: var(--danger); color: #fff;" onclick="load_data();">
                                <i class="fas fa-search"></i>&nbsp;
                                Search
                              </button>
                            </div>
                            <div class="col-md-3 mb-2 mb-1">
                              <label for="">&nbsp;</label>
                              <button class="form-control btn_Submit" id="" data-toggle="modal" data-target="#upload">
                                <i class="fas fa-upload"></i>&nbsp;
                                Upload
                              </button>
                            </div>
                            <div class="col-md-3 mb-2 mb-1">
                              <label for="">&nbsp;</label>
                              <button class="form-control" style="background-color: var(--gray); color: #fff;" onclick="refresh();">
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
                    <table class="table table-head-fixed text-nowrap  table-hover" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Status</th> <!--Global Status-->
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No</th>
                          <th>Month</th>
                          <th>Year</th>
                          <th>Document</th>
                          <th>Training Group</th>
                          <th>Filename</th>
                          <th>Checked By</th>
                          <th>Checked Date</th>
                          <th>Approved By</th>
                          <th>Approved Date</th>
                          <th>Comment</th>
                          <th>Disapproved By</th>
                        </tr>
                      </thead>
                      <tbody id="t_table">
                      </tbody>
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