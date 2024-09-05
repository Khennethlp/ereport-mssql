<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_data') {
    $serialNo = $_POST['serialNo'];
    $batchNo = $_POST['batchNo'];
    $groupNo = $_POST['groupNo'];
    $trainingGroup = $_POST['trainingGroup'];
    $fileName = $_POST['fileName'];
    $docs = $_POST['docs'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 50;
    $offset = ($page - 1) * $rowsPerPage;

    // Construct SQL query
     $sql = "SELECT a.*, b.* 
        FROM t_training_record a 
        LEFT JOIN (SELECT DISTINCT serial_no, file_name, main_doc, sub_doc FROM t_upload_file) b 
        ON a.serial_no = b.serial_no 
        WHERE approver_status = 'APPROVED'";

    if (!empty($serialNo)) {
        $sql .= " AND a.serial_no LIKE :search_by_serialNo";
    }
    if (!empty($batchNo)) {
        $sql .= " AND a.batch_no LIKE :search_by_batchNo";
    }
    if (!empty($groupNo)) {
        $sql .= " AND a.group_no LIKE :search_by_groupNo";
    }
    if (!empty($trainingGroup)) {
        $sql .= " AND a.training_group LIKE :search_by_tGroup";
    }
    if (!empty($fileName)) {
        $sql .= " AND b.file_name LIKE :search_by_filename";
    }
    if (!empty($docs)) {
        $sql .= " AND b.main_doc LIKE :search_by_docs";
    }
    if (!empty($year)) {
        $sql .= " AND a.upload_year = :year";
    }
    if (!empty($month)) {
        $sql .= " AND a.upload_month LIKE :month";
    }

    // Apply pagination
    $sql .= " ORDER BY a.serial_no 
          OFFSET :offset ROWS 
          FETCH NEXT :rows_per_page ROWS ONLY";
   
    // Debug SQL query
    // echo '<pre>';
    // echo $sql;
    // echo '</pre>';

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    // Bind parameters
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':rows_per_page', $rowsPerPage, PDO::PARAM_INT);

    if (!empty($serialNo)) {
        $serialNo = "$serialNo%";
        $stmt->bindParam(':search_by_serialNo', $serialNo, PDO::PARAM_STR);
    }
    if (!empty($batchNo)) {
        $batchNo = "$batchNo%";
        $stmt->bindParam(':search_by_batchNo', $batchNo, PDO::PARAM_STR);
    }
    if (!empty($groupNo)) {
        $groupNo = "$groupNo%";
        $stmt->bindParam(':search_by_groupNo', $groupNo, PDO::PARAM_STR);
    }
    if (!empty($trainingGroup)) {
        $trainingGroup = "$trainingGroup%";
        $stmt->bindParam(':search_by_tGroup', $trainingGroup, PDO::PARAM_STR);
    }
    if (!empty($fileName)) {
        $fileName = "$fileName%";
        $stmt->bindParam(':search_by_filename', $fileName, PDO::PARAM_STR);
    }
    if (!empty($docs)) {
        $docs = "%$docs%";
        $stmt->bindParam(':search_by_docs', $docs, PDO::PARAM_STR);
    }
    if (!empty($year)) {
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    }
    if (!empty($month)) {
        $month = "$month%";
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
    }

    // Execute query
    if (!$stmt->execute()) {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['error' => 'Database error: ' . $errorInfo[2]]);
        exit;
    }

    // Fetch results
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($rows) > $rowsPerPage;
    if ($has_more) {
        array_pop($rows); // Remove the extra row used for the check
    }

    $data = '';
    $c = $offset + 1;
    foreach ($rows as $k) {
        $file_path = '../../../uploads/ereports/' . $k['serial_no'] . '/';
        $file_path .= $k['main_doc'] . '/';
        if (!empty($k['sub_doc'])) {
            $file_path .= $k['sub_doc'] . '/';
        }
        $file_path .= $k['file_name'];

        $data .= '<tr style="cursor: pointer;" data-toggle="modal" data-target="#update_admin" onclick="update_data_admin(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '~!~' . $k['batch_no'] . '~!~' . $k['group_no'] . '~!~' . $k['upload_month'] . '~!~' . $k['upload_year'] . '~!~' . $k['training_group'] . '~!~' . $k['file_name'] . '~!~' . $k['checker_name'] . '~!~' . $k['checked_date'] . '~!~' . $k['approver_name'] . '~!~' . $k['approved_date'] . '~!~' . $k['main_doc'] . '&quot;)">';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['upload_month']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['upload_year']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['main_doc']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';
        $data .= '<td title="' . $k['file_name'] . '">' . (strlen($k['file_name']) > 45 ? substr($k['file_name'], 0, 45) . '...' : $k['file_name']) . '</td>';

        $data .= '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
        if (empty($k['checker_name'])) {
            $data .= '<td></td>';
        } else {
            $data .= '<td>' . date('Y/m/d', strtotime($k['checked_date'])) . '</td>';
        }
        $data .= '<td>' . htmlspecialchars($k['approver_name']) . '</td>';
        $data .= '<td>' . date('Y/m/d', strtotime($k['approved_date'])) . '</td>';

        if (file_exists($file_path)) {
            $data .= '<td style="cursor: pointer;"><a href="' . $file_path . '" target="_blank">View</a></td>';
            //   $data .= '<td>File found</td>';
            }else{
            $data .= '<td>File not found</td>';

        }

        $data .= '</tr>';
        $c++;
    }

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}



