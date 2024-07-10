<?php
include '../../process/conn.php';

$response = ''; // Initialize an empty response variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['method']) && $_POST['method'] == 'update_file_upload') {
    $id = $_POST['updateFile_id'];
    $serial_no = $_POST['updateFile_serialNo'];
    $status = $_POST['updated_status'];
    $approver_status = '';

    $file = $_FILES['update_files']['name'];

    try {
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
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage(); // Output error message for debugging
    }
}
?>
