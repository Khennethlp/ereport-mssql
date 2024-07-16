<?php
//SESSION
include '../../process/login.php';

if (!isset($_SESSION['username'])) {
  header('location:../../');
  exit;
} else if ($_SESSION['role'] == 'admin') {
  header('location: ../../pages/admin/index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title class="text-uppercase"><?= $title; ?> - CHECKER</title>

  <link rel="icon" href="../../dist/img/e-report-icon.png" type="image/x-icon" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="../../dist/css/font.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Sweet Alert -->
  <link rel="stylesheet" href="../../plugins/sweetalert2/dist/sweetalert2.min.css">
  <style>
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #536A6D;
      width: 50px;
      height: 50px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(1080deg);
      }
    }

    .active {
      background-color: #306BAC !important;
      /* border-bottom: 2px solid #ffffff !important; */
    }

    .b-border {
      border-bottom: 2px solid #306BAC !important;
    }

    .btn_check_refresh{
      background-color: #306BAC !important;
      color: #ffffff;
    }

   /* textarea:focus, select:focus
    {
      border: 2px solid #0F85E6;
    } */

    /* input[type=text]:focus,
    input[type=date]:focus,
    input[list]:focus {
      border: 2.5px solid #379634;
    } */

    /* .custom-switch {
      display: flex;
      align-items: center;
      
    }
     .custom-control-label{
      cursor: pointer;
    } */
    .nav-link.no-caret::after {
      display: none;
    }
    input[type=date], 
    input[type=search], 
    input[type=date],
    #status, 
    .btn_check_refresh, .btn_check{
      height: 40px;
    }
   
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed "> <!---- change body to light mode => (remove dark mode)----->
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="../../dist/img/e-report-icon.png" alt="logo" height="60" width="60">
      <noscript>
        <br>
        <span>We are facing <strong>Script</strong> issues. Kindly enable <strong>JavaScript</strong>!!!</span>
        <br>
        <span>Call IT Personnel Immediately!!! They will fix it right away.</span>
      </noscript>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light" id="navbar" style="background-color: #306BAC;">
      <!-- Left navbar links -->

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->

      <ul class="navbar-nav ml-auto">
        <div class="row">
          <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle no-caret" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
          Theme
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1">
              <label class="custom-control-label " id="theme_label" for="customSwitch1">Dark Mode</label>
            </div>
          </a>
        </div>
      </li> -->
          <li class="nav-item dropdown mx-4">
            <!-- <a class="nav-link dropdown-toggle no-caret" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="far fa-user-circle i-user "></i>
            </a> -->
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <!-- <p class="text-center">
                <?php echo $_SESSION['name'] ?>
              </p>
              <hr> -->
           
              <!-- <a class="dropdown-item " data-toggle="modal" data-target="#logout_modal">
                <i class="fas fa-sign-out-alt mr-3 text-md"></i>
                LOGOUT
              </a> -->
            </div>
          </li>

          <!-- <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li> -->
        </div>
      </ul>
    </nav>
    <!-- /.navbar -->