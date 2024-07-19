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

    // Check if approver_id and approver_email are set
    $approver_id = isset($_POST['approver_id']) ? $_POST['approver_id'] : '';
    $approver_email = isset($_POST['approver_email']) ? $_POST['approver_email'] : null;
    $approver_status = ($status === 'Disapproved') ? '' : 'Pending';

    if ($status === 'Disapproved' && isset($_FILES['file_attached'])) {
        $acc_sql = "SELECT emp_id, email, fullname FROM m_accounts WHERE emp_id = :checker_id";
        $acc_stmt = $conn->prepare($acc_sql);
        $acc_stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
        $acc_stmt->execute();
        $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
        $checker_name = $account['fullname'];

        $file = $_FILES['file_attached']['name'];
        $tmp_name = $_FILES['file_attached']['tmp_name'];

        // Define the base directory for uploads
        $uploadDir = __DIR__ . '/../../../uploads/ereport/' . $serial_no . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        try {
            // Fetch the current file name and folder structure from the database
            $sql_fetch_file = "SELECT file_name, main_doc, sub_doc FROM t_upload_file WHERE serial_no = :serial_no AND id = :id";
            $stmt_fetch_file = $conn->prepare($sql_fetch_file);
            $stmt_fetch_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
            $stmt_fetch_file->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_fetch_file->execute();
            $file_info = $stmt_fetch_file->fetch(PDO::FETCH_ASSOC);

            if ($file_info) {
                $main_doc = $file_info['main_doc'];
                $sub_doc = $file_info['sub_doc'];

                // Define the path to the "for checking" folder
                if ($sub_doc) {
                    $forCheckingFolder = $uploadDir . $main_doc . '/' . $sub_doc . '/for checking/';
                } else {
                    $forCheckingFolder = $uploadDir . $main_doc . '/for checking/';
                }

                // Ensure the "for checking" folder exists
                if (!is_dir($forCheckingFolder)) {
                    mkdir($forCheckingFolder, 0777, true);
                }

                // Define the path to the new file
                $new_file_path = $forCheckingFolder . $file;

                // Move the new file to the "for checking" folder
                if (move_uploaded_file($tmp_name, $new_file_path)) {
                    // Update t_upload_file
                    $sql_upload_file = "UPDATE t_upload_file SET updated_file = :file WHERE serial_no = :serial_no AND id = :id";
                    $stmt_upload_file = $conn->prepare($sql_upload_file);
                    $stmt_upload_file->bindParam(':file', $file, PDO::PARAM_STR);
                    $stmt_upload_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                    $stmt_upload_file->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt_upload_file->execute();

                    // Update t_training_record
                    $update_sql = "UPDATE t_training_record SET checker_status = :status, checker_name = :checker_name, checked_date = NOW(), checker_comment = :comment, approver_id = :approver_id, approver_email = :approver_email, approver_status = :approver_status WHERE id = :id";
                    $stmt_training_record = $conn->prepare($update_sql);
                    $stmt_training_record->bindParam(':id', $id);
                    $stmt_training_record->bindParam(':status', $status);
                    $stmt_training_record->bindParam(':comment', $comment);
                    $stmt_training_record->bindParam(':checker_name', $checker_name);
                    $stmt_training_record->bindParam(':approver_id', $approver_id);
                    $stmt_training_record->bindParam(':approver_email', $approver_email);
                    $stmt_training_record->bindParam(':approver_status', $approver_status);
                    $stmt_training_record->execute();

                    echo 'success';
                } else {
                    echo 'error'; // Error moving the uploaded file
                }
            } else {
                echo 'file error'; // Error fetching file info from database
            }
        } catch (PDOException $e) {
            echo 'error: ' . $e->getMessage(); // Output error message for debugging
        }
    } else if ($status === 'Approved') {
        // If approved, no file uploading

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
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':checker_name', $checker_name);
            $stmt->bindParam(':approver_id', $approver_id);
            $stmt->bindParam(':approver_email', $approver_email);
            $stmt->bindParam(':approver_status', $approver_status);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error";
            }
        } catch (PDOException $e) {
            echo "error: " . $e->getMessage();
        }
    } else {
        echo "invalid request";
    }
}
?>
