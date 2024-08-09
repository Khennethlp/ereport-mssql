<script>
    $(document).ready(function() {
        document.getElementById('approver_container').style.display = 'none';
        const checkTrainingGroup = () => {
            const trainingGroup = document.getElementById('update_training_group').value;

            if (trainingGroup === 'MNTT' || trainingGroup === 'SEP') {
                document.getElementById('checker_container').style.display = 'none';
                document.getElementById('approver_container').style.display = 'block';
            } else {
                document.getElementById('checker_container').style.display = 'block';
                document.getElementById('approver_container').style.display = 'none';
            }
        };

        checkTrainingGroup();
        document.getElementById('update_training_group').addEventListener('change', checkTrainingGroup);
    });

    const updateUpload = () => {
        var update_id = document.getElementById('update_id').value;
        var update_uploader_id = document.getElementById('update_uploader_id').value;
        var update_training_group = document.getElementById('update_training_group').value;
        var approved_by = document.getElementById('approved_by').value;
        var check_by = document.getElementById('check_by').value;
        var update_serialNo = $('#series_no_label').text().trim();
        var fileInput = $('#attachment')[0];
        var file_attached = fileInput.files[0];

        var formData = new FormData();
        formData.append("method", "update_file_upload");
        formData.append("update_serialNo", update_serialNo);
        formData.append("update_id", update_id);
        formData.append("update_uploader_id", update_uploader_id);
        formData.append("update_training_group", update_training_group);
        formData.append("approved_by", approved_by);
        formData.append("check_by", check_by);

        if (file_attached) {
            formData.append('file_attached', file_attached);
        }

        if (!file_attached) {
            Swal.fire({
                icon: 'warning',
                title: 'Please, upload updated file.',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }
        
        if (update_training_group == 'MNTT' || update_training_group == 'SEP') {
            if (approved_by == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please, select approver.',
                    showConfirmButton: false,
                    timer: 1000
                });
                return; 
            }
        } else {
            if (check_by == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please, select checker.',
                    showConfirmButton: false,
                    timer: 1000
                });
                return;
            }
        }
        $.ajax({
            type: 'POST',
            url: "../../process/uploader/file_update.php", // Updating disapproved file
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'File updated successfully!',
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.close();
                            history.back();
                        }
                    });

                } else if (response == 'error') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Failed to update file.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (response == 'file error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File information not found.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (response.startsWith('Database error:')) {
                    Swal.fire({
                        icon: 'error',
                        title: response,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX error: ' + textStatus,
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }
</script>