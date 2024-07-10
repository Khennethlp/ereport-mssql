<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_data();
    });

    let page = 1; // Initial page number
    const rowsPerPage = 20; // Number of rows to fetch per request

    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        const checker_name = document.getElementById('checker_name').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const search_by = document.getElementById('search_by').value;

        $.ajax({
            type: "POST",
            url: '../../process/checker/load_history.php',
            cache: false,
            data: {
                method: 'history_checker_table',
                search_by: search_by,
                date_from: date_from,
                date_to: date_to,
                checker_name: checker_name,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('history_checker_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('history_load_more').style.display = 'block';
                        } else {
                            document.getElementById('history_load_more').style.display = 'none';
                        }
                    } else {
                        document.getElementById('history_load_more').style.display = 'none';
                    }
                } else {
                    document.getElementById('history_checker_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('history_load_more').style.display = 'block';
                    } else {
                        document.getElementById('history_load_more').style.display = 'none';
                    }
                }
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    document.getElementById('history_load_more').addEventListener('click', () => load_data(true));
</script>