<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/user_bar.php'; ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <!-- Alert -->

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
          <div class="card card-danger card-outline">
            <div class="card-header">
              <h3 class="card-title text-uppercase"><i class="fa fa-user-check"></i>&nbsp; Approver</h3>
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
                            <label for="">Status:</label>
                            <select name="status" id="approver_status" class="form-control">
                              <!-- <option value="">--All--</option> -->
                              <option value="pending">Pending</option>
                              <option value="approved">Approved</option>
                              <option value="disapproved">Disapproved</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <label for="">Search:</label>
                            <input type="search" class="form-control" name="" id="search_by" placeholder="search by serial no.">
                          </div>
                          <div class="col-md-3">
                            <label for="">From:</label>
                            <input type="date" class="form-control" name="" id="search_by_date_from">
                          </div>
                          <div class="col-md-3">
                            <label for="">To:</label>
                            <input type="date" class="form-control" name="" id="search_by_date_to">
                          </div>

                        </div>
                        <div class="row">
                          <div class="col-md-3">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn_check_refresh" onclick="load_data();">
                              <i class="fas fa-search"></i>&nbsp;
                              Search
                            </button>
                          </div>
                          <div class="col-md-2">
                            <label for="">&nbsp;</label>
                            <button class="form-control btn-secondary" onclick="location.reload();">
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
                          <th>Status</th>
                          <th>Serial No.</th>
                          <th>Checked By</th>
                          <th>Checked Date</th>
                        </tr>
                      </thead>
                      <tbody id="approver_table"> </tbody>
                    </table>
                    <div id="a_load_more" class="text-center" style="display: none;">
                      <p class="badge badge-dark border border-outline p-2 mt-3 " style="cursor: pointer;">Load More...</p>
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

<?php include 'plugins/js/index_script.php' ?>;
<?php include 'plugins/footer.php'; ?>
<?php //include 'plugins/js/pagination_script.php';
?>