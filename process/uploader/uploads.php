<?php
include '../../process/conn.php';

$response = ''; // Initialize an empty response variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['method']) && $_POST['method'] == 'uploading') {
    $main_doc = $_POST['main_doc'];
    $sub_doc = $_POST['sub_doc'];
    $batch_no = $_POST['batch_no'];
    $training_group = $_POST['training_group'];
    $group_no = $_POST['group_no'];
    $uploader_id = $_POST['uploader_id'];
    $uploader_name = $_POST['uploader_name'];
    $checker_id = $_POST['checker_id'];
    $checker_name = $_POST['checker_name'];
    $checker_email = $_POST['checker_email'];
    $checker_status = $_POST['checker_status'];

    // Check if files were uploaded
    if (isset($_FILES['files']) && count($_FILES['files']['error']) > 0) {
        $uploadDir = __DIR__ . '/../../../uploads/ereport/';

        // Check if the uploads folder exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique random number for the series number folder if it does not already exist
        $serial_no = rand(1000000000, 9999999999);
        $seriesFolder = $uploadDir . $serial_no . '/';
        if (!is_dir($seriesFolder)) {
            mkdir($seriesFolder, 0777, true);
        }

        // Create or use existing main_doc folder inside the series folder
        $mainDocFolder = $seriesFolder . $main_doc . '/';
        if (!is_dir($mainDocFolder)) {
            mkdir($mainDocFolder, 0777, true);
        }

        // Create or use existing sub_doc folder inside the main_doc folder if sub_doc is provided
        if (!empty($sub_doc)) {
            $subDocFolder = $mainDocFolder . $sub_doc . '/';
            if (!is_dir($subDocFolder)) {
                mkdir($subDocFolder, 0777, true);
            }
        } else {
            $subDocFolder = $mainDocFolder;
        }

        foreach ($_FILES['files']['name'] as $key => $name) {
            $uploadFile = $subDocFolder . basename($name);
            $filename = basename($name);

            // Check if the uploaded file is a valid type
            $fileType = mime_content_type($_FILES['files']['tmp_name'][$key]);
            $allowedTypes = ['application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'application/vnd.ms-excel.sheet.macroEnabled.12'];

            if (in_array($fileType, $allowedTypes)) {
                // Move the uploaded file to the designated folder
                if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $uploadFile)) {
                    // Insert a new record in t_training_record
                    $stmt = $conn->prepare("INSERT INTO t_training_record (serial_no, batch_no, training_group, group_no, uploader_id, uploader_name, checker_id, checker_name, checker_email, checker_status) 
                                            VALUES (:serial_no, :batch_no, :training_group, :group_no, :uploader_id, :uploader_name, :checker_id, :checker_name, :checker_email, :checker_status)");
                    $stmt->bindParam(":serial_no", $serial_no);
                    $stmt->bindParam(":batch_no", $batch_no);
                    $stmt->bindParam(":training_group", $training_group);
                    $stmt->bindParam(":group_no", $group_no);
                    $stmt->bindParam(":uploader_id", $uploader_id);
                    $stmt->bindParam(":uploader_name", $uploader_name);
                    $stmt->bindParam(":checker_id", $checker_id);
                    $stmt->bindParam(":checker_name", $checker_name);
                    $stmt->bindParam(":checker_email", $checker_email);
                    $stmt->bindParam(":checker_status", $checker_status);

                    // Execute the t_training_record insert statement
                    if ($stmt->execute()) {
                        // Insert a new record in t_upload_file
                        $insert_upload = $conn->prepare("INSERT INTO t_upload_file (serial_no, main_doc, sub_doc, file_name) 
                                                         VALUES (:serial_no, :main_doc, :sub_doc, :file_name)");
                        $insert_upload->bindParam(":serial_no", $serial_no);
                        $insert_upload->bindParam(":main_doc", $main_doc);
                        $insert_upload->bindParam(":sub_doc", $sub_doc);
                        $insert_upload->bindParam(":file_name", $filename);

                        // Execute the t_upload_file insert statement
                        if ($insert_upload->execute()) {
                            $response = "success"; // Set success response
                        } else {
                            $response = "dberror"; // Set database error response
                        }
                    } else {
                        $response = "dberror"; // Set database error response
                    }
                } else {
                    $response = "upload error"; // Set upload error response
                }
            } else {
                $response = "invalid upload"; // Set invalid upload response
            }
        }
    } else {
        $response = "no upload"; // Set no upload error response
    }
} else {
    $response = "invalid request"; // Set invalid request error response
}

echo $response; // Echo the final response

?>
