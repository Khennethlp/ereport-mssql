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

    const update_data_admin = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        var batch_no = data[2];
        var group_no = data[3];
        var month = data[4];
        var year = data[5];
        var training_group = data[6];
        var filename = data[7];
        var checked_by = data[8];
        var checked_date = data[9];
        var approved_by = data[10];
        var approved_date = data[11];
        var main_doc = data[12];

        $('#update_id').val(id);
        $('#update_serial_no').val(serial_no);
        $('#update_batch').val(batch_no);
        $('#update_group').val(group_no);
        $('#update_month').val(month);
        $('#update_year').val(year);
        $('#update_tgroup').val(training_group);
        $('#update_filename').val(filename);
        $('#update_doc').val(main_doc);
        $('#update_checkedBy').val(checked_by);
        $('#update_checkedDate').val(checked_date);
        $('#update_approvedBy').val(approved_by);
        $('#update_approvedDate').val(approved_date);
        console.log(param);
    }

    const update_admin = () => {
        var id = document.getElementById('update_id').value;
        var serialNo = document.getElementById('update_serial_no').value;
        var batchNo = document.getElementById('update_batch').value;
        var groupNo = document.getElementById('update_group').value;
        var month = document.getElementById('update_month').value;
        var year = document.getElementById('update_year').value;
        var trainingGroup = document.getElementById('update_tgroup').value;
        var mainDoc = document.getElementById('update_doc').value;
        var filename = document.getElementById('update_filename').value;

        $.ajax({
            type: "POST",
            url: "../../process/admin/load_data.php",
            data: {
                method: 'update_admin',
                id: id,
                serialNo: serialNo,
                batchNo: batchNo,
                groupNo: groupNo,
                month: month,
                year: year,
                trainingGroup: trainingGroup,
                mainDoc: mainDoc,
                filename: filename,

            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    load_data();
                    $('#update_admin').modal('hide');
                }else if (response == 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to update.',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    load_data();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }
</script>