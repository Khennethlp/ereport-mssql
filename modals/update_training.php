<div class="modal fade bd-example-modal-xl" id="update_training" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Add New Training Title</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="update_train_id">
                <div class="col-md-12">
                    <label for="">Training Title:</label>
                    <input type="text" id="update_t_title" class="form-control" placeholder="e.g. Final Practice...">
                </div>

            </div>
            <div class="modal-footer ">
            <div class="col-sm-3">
                    <button class="btn btn-block" onclick="delete_trainings();" style="background: var(--danger) !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Delete</button>
                </div>
                <div class="col-sm-3">
                    <button class="btn  btn-block" onclick="update_trainings();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>