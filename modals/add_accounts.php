<div class="modal fade bd-example-modal-xl" id="add_acc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Add Account</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <span><b>Employee ID:</b></span>
                        <input type="text" id="add_emp_id" class="form-control mb-2" placeholder="e.g. 20-01234" autocomplete="off">
                    </div>
                    <div class="col-sm-4">
                        <span><b>Full Name:</b></span>
                        <input type="text" id="add_fullname" class="form-control mb-2" placeholder="e.g. Juan Dela Cruz" autocomplete="off">
                    </div>
                    <div class="col-sm-4">
                        <span><b>Email:</b></span>
                        <input type="email" id="add_email" class="form-control mb-2" placeholder="e.g. sample@furukawaelectric.com" autocomplete="off">
                    </div>
                    <div class="col-sm-4  mt-2">
                        <span><b>Username:</b></span>
                        <input type="text" id="add_username" class="form-control mb-2" placeholder="e.g. 20-01234" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mt-2">
                        <span><b>Password:</b></span>
                        <input type="password" id="add_password" class="form-control mb-2" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mt-2">
                        <span><b>Role:</b></span>
                        <select id="add_role" class="form-control">
                            <option value="">Select User Type</option>
                            <option value="admin">Admin</option>
                            <option value="approver">Approver</option>
                            <option value="checker">Checker</option>
                            <option value="uploader">Uploader</option>
                        </select>
                    </div>
                </div>
                <br>
            </div>
            <div class="modal-footer ">
                <div class="col-sm-3">
                    <button class="btn  btn-block" onclick="add_account();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>