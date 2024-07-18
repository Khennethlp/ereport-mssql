<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('c_load_more').addEventListener('click', function(e) {
            e.preventDefault();
            isPagination = true;
            load_data();
        });

        load_data();
        // checker();
    });

    // --------------------------------------------------------------------------

    let page = 1; // Initial page number
    const rowsPerPage = 10; // Number of rows to fetch per request
    let isPagination = false; // Flag to differentiate between initial load and pagination

    const load_data = () => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        const status = document.getElementById('status').value;
        const checker_id = document.getElementById('checker_id').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const search_by = document.getElementById('search_by').value;

        var stats = sessionStorage.setItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/checker/load_data.php',
            cache: false,
            data: {
                method: 'checker_table',
                status: status,
                search_by: search_by,
                date_from: date_from,
                date_to: date_to,
                checker_id: checker_id,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                document.getElementById('checker_table').innerHTML = response;
                // setTimeout(load_data, 5000);
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

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


    const checker = param => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        $('#id_no').val(id);
        $('#serial_label').text(serial_no);
        $('#serial_no').val(serial_no);

        var serial = sessionStorage.setItem('serial_no', serial_no);
        var stats = sessionStorage.getItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/checker/load_data.php',
            cache: false,
            data: {
                method: 'checker_modal_table',
                serial_no: serial_no,
                id: id,
                status: stats
            },
            success: function(response) {
                document.getElementById('checker_modal_table').innerHTML = response;
                $('#checker_modal').modal('hide');

            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }
</script>