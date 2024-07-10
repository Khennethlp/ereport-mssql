<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'update_check_uploader') {
    $serial_no = $_POST['serial_no'];
    $checked_by = $_POST['checked_by'];
    $approver = $_POST['approver'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $id = $_POST['id'];

    $approver_id = $_POST['approver_id'];
    $approver_name = $_POST['approver_name'];
    $approver_email = $_POST['approver_email'];
    $approver_status = ($status == 'disapproved') ? 'disapproved' : 'pending';
    $approved_date = ($status == 'disapproved') ? date('Y-m-d H:i:s') : null;

    try {

        // Update t_training_record
        $update_sql = "UPDATE t_training_record SET checker_status = :status, checked_date = NOW(), checker_comment = :comment, approver_id = :approver_id, approver_name = :approver_name, approver_email = :approver_email, approver_status = :approver_status, approved_date = :approved_date WHERE id = :id ";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':id', $id);
        // $stmt->bindParam(':serial_no', $serial_no);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':comment', $comment);

        $stmt->bindParam(':approver_id', $approver_id);
        $stmt->bindParam(':approver_name', $approver_name);
        $stmt->bindParam(':approver_email', $approver_email);
        $stmt->bindParam(':approver_status', $approver_status);
        $stmt->bindParam(':approved_date', $approved_date);

        if($stmt->execute()){
            echo "success";
        }else{
            echo "error";
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "invalid request";
}
