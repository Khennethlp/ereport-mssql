<script>
    document.addEventListener("DOMContentLoaded", function(){
        load_data();
    })

    const load_data = () => {
      
        // const status = document.getElementById('status').value;
        const checker_name = document.getElementById('checker_name').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const search_by = document.getElementById('search_by').value;

        // var stats = sessionStorage.setItem('status', status);

        $.ajax({
            type: "POST",
            url: '../../process/checker/load_history.php',
            cache: false,
            data: {
                method: 'history_checker_table',
                // status: status,
                search_by: search_by,
                date_from: date_from,
                date_to: date_to,
                checker_name: checker_name,
            },
            success: function(response) {
                document.getElementById('history_checker_table').innerHTML = response;
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }
</script>