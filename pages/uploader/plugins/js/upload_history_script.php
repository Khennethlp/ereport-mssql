<script>
    // $(document).ready(function () {
    //     revisions();
    // });
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
            }
        });
    }
</script>