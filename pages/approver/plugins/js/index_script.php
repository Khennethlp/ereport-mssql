<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('a_load_more').addEventListener('click', function(e) {
        e.preventDefault();
        isPagination = true;
        load_data();
    });
    load_data();
    });


    const load_data = () => {

        const status = document.getElementById('status').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const approver_name = document.getElementById('approver_name').value;
        const search_by = document.getElementById('search_by').value;

        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_table',
                status: status,
                approver_name: approver_name,
                // date_from: date_from,
                // date_to: date_to,
                // search_by: search_by,
                
            },
            success: function(response) {
                document.getElementById('approver_table').innerHTML = response;
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    const upload_approved = () => {
        const id = $('#a_id').val();
        const status = $('#status_approver').val();
        const comment = $('#comment_approver').val();
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
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Close tab"
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

    const approver = param => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        $('#id_no').val(id);
        $('#serial_label').text(serial_no);
        $('#serial_no').val(serial_no);

        console.log(param);

        var serial = sessionStorage.setItem('serial_no', serial_no);
        var stats = sessionStorage.getItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_modal_table',
                serial_no: serial_no,
                id: id,
                status:stats
            },
            success: function(response) {
                document.getElementById('approver_modal_table').innerHTML = response;
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

</script>