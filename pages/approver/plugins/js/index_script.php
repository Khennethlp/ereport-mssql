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

        const status = document.getElementById('approver_status').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const approver_id = document.getElementById('approver_id').value;
        const search_by = document.getElementById('search_by').value;

        var stats =sessionStorage.setItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_table',
                status: status,
                approver_id: approver_id,
                search_by: search_by,
                date_from: date_from,
                date_to: date_to,

            },
            success: function(response) {
                document.getElementById('approver_table').innerHTML = response;
            },
            error: function() {
                console.log("Error loading data");
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
        var file_status = sessionStorage.getItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_modal_table',
                serial_no: serial_no,
                id: id,
                status: file_status
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