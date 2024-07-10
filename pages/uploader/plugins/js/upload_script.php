<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileDropArea = $('#fileDropArea');
        var fileInput = $('#files');
        var selectedFiles = [];

        fileDropArea.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileDropArea.addClass('dragover');
        });

        fileDropArea.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileDropArea.removeClass('dragover');
        });

        fileDropArea.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            fileDropArea.removeClass('dragover');
            var files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
            updateLabel();
        });

        fileInput.on('change', function() {
            var files = fileInput[0].files;
            handleFiles(files);
            updateLabel();
        });

        function handleFiles(files) {
            for (var i = 0; i < files.length; i++) {
                selectedFiles.push(files[i]);
            }
            updateFileInput();
        }

        function updateFileInput() {
            var dataTransfer = new DataTransfer();
            for (var i = 0; i < selectedFiles.length; i++) {
                dataTransfer.items.add(selectedFiles[i]);
            }
            fileInput[0].files = dataTransfer.files;
        }

        function updateLabel() {
            var fileNames = [];
            for (var i = 0; i < selectedFiles.length; i++) {
                fileNames.push(selectedFiles[i].name);
            }
            fileDropArea.find('p').text(fileNames.length > 0 ? fileNames.join(', ') : 'Click or Drop file here');
        }



        document.getElementById('sub_doc_container').style.display = 'none';
        load_data();
    });

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
        $('#sub_doc_container').css('display', 'none');
        updateLabel();
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
                const subDocContainer = document.getElementById('sub_doc_container');
                const subDocSelect = document.getElementById('sub_doc');

                if (response.trim() === '<option disabled selected value="">--Select Sub Document--</option>') {
                    subDocContainer.style.display = 'none';
                } else {
                    subDocSelect.innerHTML = response;
                    subDocContainer.style.display = 'block';
                }
            }
        });
    }

    let page = 1; // Initial page number
    const rowsPerPage = 10; // Number of rows to fetch per request

    const load_data = (isPagination = false) => {
        if (!isPagination) {
            page = 1; // Reset page number for initial load
        }

        var date = $('#search_date').val();
        var status = $('#status').val();
        var uploader_name = $('#uploader_name').val();

        // sessionStorage.setItem('status', status);
        $.ajax({
            type: "POST",
            url: '../../process/uploader/load_data.php',
            cache: false,
            data: {
                method: 'load_data',
                date: date,
                status: status,
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
        // Clear form inputs or hide modal
        $('#main_doc').val('');
        $('#sub_doc').val('');
        $('#training_group').val('');
        $('#batch_no').val('');
        $('#group_no').val('');
        $('#checker_email').val('');
        $('#sub_doc_container').css('display', 'none');
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
        var checker_name = selectedOption.textContent || selectedOption.innerText;
        var checker_email = selectedOption.value;
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
        formData.append('checker_name', checker_name);
        formData.append('checker_email', checker_email);
        formData.append('checker_status', checker_status);

        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]); // Append each file to the FormData with the key 'files[]'
        }

        if (!main_doc || !batch_no || !training_group || !checker_email) {
            Swal.fire({
                icon: 'warning',
                title: 'Fields must not be empty!',
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

                        load_data(); // Example function to load data after successful upload
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

    const get_uploads_details = param => {
        var data = param.split('~!~');
        var id = data[0];
        var serial_no = data[1];
        var status = data[2];
        $('#u_id_no').val(id);
        $('#u_serial_label').text(serial_no);
        $('#u_serial_no').val(serial_no);

        console.log(param);
        sessionStorage.setItem('serial_no', serial_no);
        // var status = sessionStorage.getItem('status'); // Correctly retrieve the status

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
                }else if(response == 'error'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot update file.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }else{
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