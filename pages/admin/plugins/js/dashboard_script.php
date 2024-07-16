<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_data(); 
    });

    document.addEventListener("keyup", function(){
        load_data(); 
    });

    const load_data = () => {

        var search = $('#search_by').val();
        // var status = $('#_status').val();
        var date_from = $('#search_by_date_from').val();
        var date_to = $('#search_by_date_to').val();

        $.ajax({
            type: "POST",
            url: "../../process/admin/load_data.php",
            cache: false,
            data: {
                method: 'load_data',
                search: search,
                // status: status,
                date_from: date_from,
                date_to: date_to,
            },
            success: function(response) {
                document.getElementById('admin_dashboard_table').innerHTML = response;
            }
        });
    }
</script>