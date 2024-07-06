<?php
//SESSION
include '../../process/login.php';

if (!isset($_SESSION['username'])) {
  header('location:../../');
  exit;
} else if ($_SESSION['role'] == 'user') {
  header('location: ../../pages/stores/index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?> - Admin</title>

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

    .btn-file {
      position: relative;
      overflow: hidden;
    }

    .btn-file input[type=file] {
      position: absolute;
      top: 0;
      right: 0;
      min-width: 100%;
      min-height: 100%;
      font-size: 100px;
      text-align: right;
      filter: alpha(opacity=0);
      opacity: 0;
      outline: none;
      cursor: inherit;
      display: block;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(1080deg);
      }
    }

    body.light-mode {
      color: black;
    }

    body.dark-mode {
      color: white;
    }

    .active {
      background-color: #275DAD !important;
      /*#000EA4*/
      border-bottom: 2px solid #ffffff !important;
      color: #fff;
    }

    .b-border {
      border-bottom: 2px solid #275DAD !important;
    }

    .btn-func {
      color: #3B83EF;
      /* background-color: #275DAD !important; */
      border-bottom: 1px solid #ccc !important;
    }

    .btn-func:hover {
      /* background-color: #4881D5 !important;#275DAD */
      border-bottom: 2px solid #5B616A !important;
      color: #80B3FF;
    }

    .btn-del {
      font-size: 13px;
      height: 35px;
      color: #FF7676;
      background: none;
      border: 1px solid #ccc !important;
    }

    .btn-del:hover {
      background-color: #FF7676 !important;
      color: #fff !important;
    }

    .subBtn:hover {
      background-color: #29339B !important;
    }

    .nav-link {
      cursor: pointer;
    }

    .custom-switch {
      display: flex;
      align-items: center;

    }

    .custom-control-label {
      cursor: pointer;
    }

    .theme-active {
      background-color: #4A6670;
    }

    .btn-theme {
      width: 100% !important;
    }

    .btn-theme:hover {
      background-color: #5A7B87;
      color: #fff !important;
      transition: ease-in-out .3s;
    }

    .btn-theme:hover .fas {
      color: #f5f5f5 !important;
      transition: ease-in-out .3s;
    }

    .fa-sort {
      font-size: 24px;
      color: #333;
    }

    .fa-sort-down {
      font-size: 24px;
      color: #333;
      transform: rotate(180deg);
    }
    .btn_Submit{
      background-color: #275DAD !important;
      color: #ffffff;
    }
    .btn-danger{
      background-color: #F8403A !important;
      color: #ffffff;
    }
    .btn-warning{
      background-color: #F9C04E !important;
      color: #ffffff;
    }

    .file-drop-area {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 210px;
      padding: 25px;
      border: 2px dashed #d1d1d1;
      border-radius: 5px;
      transition: border-color 0.3s;
      cursor: pointer;
      text-align: center;
    }
    .file-drop-area.dragover {
      border-color: #007bff;
    }
    .file-drop-area input[type="file"] {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
    }
    .file-drop-area p {
      margin: 0;
      font-size: 16px;
      color: #999;
    }

    .btn_refresh{
      background-color: #28a745;
    }
    /* .nav-link.no-caret::after {
      display: block;
    } */
  </style>
</head>
<!-- sidebar-collapse sidebar-mini-->

<body class="hold-transition sidebar-mini layout-fixed ">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand " id="navbar">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <div class="row">
          <li class="nav-item dropdown">
            <!-- <a class="nav-link dropdown-toggle no-caret" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Theme
            </a> -->
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <!-- <a class="dropdown-item">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1">
            <label class="custom-control-label " id="theme_label" for="customSwitch1">Dark Mode</label>
          </div>
        </a> -->

              <!-- <a class="dropdown-item ">
                <button class="btn btn-theme" id="dark-btn"> <i class="fas fa-moon text-secondary mr-2"></i> Dark</button>
              </a>
              <a class="dropdown-item">
                <button class="btn btn-theme " id="light-btn"> <i class="fas fa-sun text-warning mr-2"></i> Light</button>
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