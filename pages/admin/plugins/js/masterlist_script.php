<script>
    document.addEventListener("DOMContentLoaded", function() {
        load_docs();
        load_trainings();
    });

    const load_docs = () => {
        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'load_docs',

            },
            success: function(response) {
                document.getElementById('m_report_table').innerHTML = response;
            }
        });
    }

    const add_reports = () => {
        var main_doc = document.getElementById('main_doc').value;
        var sub_doc = document.getElementById('sub_doc').value;

        if (main_doc == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Main Document must not be empty.',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }
        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'add_new_docs',
                main_doc: main_doc,
                sub_doc: sub_doc,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Document added to the list.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#add_docs').modal('hide');
                    load_docs();
                }
            }
        });
    }

    const get_docs = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var main_doc = data[1];
        var sub_doc = data[2];

        $('#update_doc_id').val(id);
        $('#update_main_doc').val(main_doc);
        $('#update_sub_doc').val(sub_doc);

        console.log(param);
    }

    const update_docs = () => {
        var id = document.getElementById('update_doc_id').value;
        var main_doc = document.getElementById('update_main_doc').value;
        var sub_doc = document.getElementById('update_sub_doc').value;

        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'update_docs',
                id: id,
                main_doc: main_doc,
                sub_doc: sub_doc,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#update_docs').modal('hide');
                    load_docs();
                }
            }
        });
    }

    const delete_docs = () => {
        var id = document.getElementById('update_doc_id').value;

        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'del_docs',
                id: id,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Deleted Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#update_docs').modal('hide');
                    load_docs();
                }
            }
        });
    }

    const load_trainings = () => {
        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'load_trainings',
            },
            success: function(response) {
                document.getElementById('m_training_table').innerHTML = response;
            }
        });
    }

    const add_trainings = () => {
        var training_title = document.getElementById('t_title').value;

        if (training_title == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Training field must not be empty.',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }
        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'add_new_training',
                training_title: training_title,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Document added to the list.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#add_training').modal('hide');
                    load_trainings();
                }
            }
        });
    }

    const get_train = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var training_title = data[1];

        $('#update_train_id').val(id);
        $('#update_t_title').val(training_title);

        console.log(param);
    }

    const update_trainings = () => {
        var id = document.getElementById('update_train_id').value;
        var t_title = document.getElementById('update_t_title').value;

        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'update_training',
                id: id,
                t_title: t_title,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#update_training').modal('hide');
                    load_trainings();
                }
            }
        });
    }

    const delete_trainings = () => {
        var id = document.getElementById('update_train_id').value;

        $.ajax({
            type: "POST",
            url: '../../process/admin/load_data.php',
            cache: false,
            data: {
                method: 'del_training',
                id: id,
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Deleted Successfully!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#update_training').modal('hide');
                    load_trainings();
                }
            }
        });
    }
</script>