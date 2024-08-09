<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_data();
        counts();
    });

    let page = 1; 
    const rowsPerPage = 50;
    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        var serialNo = $('#search_by_serialNo').val();
        var batchNo = $('#search_by_batchNo').val();
        var groupNo = $('#search_by_groupNo').val();
        var trainingGroup = $('#search_by_tgroup').val();
        var fileName = $('#search_by_filename').val();
        var docs = $('#search_by_docs').val();
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
                docs: docs,
                month: month,
                year: year,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                // document.getElementById('admin_dashboard_table').innerHTML = response;
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('admin_dashboard_table').innerHTML += responseData.html;
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
                    document.getElementById('admin_dashboard_table').innerHTML = responseData.html;
                    page++;
                    if (responseData.has_more) {
                        document.getElementById('load_more').style.display = 'block';
                    } else {
                        document.getElementById('load_more').style.display = 'none';
                    }
                }
            }
        });
    }
    document.getElementById('load_more').addEventListener('click', () => load_data(true));

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