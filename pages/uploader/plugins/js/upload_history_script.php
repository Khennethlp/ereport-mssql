<script>
    document.addEventListener("DOMContentLoaded", function() {
        revisions();
        document.getElementById('card-container-t2').style.display = 'none';
    });

    const revisions = () => {
        var uploader_name = document.getElementById('uploader_name').value;
        var serialNo = document.getElementById('search_by_serialNo').value;
        var batchNo = document.getElementById('search_by_batchNo').value;
        var groupNo = document.getElementById('search_by_groupNo').value;
        var dateFrom = document.getElementById('search_by_date_from').value;
        var dateTo = document.getElementById('search_by_date_to').value;

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
                dateFrom: dateFrom,
                dateTo: dateTo,
            },
           
            success: function(response) {
                document.getElementById('upload_history_table').innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = '';
                $('#t_t1_breadcrumb').hide();
            }
        });
    }

    const revisions_back = () => {
        var uploader_name = document.getElementById('uploader_name').value;
        var serialNo = document.getElementById('search_by_serialNo').value;
        var batchNo = document.getElementById('search_by_batchNo').value;
        var groupNo = document.getElementById('search_by_groupNo').value;
        var dateFrom = document.getElementById('search_by_date_from').value;
        var dateTo = document.getElementById('search_by_date_to').value;

        document.getElementById('card-container').style.display = 'block';
        document.getElementById('card-container-t2').style.display = 'none';

        $.ajax({
            type: "POST",
            url: "../../process/uploader/load_history.php",
            cache: false,
            data: {
                method: 'load_revision_data2',
                uploader_name: uploader_name,
                serialNo: serialNo,
                batchNo: batchNo,
                groupNo: groupNo,
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

    const load_t2 = param => {
        var string = param.split('~!~');
        var serial_no = string[0];
        console.log(param);

        document.getElementById('card-container').style.display = 'none';
        document.getElementById('card-container-t2').style.display = 'none';

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
                document.getElementById("lbl_c1").innerHTML = serial_no;
                $('#t_t1_breadcrumb').show();
            }
          
        });
    }

</script>