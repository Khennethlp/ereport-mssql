<?php require 'process/login.php';

if (isset($_SESSION['username'])) {
  if ($_SESSION['role'] == 'admin') {
    header('location: pages/admin/index.php');
    exit;
  } elseif ($_SESSION['role'] == 'approver') {
    header('location: pages/approver/index.php');
    exit;
  } elseif ($_SESSION['role'] == 'uploader') {
    header('location: pages/uploader/index.php');
    exit;
  } elseif ($_SESSION['role'] == 'checker') {
    header('location: pages/checker/index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?></title>

  <link rel="icon" href="dist/img/e-report-icon.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<style>



</style>
<!-- background-color: #306BAC; -->
<body class="hold-transition login-page" 
style="
background-image: url('dist/img/web-bg.png');
background-size: cover;
background-repeat: no-repeat;
">
<!-- style="transform: translate(50%, 0);" -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="login-box col-md-8">
    <div class="row">
      <div class="col-md-6 d-flex justify-content-center align-items-center">
        <!-- <img src="dist/img/flat-img.png" alt="Image" class="img-fluid" style="max-width: 500%; height: auto;"> -->
      </div>
      <div class="col-md-6">
        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'error') { ?>
          <div class="alert alert-dismissible fade show" style="background-color: #C3423F; color: #fff;" role="alert">
            <strong>Oops!</strong> <?php echo $_SESSION['msg']; ?>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php }
        unset($_SESSION['status']); ?>
        <div class="card p-3" style="box-shadow: 4px 4px 8px 0 rgba(0, 0, 0, 0.5);">
          <div class="login-logo text-center">
            <img src="dist/img/e-report-bg.png" style="height:200px; width: auto;">
          </div>
          <div class="card-body login-card-body rounded">
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="login_form">
              <div class="form-group">
                <div class="input-group">
                  <select class="form-control" id="users" name="users" autocomplete="off" required>
                    <option disabled selected>--- Choose User ---</option>
                    <option value="Admin">Admin</option>
                    <option value="Approver">Approver</option>
                    <option value="Checker">Checker</option>
                    <option value="Uploader">Uploader</option>
                  </select>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-users"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" autofocus required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-user"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" required>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col">
                  <button type="submit" class="btn btn-block" name="Login" style="background-color: #306BAC; color: #fff;">Login</button>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <center style="color: #ccc; font-size: 12px;">
                    MADE WITH &nbsp;🤍&nbsp; BY KHENNETH
                  </center>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>

<!-- jQuery -->
<script src="plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<noscript>
  <br>
  <span>We are facing <strong>Script</strong> issues. Kindly enable <strong>JavaScript</strong>!!!</span>
  <br>
  <span>Call IT Personnel Immediately!!! They will fix it right away.</span>
</noscript>

</body>

</html>