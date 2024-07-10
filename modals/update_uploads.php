<style>
    .u-file-drop-area {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 205%;
        height: 210px;
        padding: 25px;
        border: 2px dashed #d1d1d1;
        border-radius: 5px;
        transition: border-color 0.3s;
        cursor: pointer;
        text-align: center;
    }

    .u-file-drop-area.dragover {
        border-color: #007bff;
    }

    .u-file-drop-area input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .u-file-drop-area p {
        margin: 0;
        font-size: 16px;
        color: #999;
    }
</style>
<div class="modal fade bd-example-modal-xl" id="update_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="exampleModalLabel">
                    <b>UPDATE DOCUMENT</b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row col-12 mt-2 ">
                        <input type="hidden" id="updateFile_id">
                        <input type="hidden" id="updateFile_serialNo">
                        <div class="col-md-12">
                            <div class="col-md-12 mb-3">
                                <label for="">Comment:</label>
                                <textarea class="form-control" name="" id="disapproved_comment" cols="10" rows="3" disabled></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Upload File:</label>
                                <!-- <input type="file" id="files" class="form-control" style="height: 112px;"> -->
                                <div class="form-group u-file-drop-area" id="u_fileDropArea">
                                    <input type="file" class="u-custom-file-input" id="file_update" name="file_update">
                                    <p>Click or Drop file here...</p>
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

                        <div class="col-md-3 mb-2 ml-auto">
                            <!-- <label for="">&nbsp;</label> -->
                            <button class="form-control btn_Submit" id="uploadBtn" onclick="updateUpload();">
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