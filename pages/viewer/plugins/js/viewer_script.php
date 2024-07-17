<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_data(); // Initial load
    });

    let page = 1; // Initial page number
    const rowsPerPage = 20; // Number of rows to fetch per request

    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        // Retrieve search parameters
        var search_by_serial = document.getElementById('search_by_serial').value;
        var search_by_batch = document.getElementById('search_by_batch').value;
        var search_by_group = document.getElementById('search_by_group').value;
        var search_by_training = document.getElementById('search_by_training').value;
        var search_by_filename = document.getElementById('search_by_filename').value;
        var date_from = document.getElementById('search_by_date_from').value;
        var date_to = document.getElementById('search_by_date_to').value;

        // AJAX request to load data
        $.ajax({
            type: "POST",
            url: "../../process/viewer/load_data.php",
            cache: false,
            data: {
                method: 'load_viewer_data',
                search_by_serial: search_by_serial,
                search_by_batch: search_by_batch,
                search_by_group: search_by_group,
                search_by_training: search_by_training,
                search_by_filename: search_by_filename,
                date_from: date_from,
                date_to: date_to,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('viewer_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('viewer_load_more').style.display = 'block';
                        } else {
                            document.getElementById('viewer_load_more').style.display = 'none';
                        }
                    } else {
                        document.getElementById('viewer_load_more').style.display = 'none';
                    }
                } else {
                    document.getElementById('viewer_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('viewer_load_more').style.display = 'block';
                    } else {
                        document.getElementById('viewer_load_more').style.display = 'none';
                    }
                }
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    document.getElementById('viewer_load_more').addEventListener('click', function() {
        load_data(true); // Trigger pagination
    });
</script>
