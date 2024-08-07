<script>
    document.addEventListener("DOMContentLoaded", function() {
        revisions();
      
    });

    const revisions = () => {
        var uploader_name = document.getElementById('uploader_name').value;
        var serialNo = document.getElementById('search_by_serialNo').value;
        var batchNo = document.getElementById('search_by_batchNo').value;
        var groupNo = document.getElementById('search_by_groupNo').value;
        var training_group = document.getElementById('training_group').value;
        var dateFrom = document.getElementById('search_by_date_from').value;
        var dateTo = document.getElementById('search_by_date_to').value;

        document.getElementById('card-container').style.display = 'block';

        $.ajax({
            type: "POST",
            url: "../../process/uploader/load_history.php",
            cache: false,
            data: {
                method: 'load_revision_data',
                uploader_name: uploader_name,
                serialNo: serialNo,
                batchNo: batchNo,
                groupNo: groupNo,
                training_group: training_group,
                dateFrom: dateFrom,
                dateTo: dateTo,
            },
            success: function(response) {
                document.getElementById('table').innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = '';
                $('#t_t1_breadcrumb').hide();
            }
        });
    }

    // const revisions_back = () => {
    //     var uploader_name = document.getElementById('uploader_name').value;
    //     var serialNo = document.getElementById('search_by_serialNo').value;
    //     var batchNo = document.getElementById('search_by_batchNo').value;
    //     var groupNo = document.getElementById('search_by_groupNo').value;
    //     var dateFrom = document.getElementById('search_by_date_from').value;
    //     var dateTo = document.getElementById('search_by_date_to').value;

    //     document.getElementById('card-container').style.display = 'block';

    //     $.ajax({
    //         type: "POST",
    //         url: "../../process/uploader/load_history.php",
    //         cache: false,
    //         data: {
    //             method: 'load_revision_data2',
    //             uploader_name: uploader_name,
    //             serialNo: serialNo,
    //             batchNo: batchNo,
    //             groupNo: groupNo,
    //             dateFrom: dateFrom,
    //             dateTo: dateTo,
    //         },
    //         success: function(response) {
    //             document.getElementById('table').innerHTML = response;
    //             document.getElementById("lbl_c1").innerHTML = '';
    //             $('#t_t1_breadcrumb').hide();
    //         }
    //     });
    // }

    const load_t2 = param => {
        var string = param.split('~!~');
        var serial_no = string[0];
        console.log(param);

        document.getElementById('card-container').style.display = 'none';

        var set_serial_no = sessionStorage.setItem('serial_no', serial_no);
        var get_serial_no = sessionStorage.getItem('serial_no', serial_no);

        $.ajax({
            url: "../../process/uploader/load_history.php",
            type: 'POST',
            cache: false,
            data: {
                method: 'load_t_t2',
                serial_no: get_serial_no,
            },
            success: function(response) {
                document.getElementById("table").innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = 'SN: '+ serial_no;
                $('#t_t1_breadcrumb').show();
            }
          
        });
    }

</script>