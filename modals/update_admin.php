<div class="modal fade bd-example-modal-xl" id="update_admin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>UPDATE DATA</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="update_id">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="">Serial No:</label>
                        <input type="text" id="update_serial_no" class="form-control" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Batch No:</label>
                        <input type="text" id="update_batch" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Group No:</label>
                        <input type="text" id="update_group" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Month:</label>
                        <!-- <input type="text" id="update_month" class="form-control" placeholder=""> -->
                        <select name="update_month" id="update_month" class="form-control">
                              <option value=""></option>
                              <option value="January">January</option>
                              <option value="February">February</option>
                              <option value="March">March</option>
                              <option value="April">April</option>
                              <option value="May">May</option>
                              <option value="June">June</option>
                              <option value="July">July</option>
                              <option value="August">August</option>
                              <option value="September">September</option>
                              <option value="October">October</option>
                              <option value="November">November</option>
                              <option value="December">December</option>
                            </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Year:</label>
                        <input type="text" id="update_year" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Training Group:</label>
                        <!-- <input type="text" id="update_tgroup" class="form-control" placeholder=""> -->
                        <select class="form-control" name="update_tgroup" id="update_tgroup">
                            <option value=""></option>
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
                    <div class="col-md-6 mb-2">
                        <label for="">Document:</label>
                        <!-- <input type="text" id="update_doc" class="form-control" placeholder=""> -->
                        <select class="form-control" name="update_doc" id="update_doc">
                              <option value="" selected></option>
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
                    <div class="col-md-6 mb-2">
                        <label for="">Filename:</label>
                        <input type="text" id="update_filename" class="form-control" placeholder="">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Checked By:</label>
                        <input type="text" id="update_checkedBy" class="form-control" placeholder="" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Checked Date:</label>
                        <input type="text" id="update_checkedDate" class="form-control" placeholder="" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Approved By:</label>
                        <input type="text" id="update_approvedBy" class="form-control" placeholder="" readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="">Approved Date:</label>
                        <input type="text" id="update_approvedDate" class="form-control" placeholder="" readonly>
                    </div>
                </div>

            </div>
            <div class="modal-footer ">
                <div class="col-sm-3">
                    <button class="btn btn-block" class="close" data-dismiss="modal" aria-label="Close" style="background: var(--danger) !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Cancel</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-block" onclick="update_admin();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>