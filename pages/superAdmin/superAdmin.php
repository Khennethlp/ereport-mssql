<?php
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- BODY -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0"><i class="fas fa-download"></i> STORE IN</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item ">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Uploaded Record Masterlist</h3>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <!-- <input type="hidden" id="del_id"> -->
                            <div class="col-md-1">
                                <label for="sortBy">Sort by:</label>
                                <select name="sortBy" id="sortBy" class="form-control">
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Search by Serial No</label>
                                <input type="search" class="form-control" id="search_by_serialNo">
                            </div>
                            <div class="col-md-2">
                                <label for="">&nbsp;</label>
                                <button  class="form-control btn-success" onclick="load_data()">Search</button>
                                <!-- <input type="text" class="form-control"> -->
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0" style="height: 520px;">
                        <table class="table table-head-fixed text-nowrap table-hover " id="" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Status</th>
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
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="load_table"></tbody>
                        </table>
                    </div>
                    <div class="m-3" id="count">
                        <p id="total_count"></p>
                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

<?php
include 'plugins/js/sup_script.php';
include 'includes/footer.php';
?>