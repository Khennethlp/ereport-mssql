<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('#approver_status, #search_by_serialNo, #search_by_batchNo, #search_by_groupNo, #search_by_filename').forEach(input => {
            input.addEventListener("keyup", e => {
                if (e.which === 13) {
                    load_data();
                }
            });
        });

        //load data by default
        load_data();

        // change to approver if selected training group is either MNTT or SEP
        // hide this code if MNTT and SEP have checkers
        document.getElementById('approver_container').style.display = 'none';
        const checkTrainingGroup = () => {
            const trainingGroup = document.getElementById('training_group').value;

            if (trainingGroup === 'MNTT' || trainingGroup === 'SEP') {
                document.getElementById('checker_container').style.display = 'none';
                document.getElementById('approver_container').style.display = 'block';
            } else {
                document.getElementById('checker_container').style.display = 'block';
                document.getElementById('approver_container').style.display = 'none';
            }
        };

        checkTrainingGroup();
        document.getElementById('training_group').addEventListener('change', checkTrainingGroup);
    });


    const refresh = () => {
        $('#search_date').val('');
        $('#status').val('');
        location.reload();
    }

    const clear_btn = () => {
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#fileName').text('Click or Drop file here.');
        $('#approval_by').val('');
        $('#batch_no').val('');
        $('#training_group').val('');
        $('#search_date').val('');
        $('#check_by').val('');
        // del();
    }

    const fetch_sub_doc = () => {
        const main_doc = document.getElementById('main_doc').value;

        $.ajax({
            type: "POST",
            url: '../../process/uploader/get_sub_doc.php',
            cache: false,
            data: {
                method: 'get_sub_doc',
                main_doc: main_doc
            },
            success: function(response) {
                const subDocSelect = document.getElementById('sub_doc').innerHTML = response;

                if (response.trim() == '') {
                    console.log('Not Empty');
                } else {
                    console.log('Empty');

                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching sub documents:', error);
            }
        });
    }

    const del = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var serialNo = data[1];
        var filename = data[2];
        console.log(param);

        $('#del_id').val(id);
        $('#del_serial').val(serialNo);
        $('#filename').html(filename);

    }

    const del_data = () => {
        var id = $('#del_id').val();
        var serial_no = $('#del_serial').val();

        $.ajax({
            type: "POST",
            url: '../../process/uploader/load_data.php',
            cache: false,
            data: {
                method: 'del_data_pending',
                id: id,
                serial_no: serial_no
            },
            success: function(response) {
                if (response.trim() == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Record have been deleted successfully.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    load_data();
                    $('#delete_pending').modal('hide');
                } else if (response.trim() == 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'There was an error deleting the record.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An unexpected error occurred.',
                    text: 'Please try again later.',
                    showConfirmButton: true
                });
            }
        });

    }

    let page = 1;
    const rowsPerPage = 50;

    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1;
        }

        var uploader_name = $('#uploader_name').val();
        var status = $('#status').val();
        var search_by_serialNo = $('#search_by_serialNo').val();
        var search_by_batchNo = $('#search_by_batchNo').val();
        var search_by_groupNo = $('#search_by_groupNo').val();
        var search_by_tgroup = $('#search_by_tgroup').val();
        var search_by_docs = $('#search_by_docs').val();
        var search_by_filename = $('#search_by_filename').val();
        var month = $('#search_by_month').val();
        var year = $('#search_by_year').val();

        $.ajax({
            type: "POST",
            url: '../../process/uploader/load_data.php',
            cache: false,
            data: {
                method: 'load_data',
                status: status,
                search_by_serialNo: search_by_serialNo,
                search_by_batchNo: search_by_batchNo,
                search_by_groupNo: search_by_groupNo,
                search_by_tgroup: search_by_tgroup,
                search_by_docs: search_by_docs,
                search_by_filename: search_by_filename,
                month: month,
                year: year,
                uploader_name: uploader_name,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('t_table').innerHTML += responseData.html; // Append new rows
                        page++; // Increment page for next fetch
                        if (responseData.has_more != false) {
                            document.getElementById('load_more').style.display = 'none'; 
                            
                        } else {
                            document.getElementById('load_more').style.display = 'block';
                        }
                    }
                } else {
                    document.getElementById('t_table').innerHTML = responseData.html; // Replace with new data
                    page++; // Increment page for the next fetch
                    if (responseData.has_more != false) {
                        document.getElementById('load_more').style.display = 'none';
                    } else {
                        document.getElementById('load_more').style.display = 'block';
                    }
                }
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    // Event listener for the Load More button
    document.getElementById('load_more').addEventListener('click', () => load_data(true));


    const clear_all = () => {
        // Clear form inputs & hide modal
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#training_group').val('');
        $('#batch_no').val('');
        $('#group_no').val('');
        $('#check_by').val('');
        $('#upload').modal('hide');
        $('#fileName').text('Click or Drop file here');
    }

    const upload = () => {

        var batch_no = document.getElementById('batch_no').value;
        var training_group = document.getElementById('training_group').value;
        var group_no = document.getElementById('group_no').value;
        var uploader_name = document.getElementById('uploader_name').value;
        var uploader_id = document.getElementById('uploader_id').value;
        var upload_by_month = document.getElementById('upload_by_month').value;
        var upload_by_year = document.getElementById('upload_by_year').value;

        // var checker_id = document.getElementById('check_by').value;
        if (training_group == 'MNTT' || training_group == 'SEP') {
            approver_id = document.getElementById('approved_by').value;
        } else {
            checker_id = document.getElementById('check_by').value;
        }

        var main_doc = document.getElementById('main_doc').value;
        var sub_doc = document.getElementById('sub_doc').value;
        var files = document.getElementById('files').files;

        var formData = new FormData();
        formData.append('method', 'uploading');
        formData.append('main_doc', main_doc);
        formData.append('sub_doc', sub_doc);
        formData.append('batch_no', batch_no);
        formData.append('training_group', training_group);
        formData.append('group_no', group_no);
        formData.append('uploader_id', uploader_id);
        formData.append('uploader_name', uploader_name);
        formData.append('upload_by_month', upload_by_month);
        formData.append('upload_by_year', upload_by_year);

        if (training_group == 'MNTT' || training_group == 'SEP') {
            formData.append('approver_id', approver_id);
        } else {
            formData.append('checker_id', checker_id);
        }
        // formData.append('checker_status', checker_status);

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        if (!main_doc || !training_group) {
            Swal.fire({
                icon: 'warning',
                title: 'Fields must not be empty!',
                showConfirmButton: false,
                timer: 1000
            });
        } else if (sub_doc !== '' && sub_doc == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Sub Document field is empty!',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                type: "POST",
                url: "../../process/uploader/uploads.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Recorded!',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        load_data();
                        clear_all();

                    } else if (response == 'exist') {
                        Swal.fire({
                            icon: 'info',
                            title: 'File already exists.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'dberror') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error saving to database.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'upload error') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sorry, there was an error uploading your file.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'invalid upload') {
                        Swal.fire({
                            icon: 'info',
                            title: 'Please upload a valid file.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'no upload') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No file was uploaded or there was an upload error.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (response == 'invalid request') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid request or upload error.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops. Something went wrong. Please try again',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                }
            });
        }

    };
</script>