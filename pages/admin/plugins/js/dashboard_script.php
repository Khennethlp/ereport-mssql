<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_data();
        counts();
    });

    const load_data = () => {

        var serialNo = $('#search_by_serialNo').val();
        var batchNo = $('#search_by_batchNo').val();
        var groupNo = $('#search_by_groupNo').val();
        var trainingGroup = $('#search_by_tgroup').val();
        var fileName = $('#search_by_filename').val();
        var month = $('#search_by_month').val();
        var year = $('#search_by_year').val();

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
                month: month,
                year: year,
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