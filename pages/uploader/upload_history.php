<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/uploader_bar.php'; ?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-secondary card-outline">
            <div class="card-header">
              <h3 class="card-title text-uppercase"><i class="fa fa-history"></i>&nbsp;UPLOAD HISTORY</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                  <i class="fas fa-expand"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="card" id="card-container">
                    <div class="card-header">
                      <div class="col-md-12">
                        <div class="row">
                          <input type="hidden" name="uploader_name" class="form-control" id="checker_name" value="<?php echo $_SESSION['name']; ?>" readonly>
                          <div class="col-md-3">
                            <label for="">Search By Serial No:</label>
                            <input type="search" class="form-control" name="search_by" id="search_by_serialNo" placeholder="serial no">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Batch No:</label>
                            <input type="search" class="form-control" name="search_by" id="search_by_batchNo" placeholder="batch no">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Group No:</label>
                            <input type="search" class="form-control" name="search_by" id="search_by_groupNo" placeholder="group no">
                          </div>
                          <div class="col-md-3">
                            <label for="">Search By Training Group:</label>
                            <select class="form-control" name="training_group" id="training_group">
                              <option value="">All</option>
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
                            <label for="">Date From:</label>
                            <input type="date" class="form-control" name="search_by_date_from" id="search_by_date_from">
                          </div>
                          <div class="col-md-3">
                            <label for="">Date To:</label>
                            <input type="date" class="form-control" name="search_by_date_to" id="search_by_date_to">
                          </div>

                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control" style="background-color: var(--danger); color: #fff;" onclick="revisions();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary " style="background-color: var(--secondary);" onclick="location.reload();">
                              <i class="fas fa-sync-alt"></i>&nbsp;
                              Refresh
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card table-responsive p-0" style="height: 600px;">
                    <div class="card-body">
                      <div class="row mb-2" id="t_t1_breadcrumb">
                        <div class="col-12">
                          <ol class="breadcrumb bg-light mb-0">
                            <li class="breadcrumb-item"><a href="#" onclick="revisions();"><i class="fas fa-chevron-left"></i>&nbsp;Back</a></li>
                            <li class="breadcrumb-item" id="lbl_c1"></li>
                          </ol>
                        </div>
                      </div>
                      <table class="table table-head-fixed text-nowrap table-hover table-striped text-center" id="table">
                        <!-- <thead>
                          <tr>
                            <th>#</th>
                            <th>Serial No.</th>
                            <th>Batch No.</th>
                            <th>Group No.</th>
                            <th>Training Group</th>
                            <th>Revision Count</th>
                          </tr>
                        </thead> -->
                        <tbody id="upload_history_table"> </tbody>
                      </table>
                      <div id="history_load_more" class="text-center" style="display: none;">
                        <p class="badge badge-dark border border-outline p-2 mt-3 " style="cursor: pointer;">Load More...</p>
                      </div>
                    </div>
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

<?php include 'plugins/js/upload_history_script.php' ?>;
<?php include 'plugins/footer.php'; ?>