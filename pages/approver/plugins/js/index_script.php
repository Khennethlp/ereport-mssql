<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('a_load_more').addEventListener('click', function(e) {
        e.preventDefault();
        isPagination = true;
        load_data();
    });
    });


    let page = 1;
    const rowsPerPage = 10;
    let isPagination = false;

    const load_data = () => {
        if (!isPagination) {
            page = 1;
        }

        const status = document.getElementById('status').value;
        const date_from = document.getElementById('search_by_date_from').value;
        const date_to = document.getElementById('search_by_date_to').value;
        const checker_name = document.getElementById('checker_name').value;
        const search_by = document.getElementById('search_by').value;

        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_table',
                status: status,
                date_from: date_from,
                date_to: date_to,
                checker_name: checker_name,
                search_by: search_by,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('approver_table').innerHTML += responseData.html;
                        page++;
                        if (responseData.has_more) {
                            document.getElementById('a_load_more').style.display = 'block';
                        } else {
                            document.getElementById('a_load_more').style.display = 'none';
                        }
                    } else {
                        document.getElementById('a_load_more').style.display = 'none';
                    }
                } else {
                    document.getElementById('approver_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('a_load_more').style.display = 'block';
                    } else {
                        document.getElementById('a_load_more').style.display = 'none';
                    }
                }
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

</script>