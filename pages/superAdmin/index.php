<?php require '../../process/login.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?></title>

  <link rel="icon" href="../../dist/img/e-report-icon.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../../dist/css/font.min.css">

  <link rel="stylesheet" href="../../dist/css/datatable/dataTables.dataTables.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Sweet Alert -->
  <link rel="stylesheet" href="../../plugins/sweetalert2/dist/sweetalert2.min.css">

  <link rel="stylesheet" href="../../plugins/datatable/dist/dataTables.dataTables.min.css">
</head>
<!-- background-color: #306BAC; -->
<body class="hold-transition login-page" 
style="
background-image: url('../../dist/img/web-bg.png');
background-size: cover;
background-repeat: no-repeat;
">
<!-- style="transform: translate(50%, 0);" -->
  <div class="login-box" >
    <div class="col-md-12">
      <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'error') { ?>
        <div class="alert alert-dismissible fade show" style="background-color: #C3423F; color: #fff; " role="alert">
          <strong>Oops!</strong> <?php echo $_SESSION['msg']; ?>
          <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php }
      unset($_SESSION['status']); ?>
    </div>
    <!-- /.login-logo -->
    <div class="card p-3" style="box-shadow: 4px 4px 8px 0 rgba(0, 0, 0, 0.5);">
      <div class="login-logo">
        <!-- <img src="../../dist/img/e-report-bg.png" style="height:200px; width: auto;"> -->
        <h2><b>Super Admin</b></h2>
      </div>
      <div class="card-body login-card-body rounded">
        <!-- <p class="login-box-msg"><b>Sign in to start your session</b></p> -->

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="login_form">
        <!-- <form action="../plugins/login.php" method="POST" id="login_form"> -->
       
          <!-- <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" autofocus required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
          </div> -->
          <div class="form-group">
            <div class="input-group">
              <input type="password" class="form-control" id="superPassword" name="superPassword" placeholder="Password" autocomplete="off" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col">
              <button type="submit" class="btn btn-block" name="superLogin" style="background-color: #306BAC; color: #fff;">Login</button>
            </div>
          </div>
          <!-- <div class="row mb-2">
            <div class="col">
              <a type="button" href="pages/viewer/index.php" class="btn bg-danger btn-block" id="wi">Viewer Page</a>
            </div>
          </div> -->
          <div class="row">
            <!-- <div class="col">
              <center style="color: #ccc; font-size: 12px;">
               <em> MADE WITH &nbsp;🍔&nbsp;  BY KHENNETH </em> 
              </center>
            </div> -->
          </div>
        </form>
      </div>
    </div>
  </div>


</body>
</html>