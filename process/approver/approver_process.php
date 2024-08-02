<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'update_approved_uploader') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $serial_no = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $approver_id = isset($_POST['approver_id']) ? $_POST['approver_id'] : '';

    // Fetch the approver's name
    $approver_name = '';
    try {
        $acc_sql = "SELECT emp_id, email, fullname FROM m_accounts WHERE emp_id = :approver_id";
        $acc_stmt = $conn->prepare($acc_sql);
        $acc_stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);
        $acc_stmt->execute();
        $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
        if ($account) {
            $approver_name = $account['fullname'];
        } else {
            //    throw new Exception('Approver not found');
            echo 'approver not found';
        }
    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
        exit;
    }

    if ($status === 'Disapproved' && isset($_FILES['file_attached'])) {

        $file = $_FILES['file_attached']['name'];
        $tmp_name = $_FILES['file_attached']['tmp_name'];

        // Define the base directory for uploads
        $uploadDir = __DIR__ . '/../../../uploads/ereport/' . $serial_no . '/';

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
                // $current_file = $file_info['file_name'];
                $main_doc = $file_info['main_doc'];
                $sub_doc = $file_info['sub_doc'];

                // Define the path to the "for approval" folder
                if ($sub_doc) {
                    $uploadDir  = $uploadDir . $main_doc . '/' . $sub_doc . '/';
                } else {
                    $uploadDir  = $uploadDir . $main_doc . '/';
                }

                // Ensure the "for approval" folder exists
                if (!is_dir($uploadDir )) {
                    mkdir($uploadDir , 0777, true);
                }

                $new_file_path = $uploadDir  . $file;

                // Move the new file to the correct location
                if (move_uploaded_file($tmp_name, $new_file_path)) {
                    // Update t_upload_file
                    $sql_upload_file = "UPDATE t_upload_file SET updated_file = :file WHERE serial_no = :serial_no AND id = :id";
                    $stmt_upload_file = $conn->prepare($sql_upload_file);
                    $stmt_upload_file->bindParam(':file', $file, PDO::PARAM_STR);
                    $stmt_upload_file->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                    $stmt_upload_file->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt_upload_file->execute();

                    // Update t_training_record
                    $update_sql = "UPDATE t_training_record SET approver_status = :status, approved_date = NOW(), approver_name = :approver_name, approver_comment = :comment WHERE id = :id AND serial_no = :serial_no";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':serial_no', $serial_no);
                    $stmt->bindParam(':status', $status);
                    $stmt->bindParam(':comment', $comment);
                    $stmt->bindParam(':approver_name', $approver_name);
                    $stmt->execute();

                    $sql_file_revision = "INSERT INTO file_revisions (serial_no, revised_by) VALUES (:serial_no, :revised_by)";
                    $stmt_file_revision = $conn->prepare($sql_file_revision);
                    $stmt_file_revision->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
                    $stmt_file_revision->bindParam(':revised_by', $approver_name, PDO::PARAM_STR);
                    $stmt_file_revision->execute();

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
    } else if ($status === 'Approved') {

        try {
            // Update t_training_record
            $update_sql = "UPDATE t_training_record SET approver_status = :status, approved_date = NOW(), approver_name = :approver_name, approver_comment = :comment WHERE id = :id AND serial_no = :serial_no";
            $stmt = $conn->prepare($update_sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':serial_no', $serial_no);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':approver_name', $approver_name);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error";
            }
        } catch (PDOException $e) {
            echo "error: " . $e->getMessage();
        }
    }
} else {
    echo "invalid request";
}
