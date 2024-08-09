<?php
$title = "E-REPORT SYSTEM";
$file_path = $_GET['file_path'];
$uploader = $_GET['uploader'];
$serial_no = $_GET['serial_no'] . '<br>';
$id = $_GET['id'];
$tgroup = $_GET['training_group'];
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
    <title><?= $title; ?> - UPLOADER</title>
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
        height: 500px;
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
        border-radius: 3px;
        cursor: pointer;
    }

    .btn_download {
        width: 20%;
        padding: 11px;
        background-color: #111;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
    }

    .btn_download:hover {
        color: #fff;
    }

    .fileDropArea {
        position: relative;
        display: flex;
        flex-direction: column;
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

    .fileDropArea.dragover {
        border-color: #007bff;
    }

    .fileDropArea input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .fileDropArea p {
        margin: 0;
        font-size: 16px;
        color: #999;
    }

    #files-area {
        margin-top: 15px;
        width: 100%;
        text-align: center;
    }

    .file-block {
        border-radius: 10px;
        background-color: rgba(144, 163, 203, 0.2);
        margin: 5px;
        color: initial;
        display: inline-flex;
    }

    .file-block>span.name {
        padding-right: 10px;
        width: max-content;
        display: inline-flex;
    }

    .file-delete {
        display: flex;
        width: 24px;
        color: initial;
        background-color: #6eb4ff00;
        font-size: large;
        justify-content: center;
        margin-right: 3px;
        cursor: pointer;
    }

    .file-delete:hover {
        background-color: rgba(144, 163, 203, 0.2);
        border-radius: 10px;
    }

    .file-delete>span {
        transform: rotate(45deg);
    }
</style>

<?php
require '../../process/conn.php';

$serial_no = $_GET['serial_no'];
$id = $_GET['id'];

$sql = "SELECT * FROM t_upload_file WHERE serial_no = :serial_no AND id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":serial_no", $serial_no);
$stmt->bindParam(":id", $id);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($rows) {
    foreach ($rows as $row) {
        $file_path = '../../../uploads/ereport/' . $row['serial_no'] . '/';
        $file_path .= $row['main_doc'] . '/';

        if (!empty($row['sub_doc'])) {
            $file_path .= $row['sub_doc'] . '/';
        }

        $file_path .= !empty($row['updated_file']) ? $row['updated_file'] : $row['file_name'];

        if (empty($row['updated_file'])) {
            if (!empty($row['sub_doc'])) {
                $file_path = $file_path . $row['file_name'];
            } else {
                $file_path = $file_path . $row['file_name'];
            }
        }
    }
}

$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
?>

