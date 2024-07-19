<script>
      $(document).ready(function() {
        document.getElementById('check_upload_container').style.display = 'none';

        $('#checker_status').on('change', function() {
            const status = $(this).val();
            if (status === 'Disapproved') {
                document.getElementById('check_upload_container').style.display = 'block';
            } else {
                document.getElementById('check_upload_container').style.display = 'none';
            }
        });
    });

     const upload_checked = () => {
    const id = $('#c_id').val();
    const status = $('#checker_status').val();
    const approver = $('#approver_select').val();
    const comment = $('#comment_checker').val();
    const fileInput = $('#attachment')[0];
    const file_attached = fileInput.files[0]; // Changed to access the file correctly
    const checked_by = $('#checked_by').val();
    const serial_no = $('#series_no_label').text().trim();

    var selectElement = document.getElementById('approver_select');
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var approver_id = selectedOption.getAttribute('data-emp-id');
    var approver_email = selectedOption.value;
    var approver_status = 'Pending';

    if (status === '') {
        Swal.fire({
            icon: 'info',
            title: 'Please Change Status.',
            showConfirmButton: false,
            timer: 1000
        });
        return;
    }

    if (status === 'Disapproved' && comment === '') {
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

    if (status === 'Approved' && approver === '') {
        Swal.fire({
            icon: 'info',
            title: 'Please Select Approver.',
            showConfirmButton: false,
            timer: 1000
        });
        return;
    }

    // Prepare the form data
    let formData = new FormData();
    formData.append('method', 'update_check_uploader');
    formData.append('status', status);
    formData.append('approver', approver);
    formData.append('comment', comment);
    formData.append('checked_by', checked_by);
    formData.append('id', id);
    formData.append('serial_no', serial_no);
    formData.append('approver_id', approver_id);
    formData.append('approver_email', approver_email);
    formData.append('approver_status', approver_status);

    // Only append the file if it's provided
    if (file_attached) {
        formData.append('file_attached', file_attached);
    }

    $.ajax({
        type: "POST",
        url: '../../process/checker/checker_process.php',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        success: function(response) {
            if (response == 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ok"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.close();
                        history.back();
                    }
                });

                // Clear form fields after successful submission
                $('#checker_status').val('');
                $('#approver_select').val('');
                $('#comment_checker').val('');
                $('#checked_by').val('');
                $('#checker_modal').modal('hide');

                // Reload data after submission if needed
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