if ($method == 'load_docs') {

    try {
        // Prepare the SQL statement
        $sql = "SELECT * FROM m_report_title";
        $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

        // Execute the statement
        $stmt->execute();

        // Initialize row counter
        $c = 0;

        // Fetch and display results
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . htmlspecialchars($k['main_doc']) . '</td>';
            echo '<td>' . htmlspecialchars($k['sub_doc']) . '</td>';
            echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_docs" onclick="get_docs(&quot;' . htmlspecialchars($k['id']) . '~!~' . htmlspecialchars($k['main_doc']) . '~!~' . htmlspecialchars($k['sub_doc']) . '&quot;)"><i class="fas fa-ellipsis-h"></i></td>';
            echo '</tr>';
        }
    } catch (PDOException $e) {
        // Handle and display error message
        echo 'error: ' . $e->getMessage();
    }
}


if ($method == 'add_new_docs') {

    $main_doc = $_POST['main_doc'];
    $sub_doc = $_POST['sub_doc'];

    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO m_report_title (main_doc, sub_doc) VALUES (:main_doc, :sub_doc)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':main_doc', $main_doc, PDO::PARAM_STR);
        $stmt->bindParam(':sub_doc', $sub_doc, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        // Handle and display error message
        echo 'error: ' . $e->getMessage();
    }
}


if ($method == 'update_docs') {
    $id = $_POST['id'];
    $main_doc = $_POST['main_doc'];
    $sub_doc = $_POST['sub_doc'];

    try {
        // Prepare the SQL statement
        $sql = "UPDATE m_report_title SET main_doc = :main_doc, sub_doc = :sub_doc WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':main_doc', $main_doc, PDO::PARAM_STR);
        $stmt->bindParam(':sub_doc', $sub_doc, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        // Handle and display error message
        echo 'error: ' . $e->getMessage();
    }
}


if ($method == 'del_docs') {
    $id = $_POST['id'];

    try {
        // Prepare the SQL statement
        $sql = "DELETE FROM m_report_title WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        // Handle and display error message
        echo 'error: ' . $e->getMessage();
    }
}


if ($method == 'load_trainings') {

    try {
        // Prepare the SQL statement
        $sql = "SELECT * FROM t_training_group";

        $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

        // Execute the statement
        $stmt->execute();

        $c = 0;
        // Check if any rows are returned
        if ($stmt->rowCount() > 0) {
            while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $c++;
                echo '<tr>';
                echo '<td>' . htmlspecialchars($c) . '</td>';
                echo '<td>' . htmlspecialchars($k['training_title']) . '</td>';
                echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_training" onclick="get_train(&quot;' . htmlspecialchars($k['id']) . '~!~' . htmlspecialchars($k['training_title']) . '&quot;)"><i class="fas fa-ellipsis-h"></i></td>';
                echo '</tr>';
            }
        } else {
            // Optionally handle the case where no rows are returned
            echo '<tr><td colspan="3" style="text-align:center;">No training groups found.</td></tr>';
        }
    } catch (PDOException $e) {
        // Handle errors and output error message
        echo 'error: ' . $e->getMessage();
    }
}



