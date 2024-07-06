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

    try {

        // Update t_training_record
        $update_sql = "UPDATE t_training_record SET checker_status = :status, checked_date = NOW(), checker_comment = :comment WHERE id = :id ";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':id', $id);
        // $stmt->bindParam(':serial_no', $serial_no);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':comment', $comment);

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
