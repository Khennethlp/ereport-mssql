<?php
include '../../process/conn.php';

$response = ''; // Initialize an empty response variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['method']) && $_POST['method'] == 'update_file_upload') {
    $id = $_POST['update_id'];
    $serial_no = $_POST['update_serialNo'];
    $status = $_POST['updated_status'];
    $approver_status = '';
    $file = $_FILES['file_attached']['name'];
    $tmp_name = $_FILES['file_attached']['tmp_name'];

    // Define the base directory for uploads
    $uploadDir = __DIR__ . '/../../../uploads/ereport/' . $serial_no . '/';

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    try {
        // Fetch the current file name from the database
        $sql_fetch_file = "SELECT * FROM t_upload_file WHERE serial_no = :serial_no AND id = :id";
        $stmt_fetch_file = $conn->prepare($sql_fetch_file);
        $stmt_fetch_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
        $stmt_fetch_file->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_fetch_file->execute();
        $file_info = $stmt_fetch_file->fetch(PDO::FETCH_ASSOC);
        
        if ($file_info) {
            $main_doc = $file_info['main_doc'];
            $sub_doc = $file_info['sub_doc'];

            if ($sub_doc) {
                $updatedFile = $uploadDir . $main_doc . '/' . $sub_doc . '/';
            } else {
                $updatedFile = $uploadDir . $main_doc . '/';
            }

            if (!is_dir($updatedFile)) {
                mkdir($updatedFile, 0777, true);
            }

            $new_file_path = $updatedFile . $file;

            if (move_uploaded_file($tmp_name, $new_file_path)) {
                // Update t_upload_file
                $sql_upload_file = "UPDATE t_upload_file SET file_name = :file WHERE serial_no = :serial_no AND id = :id";
                $stmt_upload_file = $conn->prepare($sql_upload_file);
                $stmt_upload_file->bindParam(':file', $file, PDO::PARAM_STR);
                $stmt_upload_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                $stmt_upload_file->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_upload_file->execute();

                // Update t_training_record
                $sql_training_record = "UPDATE t_training_record SET update_upload_date = NOW(), checker_status = :status, checker_name = '', checked_date = '', checker_comment = '', approver_id = '', approver_name = '', approver_email = '', approved_date = '', approver_status = :approver_status, approver_comment = '' WHERE serial_no = :serial_no AND id = :id";
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
            echo 'File information not found in the database.';
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage(); // Output error message for debugging
    }
} else {
    echo 'Invalid request method or missing parameters.';
}
?>
