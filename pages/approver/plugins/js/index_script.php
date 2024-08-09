<script>
    document.addEventListener('DOMContentLoaded', function() {
        // document.getElementById('a_load_more').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     isPagination = true;
        //     load_data();
        // });
        load_data();
    });

    let page = 1; 
    const rowsPerPage = 50;
    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
        }

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
                page: page,
                rows_per_page: rowsPerPage

            },
            success: function(response) {
                // document.getElementById('approver_table').innerHTML = response;
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('approver_table').innerHTML += responseData.html;
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
                    document.getElementById('approver_table').innerHTML = responseData.html;
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