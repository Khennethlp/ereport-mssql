<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'update_check_uploader') {
    $serial_no = $_POST['serial_no'];
    $checker_id = $_POST['checked_by'];
    $approver = $_POST['approver'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $id = $_POST['id'];

    $approver_id = $_POST['approver_id'];
    $approver_email = $_POST['approver_email'];
    // $approver_status = 'Pending';
    $approver_status = ($status === 'Disapproved') ? '' : 'Disapproved';

    $acc_sql = "SELECT emp_id, email, fullname FROM m_accounts WHERE emp_id = :checker_id";
    $acc_stmt = $conn->prepare($acc_sql);
    $acc_stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
    $acc_stmt->execute();
    $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
    $checker_name = $account['fullname'];
    
    try {

        // Update t_training_record
        $update_sql = "UPDATE t_training_record SET checker_status = :status, checker_name = :checker_name, checked_date = NOW(), checker_comment = :comment, approver_id = :approver_id, approver_email = :approver_email, approver_status = :approver_status WHERE id = :id ";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':id', $id);
        // $stmt->bindParam(':serial_no', $serial_no);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':checker_name', $checker_name);
        $stmt->bindParam(':approver_id', $approver_id);
        $stmt->bindParam(':approver_email', $approver_email);
        $stmt->bindParam(':approver_status', $approver_status);

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
