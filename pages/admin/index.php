<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">


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
                          <div class="col-md-3 mb-2">
                            <label for="">Search By Serial No:</label>
                            <input type="search" class="form-control" name="" id="search_by_serialNo" placeholder="">
                          </div>
                          <div class="col-md-3 mb-2">
                            <label for="">Search By Batch No:</label>
                            <input type="search" class="form-control" name="" id="search_by_batchNo" placeholder="">
                          </div>
                          <div class="col-md-3 mb-2">
                            <label for="">Search By Group No:</label>
                            <input type="search" class="form-control" name="" list="search_by_groupNo" placeholder="">
                            <datalist id="search_by_groupNo">
                              <?php
                              require '../../process/conn.php';
                              $sql = "SELECT DISTINCT group_no FROM t_training_record ";
                              $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                              $stmt->execute();

                              if ($stmt->rowCount() > 0) {
                                // Output data of each row
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Output data of each row
                                foreach ($rows as $row) {

                                  echo '<option value="' . $row["group_no"] . '">' . $row["group_no"] . '</option>';
                                }
                              } else {
                                echo '<option value="">No data available</option>';
                              }
                              ?>
                            </datalist>
                          </div>
                          <div class="col-md-3 mb-2">
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
                            <label for="">Search By Filename:</label>
                            <input type="search" class="form-control" name="" id="search_by_filename" placeholder="">
                          </div>
                          <div class="col-md-3 mb-2">
                            <!-- <label for="">From:</label> -->
                            <!-- <input type="date" class="form-control" name="" id="search_by_date_from"> -->
                            <label for="">Month:</label>
                            <!-- <input type="date" id="search_date_from" class="form-control"> -->
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
                            <!-- <input type="date" class="form-control" name="" id="search_by_date_to"> -->
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
                          <div class="col-md-3 mb-2 ml-auto">
                            <label for="">&nbsp;</label>
                            <button class="form-control active" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-3 mb-2 ">
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
                  <div class="card-body table-responsive p-0" style="height: 620px;">
                    <table class="table table-head-fixed text-nowrap table-hover" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No.</th>
                          <th>Month</th>
                          <th>Year</th>
                          <th>Document</th>
                          <th>Training Group</th>
                          <th>Filename</th>
                          <th>Checked By</th>
                          <th>Checked Date</th>
                          <th>Approved By</th>
                          <th>Approved Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="admin_dashboard_table"> </tbody>
                    </table>
                    <div id="load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline px-3 py-2 mt-3 " style="cursor: pointer;">Load More...</p>
                    </div>
                  </div>
                  <hr>
                  <div class="col-md-4">
                    <div class="row">
                      <span id="approved_count"></span>
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