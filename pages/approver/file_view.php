<?php
$title = "E-REPORT SYSTEM";
$file_path = $_GET['file_path'];
$approver = $_GET['approver'];
$serial_no = $_GET['serial_no'];
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title><?= $title; ?> - CHECKER</title>
</head>

<style>
    body {
        background-color: #F6F6F6;
        overflow-x: hidden;
    }

    .btn_submit {
        background-color: #275DAD !important;
        color: #ffffff;
    }

    .btn_submit:hover {
        background-color: #2F6CC8 !important;
        color: #ffffff;
    }

    .btn_close {
        background-color: #111 !important;
        color: #ffffff;
    }

    .btn_close:hover {
        background-color: #222 !important;
        color: #ffffff;
    }

    textarea:focus,
    select:focus {
        border: 2px solid #0F85E6 !important;
    }

    #iframe-container {
        /* position: relative; */
        width: 100%;
        height: 650px;
    }
    iframe {
        border: none;
        width: 100%;
        height: 100%;
    }
    #fullscreen-btn {
        /* position: absolute; */
        top: 10px;
        right: 10px;
        /* z-index: 1; */
        padding: 10px;
        background-color: #275DAD;
        color: #fff;
        border: none;
        cursor: pointer;
    }
</style>

<body>
    <div class="row">
        <div class="col-md-6">
            <div class="card m-3">
                <button id="fullscreen-btn" onclick="toggleFullscreen()">Fullscreen</button>
                <div class="card-body">
                    <div id="iframe-container">
                        <iframe id="my-iframe" src="<?= htmlspecialchars($file_path) ? htmlspecialchars($file_path) : "No file to preview." ?>" frameborder="0" height="650" width="100%"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card m-3">
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">
                        
                            <div class="ml-auto mb-5">
                            <input type="hidden" id="a_id" value="<?php echo $id; ?>">
                                <input type="hidden" id="approved_id" value="<?php echo $approver; ?>">
                                <label for="series_no_label" class="d-inline-block mb-0 text-lg">Serial_no:</label>
                                <p id="series_no_label" class="d-inline-block mb-0 text-lg"><?= $serial_no; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="">Status:</label>
                                <select class="form-control" name="status_approver" id="status_approver">
                                    <option value="">---Status---</option>
                                    <option value="approved">Approve</option>
                                    <option value="disapproved">Disapprove</option>
                                </select>

                            </div>
                            
                            <!-- <div class="col-md-6 mb-5">
                                <label for="">Approval by:</label>
                                <select class="form-control" name="approver_select" id="approver_select">
                                    <option value="">---Choose Approver---</option>
                                    <?php
                                    require '../../process/conn.php';

                                    $sql = "SELECT fullname, email FROM m_accounts WHERE role = 'approver'";
                                    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        // Output data of each row
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Output data of each row
                                        foreach ($rows as $row) {
                                            echo '<option value="' . $row["email"] . '">' . $row["fullname"] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No data available</option>';
                                    }
                                    ?>
                                </select>
                            </div> -->
                            <div class="col-md-12">
                                <label for="">Comment:</label>
                                <textarea class="form-control" name="comment_approver" id="comment_approver" rows="3" cols="5" maxlength="250"></textarea>
                            </div>
                        
                            <div class="col-md-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="form-control btn_submit mb-2" id="submit_upload_btn" onclick="upload_approved();">
                                            <i class="fas fa-paper-plane"></i>&nbsp;
                                            Submit</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="form-control btn_close" onclick="window.close(); history.back();">
                                            <i class="fas fa-sign-out-alt "></i>
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="../../plugins/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- SweetAlert2 -->
    <script type="text/javascript" src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.js"></script>
    <script src="plugins/js/custom.js"></script>

    <script>
        
        function toggleFullscreen() {
          var iframe = document.getElementById('my-iframe');
          if (!document.fullscreenElement && !document.mozFullScreenElement &&
              !document.webkitFullscreenElement && !document.msFullscreenElement ) {
              if (iframe.requestFullscreen) {
                  iframe.requestFullscreen();
              } else if (iframe.msRequestFullscreen) {
                  iframe.msRequestFullscreen();
              } else if (iframe.mozRequestFullScreen) {
                  iframe.mozRequestFullScreen();
              } else if (iframe.webkitRequestFullscreen) {
                  iframe.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
              }
          } else {
              if (document.exitFullscreen) {
                  document.exitFullscreen();
              } else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
              } else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
              } else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
              }
          }
        }
        
        // Optional: Detect fullscreen change and update button text
        document.addEventListener("fullscreenchange", function () {
          updateButton();
        });
        document.addEventListener("mozfullscreenchange", function () {
          updateButton();
        });
        document.addEventListener("webkitfullscreenchange", function () {
          updateButton();
        });
        document.addEventListener("MSFullscreenChange", function () {
          updateButton();
        });
        
        function updateButton() {
          var iframe = document.getElementById('my-iframe');
          var btn = document.getElementById('fullscreen-btn');
          if (document.fullscreenElement || document.mozFullScreenElement ||
              document.webkitFullscreenElement || document.msFullscreenElement ) {
              btn.textContent = 'Exit Fullscreen';
          } else {
              btn.textContent = 'Fullscreen';
          }
        }
            </script>
</body>
</html>
<?php include 'plugins/js/index_script.php' ?>;