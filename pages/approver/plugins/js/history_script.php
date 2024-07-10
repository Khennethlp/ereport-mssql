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

        const approver_name = document.getElementById('approver_name').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const search_by = document.getElementById('search_by').value;

        $.ajax({
            type: "POST",
            url: '../../process/approver/load_history.php',
            cache: false,
            data: {
                method: 'approver_history_checker_table',
                search_by: search_by,
                date_from: date_from,
                date_to: date_to,
                approver_name: approver_name,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('approver_history_checker_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('approver_history_load_more').style.display = 'block';
                        } else {
                            document.getElementById('approver_history_load_more').style.display = 'none';
                        }
                    } else {
                        document.getElementById('approver_history_load_more').style.display = 'none';
                    }
                } else {
                    document.getElementById('approver_history_checker_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('approver_history_load_more').style.display = 'block';
                    } else {
                        document.getElementById('approver_history_load_more').style.display = 'none';
                    }
                }
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    // Attach event listener to the Load More button
    document.getElementById('approver_history_load_more').addEventListener('click', function() {
        load_data(true); // Pass true to indicate pagination
    });
</script>
