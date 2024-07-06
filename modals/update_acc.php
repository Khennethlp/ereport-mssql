<div class="modal fade bd-example-modal-xl" id="update_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Update Account</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="id_acc" name="id_acc">
                    <div class="col-sm-4 mb-2">
                        <span><b>EmployeeID:</b></span>
                        <input type="text" id="empId_edit" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span><b>Full Name:</b></span>
                        <input type="text" id="fullname_edit" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span><b>Email:</b></span>
                        <input type="email" id="email_edit" class="form-control" placeholder="" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span><b>Username:</b></span>
                        <input type="text" id="username_edit" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <span><b>Password:</b></span>
                        <input type="text" id="password_edit" class="form-control" autocomplete="off">
                    </div>

                    <div class="col-md-4 mb-2">
                        <span><b>Role:</b></span>
                        <select id="role_edit" class="form-control">
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
                    <button class="btn btn-block btn-del" onclick="delete_account();" style="color:#111;height:34px;border-radius:.25rem;background: #bbb;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);">Delete </button>
                </div>
                <div class="col-sm-3">
                    <button class="btn subBtn btn-block" id="update_btn" onclick="update_account();" style="color:#fff;height:34px;border-radius:.25rem;background: #3765AA;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.5);">Update </button>
                </div>
            </div>
        </div>
    </div>
</div>