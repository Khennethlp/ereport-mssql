<script>
    const updateUpload = () => {
        var update_id = document.getElementById('update_id').value;
        var update_uploader_id = document.getElementById('update_uploader_id').value;
        // var update_serialNo = document.getElementById('series_no_label').text().trim();
        var update_serialNo = $('#series_no_label').text().trim();
        var fileInput = $('#attachment')[0];
        var file_attached = fileInput.files[0]; // Access the file correctly
        var updated_status = 'PENDING'; // Pending status for the checker

        var formData = new FormData();
        formData.append("method", "update_file_upload");
        formData.append("update_serialNo", update_serialNo);
        formData.append("update_id", update_id);
        formData.append("update_uploader_id", update_uploader_id);
        formData.append("updated_status", updated_status);

        // Only append the file if it's provided
        if (file_attached) {
            formData.append('file_attached', file_attached);
        }

        // Check if file is attached
        if (!file_attached) {
            Swal.fire({
                icon: 'warning',
                title: 'Please, upload updated file.',
                showConfirmButton: false,
                timer: 1000
            });
            return; // Exit the function if no file is attached
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
