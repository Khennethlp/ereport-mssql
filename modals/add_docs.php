<div class="modal fade bd-example-modal-xl" id="add_docs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>Add New Document</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-2">
                        <label for="">Main Document:</label>
                    <input type="text" id="main_doc" class="form-control" placeholder="e.g. Trainers Evaluation Results...">
                </div>
                <div class="col-md-12">
                    <label for="">Sub Document:</label>
                    <input type="text" id="sub_doc" class="form-control" placeholder="e.g. Theory Training...">
                    <!-- <p class="text-green text-sm">Sub Document can be empty.</p> -->
                </div>
                

            </div>
            <div class="modal-footer ">
                <div class="col-sm-3">
                    <button class="btn btn-block" onclick="add_reports();" style="background: #3765AA !important;color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal; box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>