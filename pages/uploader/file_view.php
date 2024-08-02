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
    <title><?= $title; ?> - APPROVER</title>
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
        height: 633px;
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

<body>
    <div class="row">
        <div class="col-md-6">
            <div class="card m-3">
                <!-- <button id="fullscreen-btn" onclick="toggleFullscreen()">Fullscreen</button> -->
                <div class="card-body">
                    <div class="row">
                        <button id="fullscreen-btn" style="width: 40%;" class="mx-2 mb-1" onclick="toggleFullscreen()">Fullscreen</button>
                        <!-- <button id="btn_download" class="">Download</button> -->
                        <!-- <a class="btn_download w-50 ml-auto" href="<?php urlencode($file_path) ?>" download>Download</a> -->
                        <?php
                        require '../../process/conn.php';

                        // Assuming you've sanitized and validated these inputs to prevent SQL injection
                        $serial_no = $_GET['serial_no'];
                        $id = $_GET['id'];

                        // Fetch the file details from the database
                        $sql = "SELECT * FROM t_upload_file WHERE serial_no = :serial_no AND id = :id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(":serial_no", $serial_no);
                        $stmt->bindParam(":id", $id);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // $result = $stmt->rowCount();

                        if ($rows) {
                            foreach ($rows as $row) {
                                // Constructing the file path
                                $file_path = '../../../uploads/ereport/' . $row['serial_no'] . '/';
                                $file_path .= $row['main_doc'] . '/';
                                // $base_path = '../../../uploads/ereport/' . $row['serial_no'] . '/' . $row['main_doc'] . '/';
                                // $file_path = $base_path;
                                // if (!empty($row['sub_doc'])) {
                                //     $file_path .= $row['sub_doc'] . '/';
                                // }
                                if (!empty($row['sub_doc'])) {
                                    $file_path .= $row['sub_doc'] . '/';
                                }

                                $file_path .= !empty($row['updated_file']) ? $row['updated_file'] : $row['file_name'];

                                // If 'updated_file' is empty, adjust the path to the base path with the file name
                                if (empty($row['updated_file'])) {
                                    if (!empty($row['sub_doc'])) {
                                        $file_path = $file_path . $row['file_name'];
                                    } else {
                                        $file_path = $file_path . $row['file_name'];
                                    }
                                }

                                // Check if the file exists
                                if (file_exists($file_path)) {
                        ?>
                                    <a class="btn_download mx-2 mb-1 ml-auto" href="<?php echo $file_path; ?>" download>Download</a>
                                    <!-- <button id="btn_download" style="width: 40%;" class="mx-2 mb-1 ml-auto" href="<?php echo $file_path; ?>" download>Download</button> -->
                        <?php
                                } else {
                                    echo 'File not found.';
                                }
                            }
                        } else {
                            echo 'No files found for the provided serial number and ID.';
                        }
                        ?>
                        <div class="col-md-12">
                            <div id="iframe-container">
                                <iframe class="w-100" id="my-iframe" src="<?= $file_path ? $file_path : "No file to preview." ?>" frameborder="0" height="650" width="100%"></iframe>
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

                                    $sql = "SELECT emp_id, fullname FROM m_accounts WHERE role = 'approver'";
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
                            <label for="attachment">Upload Updated File:</label>
                            <!-- <div class="row"> -->
                            <div class="col-md-12">
                                <input type="file" class="form-control p-1" id="attachment" name="file[]">
                                <div class="col-md-12" id="update_upload_container">
                                    <!-- <div class="form-group fileDropArea" id="fileDropArea">
                                            <input type="file" class="custom-file-input" id="attachment" name="file[]">
                                            <p>Click or Drop file here</p>
                                            <div id="files-area">
                                                <span id="filesList">
                                                    <span id="files-names"></span>
                                                </span>
                                            </div>
                                        </div> -->
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
        // $(document).ready(function() {
        //    // initializeFileInput("#files", "#filesList > #files-names");
        // });

        // const dt = new DataTransfer(); // Allows manipulation of the files of the input file

        // $("#attachment").on('change', function(e) {
        //     // Clear the DataTransfer object and the displayed file list
        //     dt.clearData();
        //     $("#filesList > #files-names").empty();

        //     // Ensure only one file is handled
        //     if (this.files.length > 0) {
        //         let fileBloc = $('<span/>', {
        //                 class: 'file-block'
        //             }),
        //             fileName = $('<span/>', {
        //                 class: 'name',
        //                 text: this.files.item(0).name
        //             });

        //         // fileBloc.append('<span class="file-delete"><span>+</span></span>')
        //             // .append(fileName);
        //         $("#filesList > #files-names").append(fileName);

        //         // Add the single file to the DataTransfer object
        //         dt.items.add(this.files[0]);

        //         // Update the input file with the new DataTransfer files
        //         this.files = dt.files;


        //         $('span.file-delete').click(function() {
        //             let name = $(this).next('span.name').text();

        //             $(this).parent().remove();
        //             // for (let i = 0; i < dt.items.length; i++) {

        //             //     if (name === dt.items[i].getAsFile().name) {

        //             //         dt.items.remove(i);
        //             //         continue;
        //             //     }
        //             // }

        //             document.getElementById('attachment').files = dt.files;
        //         });
        //     }
        // });
    </script>


    <script>
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