<!-- Modal -->
<div class="modal fade" id="remove_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm " role="document">
        <div class="modal-content  align-items-center">
            <div class="modal-body ">
                <input type="hidden" id="del_id">
                <input type="hidden" id="del_serial">
                <center><p style="font-size: 18px;">Are you sure you want to delete this data?</p></center>
                <hr>
                <input type="button" value="Confirm" class="btn btn-danger col-sm-12" onclick="remove_data();">
                <input type="button" data-dismiss="modal" aria-label="Close" value="Cancel" class="btn btn-white col-sm-12">
            </div>
        </div>
    </div>
</div>