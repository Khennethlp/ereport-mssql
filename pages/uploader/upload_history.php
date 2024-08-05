<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/uploader_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <!-- Alert -->

      <!-- end of alert -->
      <div class="row mb-2">
        <!-- <div class="col-sm-6">
          <h1 class="m-0">UPLOAD HISTORY</h1>
        </div> -->
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
            <!-- /.card-header -->
            <div class="card-body">
              <!-- <h6>content here...</h6> -->
              <div class="row">
                <div class="col-12">

                  <div class="card">
                    <div class="card-header">
                      <div class="col-md-12">
                        <div class="row">
                          <input type="hidden" name="checker_name" id="checker_name" value="<?php echo $_SESSION['name']; ?>">
                          <!-- <div class="col-md-2">
                            <label for="">Status:</label>
                            <select name="status" id="status" class="form-control">
                              <option value="">--All--</option>
                              <option value="pending">Pending</option>
                              <option value="approved">Approved</option>
                              <option value="disapproved">Disapproved</option>
                            </select>
                          </div> -->
                          <div class="col-md-3">
                            <label for="">Search:</label>
                            <input type="search" class="form-control" name="search_by" id="search_by" placeholder="serial no.">
                          </div>
                          <div class="col-md-3">
                            <label for="">From:</label>
                            <input type="date" class="form-control" name="search_by_date_from" id="search_by_date_from">
                          </div>
                          <div class="col-md-3">
                            <label for="">To:</label>
                            <input type="date" class="form-control" name="search_by_date_to" id="search_by_date_to">
                          </div>

                          <div class="col-md-2">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn_check_refresh" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-1">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary" style="background-color: var(--secondary);" onclick="location.reload();">
                              <i class="fas fa-sync-alt"></i>&nbsp;
                              <!-- Refresh -->
                            </button>
                          </div>
                        </div>
                        <!-- <div class="row">
                        </div> -->
                      </div>
                    </div>
                  </div>

                  <div class="card table-responsive p-0" style="height: 600px;">
                    <div class="card-body">
                    <div class="row mb-2" id="t_t1_breadcrumb">
                      <div class="col-12">
                        <ol class="breadcrumb bg-light mb-0">
                          <li class="breadcrumb-item"><a href="#" onclick="revisions2();">Back</a></li>
                          <li class="breadcrumb-item" id="lbl_c1"></li>
                        </ol>
                      </div>
                    </div>
                    <table class="table table-head-fixed text-nowrap table-hover table-striped text-center" id="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Revision Count</th>
                          <th>Serial No.</th>
                          <th>Batch No.</th>
                          <th>Group No.</th>
                          <!-- <th>Revised Date</th> -->
                        </tr>
                      </thead>
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

<?php include 'plugins/js/upload_history_script.php' ?>;
<?php include 'plugins/footer.php'; ?>