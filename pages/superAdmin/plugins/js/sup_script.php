<script>
  document.addEventListener("DOMContentLoaded", function() {
    load_data();
    counts();
  })

  const load_data = () => {
    var sortBy = document.getElementById('sortBy').value;
    var serialNo = document.getElementById('search_by_serialNo').value;

    $.ajax({
      type: "POST",
      url: "../../process/superAdmin/load_data.php",
      data: {
        method: 'getData_masterlist',
        sortBy: sortBy,
        serialNo: serialNo
      },
      success: function(response) {
        document.getElementById('load_table').innerHTML = response;
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
    // $('#update_group').val(group_no);
    $('.update_group').val(group_no); //classname
    $('#update_month').val(month);
    // $('#update_year').val(year);
    $('.update_year').val(year); //classname
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
    // var groupNo = document.getElementById('update_group').value;
    var groupNo = document.getElementsByClassName('update_group')[0].value; //classname
    var month = document.getElementById('update_month').value;
    // var year = document.getElementById('update_year').value;
    var year = document.getElementsByClassName('update_year')[0].value; //classname
    var trainingGroup = document.getElementById('update_tgroup').value;
    var mainDoc = document.getElementById('update_doc').value;
    var filename = document.getElementById('update_filename').value;

    $.ajax({
      type: "POST",
      url: "../../process/superAdmin/load_data.php",
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
        } else if (response == 'error') {
          Swal.fire({
            icon: 'error',
            title: 'Failed to update.',
            showConfirmButton: false,
            timer: 1000
          });

          load_data();
        } else {
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

  const del_data = (param) => {
    var data = param.split('~!~');
    var id = data[0];
    var serial_no = data[1];
    console.log(param);
  }

  const remove_data = () => {
    var id = document.getElementById('update_id').value;
    var serialNo = document.getElementById('update_serial_no').value;

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "../../process/superAdmin/load_data.php",
          data: {
            method: 'remove_data',
            id: id,
            serialNo: serialNo,
          },
          success: function(response) {
            if (response == 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Deleted successfully!',
                showConfirmButton: false,
                timer: 1000
              });

              load_data();
              $('#update_admin').modal('hide');
            } else if (response == 'error') {
              Swal.fire({
                icon: 'error',
                title: 'Failed to delete.',
                showConfirmButton: false,
                timer: 1000
              });

              load_data();
            } else {
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
    });
  }

  const counts = () => {
        $.ajax({
            type: "POST",
            url: "../../process/superAdmin/load_data.php",
            data: {
                method: 'counts',
            },
            success: function(response) {
                $('#total_count').html(response);

            }
        });
    }
</script>