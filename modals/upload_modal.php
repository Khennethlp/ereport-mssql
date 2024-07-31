<div class="modal fade bd-example-modal-xl" id="upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>UPLOAD DOCUMENT</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
      
            <div class="modal-body">
                <div class="row">
                    <div class="row col-12 mt-2 ">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="uploader_name" value="<?= $_SESSION['name']; ?>">
                                <input type="hidden" class="form-control" id="uploader_id" value="<?= $_SESSION['emp_id']; ?>">
                                <input type="hidden" class="form-control" id="uploader_email" value="<?= $_SESSION['email']; ?>">
                            </div>
                            <div class="row">
                       
                                <div class="col-md-4 mb-3">
                                    <label for="">Batch No.</label>
                                    <input type="text" class="form-control" id="batch_no">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="">Group No.</label>
                                    <input type="text" class="form-control" id="group_no">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="">Training Group:</label>
                                    <select class="form-control" name="training_group" id="training_group">
                                        <option value="">--SELECT TRAINING GROUP--</option>
                                        <?php
                                        require '../../process/conn.php';

                                        $sql = "SELECT DISTINCT training_title FROM t_training_group";
                                        $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                        $stmt->execute();

                                        if ($stmt->rowCount() > 0) {
                                            // Output data of each row
                                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Output data of each row
                                            foreach ($rows as $row) {
                                                echo '<option value="' . $row["training_title"] . '">' . $row["training_title"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No data available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="main_doc">Document:</label>
                                        <!-- <input type="text" class="form-control"> -->
                                        <select class="form-control" name="main_doc" id="main_doc" onchange="fetch_sub_doc();">
                                            <option value="" selected>--SELECT DOCUMENT--</option>
                                            <?php
                                            require '../../process/conn.php';

                                            $sql = "SELECT DISTINCT main_doc FROM m_report_title";
                                            $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                            $stmt->execute();

                                            if ($stmt->rowCount() > 0) {
                                                // Output data of each row
                                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                // Output data of each row
                                                foreach ($rows as $row) {
                                                    echo '<option value="' . $row["main_doc"] . '">' . $row["main_doc"] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No data available</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3" id="sub_doc_container">
                                        <label for="sub_doc">Sub Document:</label>
                                        <select class="form-control" name="sub_doc" id="sub_doc">
                                            <option value="">--Select Sub Document--</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3" id="checker_container">
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
                                    <div class="col-md-4 mb-3" id="approver_container">
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="files">Upload File:</label>
                                    <!-- <input type="file" id="files" class="form-control" style="height: 112px;"> -->
                                    <div class="form-group file-drop-area" id="fileDropArea">
                                        <input type="file" class="custom-file-input" id="files" name="files">
                                        <p>Click or Drop file here</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="files-names"></label>
                                    <p id="files-area">
                                        <span id="filesList">
                                            <span id="files-names"></span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer ">
                <div class="col-sm-12">
                    <div class="row mt-3 ">
                        <div class="col-md-2 mb-2">
                            <!-- <label for="">&nbsp;</label> -->
                            <button class="form-control btn btn-secondary" id="clearbtn" onclick="clear_btn();">
                                <i class="fas fa-broom"></i>&nbsp;
                                Clear All
                            </button>
                        </div>

                        <div class="col-md-3 mb-2 ml-auto">
                            <!-- <label for="">&nbsp;</label> -->
                            <button class="form-control btn_Submit" id="uploadBtn" onclick="upload();">
                                <i class="fas fa-paper-plane"></i>&nbsp;
                                Submit
                            </button>
                        </div>
                    </div>
                    <!-- <button class="btn  btn-block" onclick="" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button> -->
                </div>
            </div>
        </div>
    </div>
</div>