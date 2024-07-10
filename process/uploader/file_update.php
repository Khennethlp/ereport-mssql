<?php
include '../../process/conn.php';

$response = ''; // Initialize an empty response variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['method']) && $_POST['method'] == 'update_file_upload') {
    $id = $_POST['updateFile_id'];
    $serial_no = $_POST['updateFile_serialNo'];
    $status = $_POST['updated_status'];
    $approver_status = '';

    $file = $_FILES['update_files']['name'];
    $tmp_name = $_FILES['update_files']['tmp_name'];

    // Define the base directory for uploads
    $uploadDir = __DIR__ . '/../../../uploads/ereport/' . $serial_no . '/';

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    try {
        // Fetch the current file name from the database
        $sql_fetch_file = "SELECT file_name, main_doc, sub_doc FROM t_upload_file WHERE serial_no = :serial_no AND id = :id";
        $stmt_fetch_file = $conn->prepare($sql_fetch_file);
        $stmt_fetch_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
        $stmt_fetch_file->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_fetch_file->execute();
        $file_info = $stmt_fetch_file->fetch(PDO::FETCH_ASSOC);

        if ($file_info) {
            $current_file = $file_info['file_name'];
            $main_doc = $file_info['main_doc'];
            $sub_doc = $file_info['sub_doc'];

            // Define the path to the current file
            $current_file_path = $uploadDir . $main_doc . '/' . ($sub_doc ? $sub_doc . '/' : '') . $current_file;

            // Check if the current file exists and delete it
            if (file_exists($current_file_path)) {
                unlink($current_file_path);
            }

            // Define the path to the new file
            $new_file_path = $uploadDir . $main_doc . '/' . ($sub_doc ? $sub_doc . '/' : '') . $file;

            // Ensure the directories exist
            if (!is_dir($uploadDir . $main_doc)) {
                mkdir($uploadDir . $main_doc, 0777, true);
            }
            if ($sub_doc && !is_dir($uploadDir . $main_doc . '/' . $sub_doc)) {
                mkdir($uploadDir . $main_doc . '/' . $sub_doc, 0777, true);
            }

            // Move the new file to the correct location
            if (move_uploaded_file($tmp_name, $new_file_path)) {
                // Update t_upload_file
                $sql_upload_file = "UPDATE t_upload_file SET file_name = :file WHERE serial_no = :serial_no AND id = :id";
                $stmt_upload_file = $conn->prepare($sql_upload_file);
                $stmt_upload_file->bindParam(':file', $file, PDO::PARAM_STR);
                $stmt_upload_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                $stmt_upload_file->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_upload_file->execute();

                // Update t_training_record
                $sql_training_record = "UPDATE t_training_record SET checker_status = :status, approver_status = :approver_status WHERE serial_no = :serial_no AND id = :id";
                $stmt_training_record = $conn->prepare($sql_training_record);
                $stmt_training_record->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt_training_record->bindParam(':approver_status', $approver_status, PDO::PARAM_STR);
                $stmt_training_record->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                $stmt_training_record->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_training_record->execute();

                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            echo 'file error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage(); // Output error message for debugging
    }
}
?>
