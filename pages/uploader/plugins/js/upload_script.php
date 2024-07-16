<script>
    document.addEventListener('DOMContentLoaded', function() {

        initializeFileInput("#files", "#filesList > #files-names");
        load_data();
     
    });
    
    // document.addEventListener('keypress', ()=>{
    //     load_data();
    // });

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
                date_from: date_from,
                date_to: date_to,
                uploader_name: uploader_name,
                page: page,
                rows_per_page: rowsPerPage
            },
            beforeSend: function() {
                // Show spinner before AJAX request is sent
                $('#spinner').fadeIn();
            },
            success: function(response) {
                $('#spinner').fadeOut(function() {});
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
        // Clear form inputs or hide modal
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#training_group').val('');
        $('#batch_no').val('');
        $('#group_no').val('');
        $('#check_by').val('');
        $('#fileDropArea p').text('Click or Drop file here');
        $('#upload').modal('hide');
    }

    // document.getElementById('uploadBtn').addEventListener('click', function()
    const upload = () => {

        var batch_no = document.getElementById('batch_no').value;
        var training_group = document.getElementById('training_group').value;
        var group_no = document.getElementById('group_no').value;
        var uploader_name = document.getElementById('uploader_name').value;
        var uploader_id = document.getElementById('uploader_id').value;

        var selectElement = document.getElementById('check_by');
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var checker_id = selectedOption.getAttribute('data-emp-id');
        // var checker_name = selectedOption.textContent || selectedOption.innerText;
        var checker_email = selectedOption.value;
        // var checker_email = document.getElementById('check_by').value;
        var checker_status = 'Pending';

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
        formData.append('checker_id', checker_id);
        // formData.append('checker_name', checker_name);
        formData.append('checker_email', checker_email);
        formData.append('checker_status', checker_status);

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]); // Append each file to the FormData with the key 'files[]'
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
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Set content type to false for FormData
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

    const get_uploads_details = param => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        var status = data[2];
        $('#u_id_no').val(id);
        $('#u_serial_label').text(serial_no);
        $('#u_serial_no').val(serial_no);

        console.log(param);
        sessionStorage.setItem('status', status);
        sessionStorage.setItem('serial_no', serial_no);
        var status = sessionStorage.getItem('status'); 

        $.ajax({
            type: "POST",
            url: '../../process/uploader/load_data.php',
            cache: false,
            data: {
                method: 'uploads_modal_table',
                id: id,
                serial_no: serial_no,
                status: status
            },
            success: function(response) {
                document.getElementById('uploads_modal_table').innerHTML = response;
            },
            error: function() {
                console.log("Error loading data");
            }
        });
    }

    //get details for selected disapproved file
    const get_disapprovedDetails = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        var comment = data[2];

        $('#updateFile_id').val(id);
        $('#updateFile_serialNo').val(serial_no);
        $('#disapproved_comment').val(comment);
        console.log(param);
    }

    const updateUpload = () => {
        var updateFile_id = document.getElementById('updateFile_id').value;
        var updateFile_serialNo = document.getElementById('updateFile_serialNo').value;
        var update_files = document.getElementById('file_update').files[0];
        var updated_status = 'Pending';

        var formData = new FormData();
        formData.append("method", "update_file_upload");
        formData.append("updateFile_serialNo", updateFile_serialNo);
        formData.append("updateFile_id", updateFile_id);
        formData.append("update_files", update_files);
        formData.append("updated_status", updated_status);

        $.ajax({
            type: 'POST',
            url: "../../process/uploader/file_update.php", //updating disapproved file
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    load_data();
                } else if (response == 'error') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Failed to update file.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (response == 'file error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'File not found.',
                        showConfirmButton: false,
                        timer: 1000
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Something went wrong.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    }
</script>