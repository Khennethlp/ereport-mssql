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
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Uploaded Record Masterlist</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <div class="row">
                                            <label for="sortBy">Sort by:</label>
                                            
                                            <div class="col-md-1">
                                                <select name="sortBy" id="sortBy" class="form-control">
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 520px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Filename</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Accounts Masterlist</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable2">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Document</th>
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Revised Files Masterlist</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <!-- <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button> -->
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="myDataTable3">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Serial No.</th>
                                                    <th>Uploaded Document</th>
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
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

<?php
include 'includes/footer.php';
?>