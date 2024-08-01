<div class="modal fade bd-example-modal-xl" id="problem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Report a problem</b>
                </h5>
                <input type="hidden" value="<?php echo $reporter = $_SESSION['name']; ?>" id="reporter">
                <input type="hidden" value="<?php echo $reporter_id = $_SESSION['emp_id']; ?>" id="reporter_id">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-2">
                    <label for="">What seems to be the problem?</label>
                    <textarea id="main_doc" class="form-control" rows="5" placeholder="Write about the problems you encountered using this system."></textarea>
                </div>
                <div class="col-md-12">
                    <label for="">Submit Document:</label>
                    <input type="file" id="sub_doc" class="form-control" placeholder="e.g. Theory Training...">
                    <!-- <p class="text-green text-sm">Sub Document can be empty.</p> -->
                </div>


            </div>
            <div class="modal-footer ">
                <div class="col-sm-3">
                    <button class="btn btn-block" onclick="submit_report();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>