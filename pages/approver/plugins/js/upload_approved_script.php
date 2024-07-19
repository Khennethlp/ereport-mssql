<script>
    $(document).ready(function() {
        document.getElementById('approve_upload_container').style.display = 'none';

        $('#status_approver').on('change', function() {
            const status = $(this).val();
            if (status === 'Disapproved') {
                document.getElementById('approve_upload_container').style.display = 'block';
            } else {
                document.getElementById('approve_upload_container').style.display = 'none';
            }
        });
    });

    const upload_approved = () => {
        const id = $('#a_id').val();
        const status = $('#status_approver').val();
        const comment = $('#comment_approver').val();
        const approver_id = $('#approved_id').val();
        const serial_no = $('#series_no_label').text().trim();
        const fileInput = $('#attachment')[0];
        const file_attached = fileInput.files[0]; // Changed to access the file correctly

        if (status === '') {
            Swal.fire({
                icon: 'info',
                title: 'Please Change Status.',
                text: 'Information',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }

        if (status === 'disapproved' && comment === '') {
            Swal.fire({
                icon: 'info',
                title: 'Please provide a comment for disapproval.',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }
        if (status === 'Disapproved' && file_attached === undefined) {
            Swal.fire({
                icon: 'info',
                title: 'Please upload the disapproved file.',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }

        // Prepare the form data
        let formData = new FormData();
        formData.append('method', 'update_approved_uploader');
        formData.append('id', id);
        formData.append('serial_no', serial_no);
        formData.append('status', status);
        formData.append('comment', comment);
        formData.append('approver_id', approver_id);

        // Only append the file if it's provided
        if (file_attached) {
            formData.append('file_attached', file_attached);
        }
        $.ajax({
            type: "POST",
            url: '../../process/approver/approver_process.php',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        // showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok"
                        // timer: 1000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.close();
                            history.back();

                        }
                    });

                    $('#checker_status').val('');
                    $('#comment_checker').val('');
                } else if (response == 'error') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'An error occurred during submission. Please try again.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (response == 'approver not found') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Approver not found',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Something went wrong. Please try again.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Something went wrong. Please try again.',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }
</script>