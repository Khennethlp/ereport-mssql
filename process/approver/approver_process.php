<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'update_approved_uploader') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $serial_no = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

    try {

        // Update t_training_record
        $update_sql = "UPDATE t_training_record SET approver_status = :status, approved_date = NOW(), approver_comment = :comment WHERE id = :id AND serial_no = :serial_no";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':serial_no', $serial_no);
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
