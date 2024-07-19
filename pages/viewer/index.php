<?php
include('plugins/header.php');
include('plugins/preloader.php');
include('plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">E-REPORT SYSTEM</h1>
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
              <h3 class="card-title text-uppercase"><i class="fa fa-file-alt"></i>&nbsp; E-Report Approved Files</h3>
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
                        <div class="row mb-0">
                          <!-- <input type="hidden" name="approver_id" id="approver_id" value="<?php echo $_SESSION['emp_id']; ?>"> -->
                          <div class="col-md-3">
                            <label for="">Serial No:</label>
                            <input type="search" class="form-control" name="" id="search_by_serial" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Batch No:</label>
                            <input type="search" class="form-control" name="" id="search_by_batch" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Group No:</label>
                            <input type="search" class="form-control" name="" id="search_by_group" placeholder="">
                          </div>
                          <div class="col-md-3">
                            <label for="">Training Group:</label>
                            <!-- <input type="search" class="form-control" name="" id="search_by_training" placeholder=""> -->
                            <select class="form-control" name="search_by_training" id="search_by_training">
                              <option value="" selected>--SELECT TRAINING RECORD--</option>
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
                            <label for="">Filename:</label>
                            <input type="search" class="form-control" name="" id="search_by_filename" placeholder="">
                          </div>
                          <!-- <div class="col-md-3">
                            <label for="">Status:</label>
                            <select name="status" id="_status" class="form-control">
                              <option value="pending">Pending</option>
                              <option value="approved">Approved</option>
                              <option value="disapproved">Disapproved</option>
                            </select>
                          </div> -->
                          <div class="col-md-3">
                            <label for="">From:</label>
                            <input type="date" class="form-control" name="" id="search_by_date_from">
                          </div>
                          <div class="col-md-3">
                            <label for="">To:</label>
                            <input type="date" class="form-control" name="" id="search_by_date_to">
                          </div>
                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control bg-danger" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 ml-auto">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary btn_check" style="background-color: var(--gray);" onclick="location.reload();">
                              <i class="fas fa-sync-alt"></i>&nbsp;
                              Refresh
                            </button>
                          </div>
                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control" style="background-color: var(--warning);" onclick="">
                              <i class="fas fa-broom"></i>&nbsp;
                              Clear
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body table-responsive p-0" style="height: 600px;">
                    <table class="table table-head-fixed text-nowrap table-hover table-striped" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <!-- <th>Status</th> -->
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No.</th>
                          <th>Training Group</th>
                          <th>Filename</th>
                          <th>Approved Date</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="viewer_table"> </tbody>
                    </table>
                    <div id="viewer_load_more" class="text-center" style="display: none;">
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

<?php
include('plugins/js/viewer_script.php');
include('plugins/footer.php');
?>