<script>
    document.addEventListener('DOMContentLoaded', function() {
        load_accounts(); 
    });

    const load_accounts = () => {
        var search = document.getElementById('search_account').value;
        $.ajax({
            type: "POST",
            url: "../../process/admin/accounts_p.php",
            cache: false,
            data: {
                method: 'load_accounts',
                search:search
            },
            success: function(response) {
                document.getElementById('accounts_table').innerHTML = response;
            }
        });
    }

    const add_account = () => {
        var emp_id = document.getElementById('add_emp_id').value;
        var fullname = document.getElementById('add_fullname').value;
        // var email = document.getElementById('add_email').value;
        var username = document.getElementById('add_username').value;
        var password = document.getElementById('add_password').value;
        var role = document.getElementById('add_role').value;

        if (!emp_id || !fullname || !username || !password || !role) {
            Swal.fire({
                icon: 'warning',
                title: 'Fields must not be empty!',
                // text: 'Success',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                type: "POST",
                url: "../../process/admin/accounts_p.php",
                cache: false,
                data: {
                    method: 'add_accounts',
                    emp_id: emp_id,
                    fullname: fullname,
                    // email: email,
                    username: username,
                    password: password,
                    role: role,
                },
                success: function(response) {
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Recorded!',
                            // text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#add_acc').modal('hide');
                        $('#add_emp_id').val('');
                        $('#add_fullname').val('');
                        $('#add_email').val('');
                        $('#add_username').val('');
                        $('#add_password').val('');
                        $('#add_role').val('');
                        load_accounts();
                    } else if (response == 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to add new user. Please try again',
                            // text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#add_acc').modal('hide');
                        $('#add_emp_id').val('');
                        $('#add_fullname').val('');
                        $('#add_email').val('');
                        $('#add_username').val('');
                        $('#add_password').val('');
                        $('#add_role').val('');
                        load_accounts();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops. Something went wrong. Please try again',
                            // text: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                }
            });
        }
    }

    const get_accounts_details = (param) => {
        var data = param.split('~!~');
        var id = data[0];
        var emp_id = data[1];
        var username = data[2];
        var fullname = data[3];
        // var email = data[4];
        var password = data[5];
        var role = data[6];

        $('#id_acc').val(id);
        $('#empId_edit').val(emp_id);
        $('#username_edit').val(username);
        $('#fullname_edit').val(fullname);
        // $('#email_edit').val(email);
        $('#password_edit').val(password);
        $('#role_edit').val(role);

        console.log(param);
    }

    const update_account = () => {
        var id = document.getElementById('id_acc').value;
        var emp_id = document.getElementById('empId_edit').value;
        var username = document.getElementById('username_edit').value;
        var fullname = document.getElementById('fullname_edit').value;
        // var email = document.getElementById('email_edit').value;
        var password = document.getElementById('password_edit').value;
        var role = document.getElementById('role_edit').value;

        if (!emp_id || !username || !fullname || !password || !role) {
            Swal.fire({
                icon: 'warning',
                title: 'Fields must not be empty.',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            $.ajax({
                type: "POST",
                url: "../../process/admin/accounts_p.php",
                cache: false,
                data: {
                    method: 'edit_account',
                    id: id,
                    emp_id: emp_id,
                    username: username,
                    fullname: fullname,
                    // email: email,
                    password: password,
                    role: role,
                },
                success: function(response) {
                    console.log(response);
                    if (response == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated successfully.',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        $('#update_account').modal('hide');
                        load_accounts();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error! Something went wrong.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                }

            });
        }
    }

    const delete_account = () => {
        var id = document.getElementById('id_acc').value;

        $.ajax({
            url: "../../process/admin/accounts_p.php",
            type: 'POST',
            cache: false,
            data: {
                method: 'del_account',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted successfully.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#update_account').modal('hide');
                    load_accounts();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors if any
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to delete account.',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }
</script>