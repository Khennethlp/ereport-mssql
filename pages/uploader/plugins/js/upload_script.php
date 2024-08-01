<script>
    document.addEventListener('DOMContentLoaded', function() {

        initializeFileInput("#files", "#filesList > #files-names");
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

    //File Selection and Displaying
    const dt = new DataTransfer();

    function initializeFileInput(selector, fileListSelector) {
        $(selector).on('change', function(e) {
            handleFileSelection(this, dt, fileListSelector);
        });
    }

    function handleFileSelection(inputElement, dt, fileListSelector) {
        for (var i = 0; i < inputElement.files.length; i++) {
            let fileBloc = $('<span/>', {
                    class: 'file-block'
                }),
                fileName = $('<span/>', {
                    class: 'name',
                    text: inputElement.files.item(i).name
                });
            fileBloc.append('<span class="file-delete" title="remove"><span><i class="fas fa-times"></i></span></span>')
                .append(fileName);
            $(fileListSelector).append(fileBloc);
        };

        for (let file of inputElement.files) {
            dt.items.add(file);
        }

        inputElement.files = dt.files;
        attachDeleteEvent(fileListSelector, dt);
    }

    function attachDeleteEvent(fileListSelector, dt) {
        $('span.file-delete').off('click').on('click', function() {
            let name = $(this).next('span.name').text();

            $(this).parent().remove();
            for (let i = 0; i < dt.items.length; i++) {
                if (name === dt.items[i].getAsFile().name) {
                    dt.items.remove(i);
                    break;
                }
            }
            document.getElementById('files').files = dt.files;
        });
    }

    function deleteAllFiles(dt, fileListSelector) {
        $(fileListSelector).empty(); // Clear the file list from the DOM
        dt.items.clear(); // Clear the DataTransfer object
        document.getElementById('files').files = dt.files; // Update the file input
    }

    const del = () => {
        deleteAllFiles(dt, "#filesList > #files-names");

    }

    const refresh = () => {
        $('#search_date').val('');
        $('#status').val('');
        location.reload();
    }

    const clear_btn = () => {
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#fileDropArea p').text('Click or Drop file here');
        $('#approval_by').val('');
        $('#batch_no').val('');
        $('#training_group').val('');
        $('#search_date').val('');
        $('#check_by').val('');
        del();
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

    let page = 1; // Initial page number
    const rowsPerPage = 10; // Number of rows to fetch per request

    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        var status = $('#status').val();
        var search = $('#search').val();
        var search_by_filename = $('#search_by_filename').val();
        var date_from = $('#search_date_from').val();
        var date_to = $('#search_date_to').val();
        var uploader_name = $('#uploader_name').val();

        // sessionStorage.setItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/uploader/load_data.php',
            cache: false,
            data: {
                method: 'load_data',
                status: status,
                search: search,
                search_by_filename: search_by_filename,
                date_from: date_from,
                date_to: date_to,
                uploader_name: uploader_name,
                page: page,
                rows_per_page: rowsPerPage
            },
            success: function(response) {
                const responseData = JSON.parse(response);
                if (isPagination) {
                    if (responseData.html.trim() !== '') {
                        document.getElementById('t_table').innerHTML += responseData.html;
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
                    document.getElementById('t_table').innerHTML = responseData.html;
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

    const clear_all = () => {
        // Clear form inputs & hide modal
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#training_group').val('');
        $('#batch_no').val('');
        $('#group_no').val('');
        $('#check_by').val('');
        $('#upload').modal('hide');
        $('#fileDropArea p').text('Click or Drop file here');
    }

    const upload = () => {

        var batch_no = document.getElementById('batch_no').value;
        var training_group = document.getElementById('training_group').value;
        var group_no = document.getElementById('group_no').value;
        var uploader_name = document.getElementById('uploader_name').value;
        var uploader_id = document.getElementById('uploader_id').value;

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

        if (training_group == 'MNTT' || training_group == 'SEP') {
            formData.append('approver_id', approver_id);
        } else {
            formData.append('checker_id', checker_id);
        }
        // formData.append('checker_status', checker_status);

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        if (!main_doc || !batch_no || !training_group) {
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
                        del();

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