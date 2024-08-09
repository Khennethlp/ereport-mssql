<script>
    document.addEventListener('DOMContentLoaded', function() {

        // document.getElementById('load_more').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     isPagination = true;
        //     load_data();
        // });

        load_data();
        // checker();
    });

    // --------------------------------------------------------------------------

    let page = 1;
    const rowsPerPage = 50;

    const load_data = (isPagination = false) => {
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
                // document.getElementById('checker_table').innerHTML = response;
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('checker_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('load_more').style.display = 'block';
                        } else {
                            document.getElementById('load_more').style.display = 'none';
                        }
                    } else {
                        document.getElementById('load_more').style.display = 'none';
                    }
                } else {
                    document.getElementById('checker_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('load_more').style.display = 'block';
                    } else {
                        document.getElementById('load_more').style.display = 'none';
                    }
                }
        
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }
    document.getElementById('load_more').addEventListener('click', () => load_data(true));
</script>