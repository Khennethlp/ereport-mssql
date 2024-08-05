<script>
    document.addEventListener("DOMContentLoaded", function(){
        revisions();
    });

    const revisions = () => {
        $.ajax({
            type: "POST",
            url: "../../process/uploader/load_history.php",
            cache: false,
            data: {
                method: 'load_revision_data',
            },
            success: function (response) {
                document.getElementById('upload_history_table').innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = '';
                $('#t_t1_breadcrumb').hide();
            }
        });
    }
    const revisions2 = () => {
        $.ajax({
            type: "POST",
            url: "../../process/uploader/load_history.php",
            cache: false,
            data: {
                method: 'load_revision_data2',
            },
            success: function (response) {
                document.getElementById('table').innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = '';
                $('#t_t1_breadcrumb').hide();
            }
        });
    }

    const load_t2 = param => {
        var string = param.split('~!~');
        var serial_no = string[0];
        // var serial_no = string[1];
        console.log(param);

        // var set_id = sessionStorage.setItem('id', id);
        var set_serial_no = sessionStorage.setItem('serial_no', serial_no);
        var get_serial_no = sessionStorage.getItem('serial_no', serial_no);

        $.ajax({
            url: "../../process/uploader/load_history.php",
            type: 'POST',
            cache: false,
            data: {
                method: 'load_t_t2',
                serial_no:get_serial_no,
            },
            success: function(response) {
                document.getElementById("table").innerHTML = response;
                document.getElementById("lbl_c1").innerHTML = serial_no;
                $('#t_t1_breadcrumb').show();
            }
        });
    }
</script>