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
        const approved_id = $('#approved_id').val();
        const serial_no = $('#series_no_label').text().trim();

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
        if (status === 'Disapproved') {
            document.getElementById('approve_upload_container').style.display = 'block';
        }

        $.ajax({
            type: "POST",
            url: '../../process/approver/approver_process.php',
            cache: false,
            data: {
                method: "update_approved_uploader",
                id: id,
                serial_no: serial_no,
                status: status,
                comment: comment,
                approved_id: approved_id,
            },
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
                            load_data();

                        }
                    });

                    $('#checker_status').val('');
                    $('#comment_checker').val('');
                    load_data();
                } else if (response == 'error') {
                    Swal.fire({
                        icon: 'info',
                        title: 'An error occurred during submission. Please try again.',
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