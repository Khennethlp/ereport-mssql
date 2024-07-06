<div class="modal fade bd-example-modal-xl" id="for_checking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- <div class="col-md-6">
                        <input type="text" id="id">
                        <input type="text" id="file_path">
                    <iframe src="" id="file_path_iframe" frameborder="0" height="650" width="100%" onchange="get_files();"></iframe>

                    </div> -->
                    <div class="card col-md-6 mt-5 p-3">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-5 mb-5">
                                    <label for="">Status:</label>
                                    <select class="form-control" name="" id="">
                                        <option value="approved">--Status--</option>
                                        <option value="approved">Approved</option>
                                        <option value="disapproved">Disapproved</option>
                                    </select>

                                </div>
                                <div class="col-md-5 mb-5">
                                    <label for="">Approval by:</label>
                                    <select class="form-control" name="" id="">
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
                                </div>
                            </div>

                            <div class="col-md-10 mb-5">
                                <label for="">Comment:</label>
                                <textarea class="form-control" name="comment" id="comment" rows="4" cols="5" maxlength="250"></textarea>
                            </div>
                            <div class="col-md-10">
                                <button class="form-control btn-info mb-3" onclick="">Submit</button>
                                <button class="form-control btn-secondary" onclick="history.back();">Back</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <div class="col-sm-3">
                    <!-- <button class="btn  btn-block" onclick="add_account();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button> -->
                </div>
            </div>
        </div>
    </div>
</div>