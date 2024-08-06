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
        };</script>. 
        </strong>
        All rights reserved.
        <!-- <a href="#" data-target="#problem" data-toggle="modal">Report a problem</a> -->
    </div>
  </footer>
<?php
//MODALS
include '../../modals/logout_modal.php';
include '../../modals/upload_modal.php';
include '../../modals/upload_data.php';
include '../../modals/update_uploads.php';
include '../../modals/problem_modal.php';
?>

<script>
  document.getElementById('files').addEventListener('change', function(event) {
    const fileInput = event.target;
    const fileNameSpan = document.getElementById('fileName');

    if (fileInput.files.length > 0) {
        fileNameSpan.textContent = fileInput.files[0].name;
    } else {
        fileNameSpan.textContent = 'Click or drop file here.';
    }
});
</script>
<!-- <script src="plugins/js/custom.js"></script> -->
<!-- jQuery -->
<script src="../../plugins/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- SweetAlert2 -->
<script type="text/javascript" src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/popup_center.js"></script>
<script src="plugins/js/handle_files.js"></script>

</body>
</html>