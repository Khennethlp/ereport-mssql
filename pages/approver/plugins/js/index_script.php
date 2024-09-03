<script>
    document.addEventListener('DOMContentLoaded', function() {
        // document.getElementById('a_load_more').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     isPagination = true;
        //     load_data();
        // });
        document.querySelectorAll('#approver_status, #search_by_serialNo, #search_by_batchNo, #search_by_groupNo, #search_by_filename').forEach(input => {
            input.addEventListener("keyup", e => {
                if (e.which === 13) {
                    load_data();
                }
            });
        });

        //load data by default
        load_data();
    });

    let page = 1;
    const rowsPerPage = 50;
    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
        }

        const status = document.getElementById('approver_status').value;
        const approver_id = document.getElementById('approver_id').value;
        var search_by_serialNo = $('#search_by_serialNo').val();
        var search_by_batchNo = $('#search_by_batchNo').val();
        var search_by_groupNo = $('#search_by_groupNo').val();
        var search_by_tgroup = $('#search_by_tgroup').val();
        var search_by_docs = $('#search_by_docs').val();
        var search_by_filename = $('#search_by_filename').val();
        var month = $('#search_by_month').val();
        var year = $('#search_by_year').val();

        var stats = sessionStorage.setItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/approver/load_data.php',
            cache: false,
            data: {
                method: 'approver_table',
                status: status,
                approver_id: approver_id,
                search_by_serialNo: search_by_serialNo,
                search_by_batchNo: search_by_batchNo,
                search_by_groupNo: search_by_groupNo,
                search_by_tgroup: search_by_tgroup,
                search_by_docs: search_by_docs,
                search_by_filename: search_by_filename,
                month: month,
                year: year,
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