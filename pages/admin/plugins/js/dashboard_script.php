<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_data();
        counts();
    });

    document.addEventListener("keyup", function() {
        load_data();
    });

    const load_data = () => {

        var serialNo = $('#search_by_serialNo').val();
        var batchNo = $('#search_by_batchNo').val();
        var groupNo = $('#search_by_groupNo').val();
        var trainingGroup = $('#search_by_tgroup').val();
        var fileName = $('#search_by_filename').val();
        var date_from = $('#search_by_date_from').val();
        var date_to = $('#search_by_date_to').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/load_data.php",
            cache: false,
            data: {
                method: 'load_data',
                serialNo: serialNo,
                batchNo: batchNo,
                groupNo: groupNo,
                trainingGroup: trainingGroup,
                fileName: fileName,
                date_from: date_from,
                date_to: date_to,
            },
            success: function(response) {
                document.getElementById('admin_dashboard_table').innerHTML = response;
            }
        });
    }

    const counts = () => {
        $.ajax({
            type: "POST",
            url: "../../process/admin/load_data.php",
            data: {
                method: 'counts',
            },
            success: function(response) {
                $('#approved_count').html(response);

            }
        });
    }
</script>