<body onload="handleNonPdfFiles('<?php echo addslashes(htmlspecialchars($file_path)); ?>', '<?php echo addslashes(htmlspecialchars($file_extension)); ?>')">

    <div class="row">
        <div class="col-md-6">
            <div class="card m-3">
                <div class="card-body">
                    <div class="row">

                        <?php if ($file_path && $file_extension == 'pdf') : ?>
                            <button id="fullscreen-btn" style="width: 40%;" class="mx-2 mb-1" onclick="toggleFullscreen()"><i class="fas fa-expand"></i>&nbsp; Fullscreen</button>
                        <?php endif; ?>
                        <!-- <button id="fullscreen-btn" style="width: 40%;" class="mx-2 mb-1" onclick="toggleFullscreen()">Fullscreen</button> -->
                        <?php

                        if (file_exists($file_path)) {
                        ?>
                            <a class="btn_download mx-2 mb-1 ml-auto" href="<?php echo $file_path; ?>" download><i class="fas fa-download"></i> Download File</a>
                        <?php
                        } else {
                            echo 'File not found.';
                        }

                        ?>
                        <div class="col-md-12">
                            <div id="iframe-container">
                                <?php if ($file_path && $file_extension == 'pdf') : ?>
                                    <iframe class="w-100" id="my-iframe" src="<?php echo htmlspecialchars($file_path); ?>" frameborder="0" height="650" width="100%"></iframe>
                                <?php else : ?>
                                    <p class="text-center text-gray pt-5">
                                        <?php if ($file_extension == 'xls' || $file_extension == 'xlsx') : ?>
                                            Preview is not available for Excel files.<br>
                                            The file will be downloaded automatically or click the download button.
                                        <?php else : ?>
                                            Preview is not available for this file type.
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                                <!-- <iframe class="w-100" id="my-iframe" src="<?= $file_path ? $file_path : "No file to preview." ?>" frameborder="0" height="650" width="100%"></iframe> -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card m-3">
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">

                            <div class="">
                                <input type="hidden" id="file_extension" value="<?php echo $file_extension; ?>">
                                <input type="hidden" id="filepath" value="<?php echo $file_path; ?>">
                                <input type="hidden" id="update_id" value="<?php echo $id; ?>">
                                <input type="hidden" id="update_uploader_id" value="<?php echo $uploader; ?>">
                                <input type="hidden" id="update_training_group" value="<?php echo $tgroup; ?>">
                                <label for="series_no_label" class="d-inline-block mb-0 text-lg">Serial no:&nbsp;&nbsp;</label>
                                <p id="series_no_label" class="d-inline-block mb-0 text-lg"><?php echo $serial_no; ?></p>
                                <label for="series_no_label" class="d-inline-block mb-0z text-lg">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mb-3" id="checker_container">
                                <label for="">Check by:</label>
                                <Select class="form-control" id="check_by">
                                    <option value="">---Choose Checker---</option>
                                    <?php
                                    require '../../process/conn.php';

                                    $sql = "SELECT emp_id, fullname FROM m_accounts WHERE role = 'checker'";
                                    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        // Output data of each row
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Output data of each row
                                        foreach ($rows as $row) {

                                            echo '<option value="' . $row["emp_id"] . '">' . $row["fullname"] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No data available</option>';
                                    }
                                    ?>

                                </Select>
                            </div>
                            <div class="col-md-12 mb-3" id="approver_container">
                                <label for="">Approve by:</label>
                                <Select class="form-control" id="approved_by">
                                    <option value="">---Choose Approver---</option>
                                    <?php
                                    require '../../process/conn.php';

                                    $sql = "SELECT emp_id, fullname FROM m_accounts WHERE role = 'approver' ";
                                    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        // Output data of each row
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Output data of each row
                                        foreach ($rows as $row) {

                                            echo '<option value="' . $row["emp_id"] . '">' . $row["fullname"] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No data available</option>';
                                    }
                                    ?>

                                </Select>
                            </div>
                            <!-- <div class="row"> -->
                            <div class="col-md-12">
                                <label for="attachment">Upload Updated File:</label>
                                <div class="fileDropArea" id="fileDropArea">
                                    <input type="file" class="form-control p-1" id="attachment" name="file[]">
                                    <span id="fileName" class="text-center">Click or drop file here.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="form-control btn_submit mb-2" id="submit_upload_btn" onclick="updateUpload();">
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
    <!-- <script src="plugins/js/custom.js"></script> -->
    <script>
        // for file dropping 
        document.getElementById('attachment').addEventListener('change', function(event) {
            const fileInput = event.target;
            const fileNameSpan = document.getElementById('fileName');

            if (fileInput.files.length > 0) {
                fileNameSpan.textContent = fileInput.files[0].name;
            } else {
                fileNameSpan.textContent = 'Click or drop file here.';
            }
        });

        // for auto file downloading
        function downloadFile(filePath) {
            var link = document.createElement('a');
            link.href = filePath;
            link.download = filePath.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function handleNonPdfFiles(filePath, fileExtension) {
            if (fileExtension === 'xls' || fileExtension === 'xlsx') {
                downloadFile(filePath);
            }
        }

        // for toggling full screen
        function toggleFullscreen() {
            var iframe = document.getElementById('my-iframe');
            if (!document.fullscreenElement && !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && !document.msFullscreenElement) {
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
        document.addEventListener("fullscreenchange", function() {
            updateButton();
        });
        document.addEventListener("mozfullscreenchange", function() {
            updateButton();
        });
        document.addEventListener("webkitfullscreenchange", function() {
            updateButton();
        });
        document.addEventListener("MSFullscreenChange", function() {
            updateButton();
        });

        function updateButton() {
            var iframe = document.getElementById('my-iframe');
            var btn = document.getElementById('fullscreen-btn');
            if (document.fullscreenElement || document.mozFullScreenElement ||
                document.webkitFullscreenElement || document.msFullscreenElement) {
                btn.textContent = 'Exit Fullscreen';
            } else {
                btn.textContent = 'Fullscreen';
            }
        }
    </script>
</body>

</html>
<?php include 'plugins/js/update_upload_script.php' ?>;