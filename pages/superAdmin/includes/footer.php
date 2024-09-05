
<footer class="main-footer text-sm">
    Developed by: <em>Khennethlp</em>
    <div class="float-right d-none d-sm-inline-block">
        <strong>Copyright &copy;
            <script>
                var currentYear = new Date().getFullYear();
                if (currentYear !== 2024) {
                    document.write("2024 - " + currentYear);
                } else {
                    document.write(currentYear);
                };
            </script>.
        </strong>
        All rights reserved.
    </div>
</footer>

<?php
include '../../modals/remove.php';
include '../../modals/update_admin.php';
?>

<script src="../../plugins/jquery/dist/jquery.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../dist/js/adminlte.js"></script>
<script src="../../dist/js/popup_center.js"></script>
</body>

</html>