<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/preloader.php'; ?>
<?php include 'plugins/sidebar/admin_bar.php'; ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0"><i class="fas fa-download"></i> STORE IN</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item ">Masterlist</li>
                    </ol>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase"><i class="fas fa-list-ul mr-2"></i>Document Masterlist</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 ml-auto mb-2">
                                        <button class="form-control active " data-toggle="modal" data-target="#add_docs"><i class="fas fa-plus"></i>&nbsp; Add Document</button>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Main Document</th>
                                                    <th>Sub Document</th>
                                                </tr>
                                            </thead>
                                            <tbody id="m_report_table"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-5">
                                    <div class="col-md-4 ml-auto mb-2">
                                        <button class="form-control active" data-toggle="modal" data-target="#add_training"><i class="fas fa-plus"></i>&nbsp; Add Training</button>
                                    </div>
                                    <div class="card-body table-responsive p-0" style="height: 350px;">
                                        <table class="table table-head-fixed text-nowrap table-hover " id="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Training Title</th>
                                                </tr>
                                            </thead>
                                            <tbody id="m_training_table"></tbody>
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



<?php include 'plugins/js/masterlist_script.php'; ?>
<?php include 'plugins/footer.php'; ?>