if ($method == 'update_training') {
    $id = $_POST['id'];
    $training_title = $_POST['t_title'];

    try {
        // Prepare the SQL statement for updating the training title
        $sql = "UPDATE t_training_group 
                SET training_title = :training_title 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':training_title', $training_title, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        // Handle errors and output error message
        echo 'error: ' . $e->getMessage();
    }
}


if ($method == 'del_training') {
    $id = $_POST['id'];

    // Prepare the SQL statement
    $sql = "DELETE FROM t_training_group WHERE id = :id";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        // Execute the statement
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        // Handle SQL errors
        echo 'Database error: ' . $e->getMessage();
    }
}

if ($method == 'counts') {
    // SQL query to count the number of approved training records
    $sql = "SELECT COUNT(*) as count FROM t_training_record WHERE approver_status = 'APPROVED'";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    try {
        // Execute the statement
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the result is not false
        if ($result !== false) {
            echo 'Total: ' . $result['count'];
        } else {
            echo 'Total: 0';
        }
    } catch (PDOException $e) {
        // Handle any SQL errors
        echo 'Database error: ' . $e->getMessage();
    }
}


if ($method == 'update_admin') {
    // Retrieve POST parameters
    $id = $_POST['id'];
    $serialNo = $_POST['serialNo'];
    $batchNo = $_POST['batchNo'];
    $groupNo = $_POST['groupNo'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $trainingGroup = $_POST['trainingGroup'];
    $mainDoc = $_POST['mainDoc'];
    $filename = $_POST['filename'];

    try {
        // Start a transaction
        $conn->beginTransaction();
        
        // Prepare and execute the update for t_training_record
        $sql_training_record = "UPDATE t_training_record 
            SET batch_no = :batch_no, 
                group_no = :group_no, 
                training_group = :training_group, 
                upload_month = :upload_month, 
                upload_year = :upload_year 
            WHERE id = :id 
            AND serial_no = :serialNo";
        $stmt = $conn->prepare($sql_training_record);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':serialNo', $serialNo, PDO::PARAM_STR);
        $stmt->bindParam(':batch_no', $batchNo, PDO::PARAM_STR);
        $stmt->bindParam(':group_no', $groupNo, PDO::PARAM_STR);
        $stmt->bindParam(':training_group', $trainingGroup, PDO::PARAM_STR);
        $stmt->bindParam(':upload_month', $month, PDO::PARAM_STR);
        $stmt->bindParam(':upload_year', $year, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            // Rollback if update fails
            $conn->rollBack();
            echo 'error: Failed to update t_training_record';
            exit;
        }

        // Prepare and execute the update for t_upload_file
        $sql_upload_file = "UPDATE t_upload_file 
            SET main_doc = :main_doc, 
                file_name = :file_name 
            WHERE id = :id 
            AND serial_no = :serialNo";
        $stmt = $conn->prepare($sql_upload_file);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':serialNo', $serialNo, PDO::PARAM_STR);
        $stmt->bindParam(':main_doc', $mainDoc, PDO::PARAM_STR);
        $stmt->bindParam(':file_name', $filename, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            // Rollback if update fails
            $conn->rollBack();
            echo 'error: Failed to update t_upload_file';
            exit;
        }

        // Commit the transaction if both updates succeed
        $conn->commit();
        echo 'success';
        
    } catch (PDOException $e) {
        // Rollback in case of exception
        $conn->rollBack();
        echo 'error: ' . $e->getMessage();
    }
}

