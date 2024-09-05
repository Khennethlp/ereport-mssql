<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'getData_masterlist') {
    $sortBy = intval($_POST['sortBy']) ?? 0;
    $serialNo = isset($_POST['serialNo']) ? $_POST['serialNo'] : '';

    $sql = "SELECT a.*, b.* 
            FROM t_training_record a 
            LEFT JOIN (SELECT * FROM t_upload_file GROUP BY serial_no) b 
            ON a.serial_no = b.serial_no 
            WHERE a.approver_status = 'APPROVED' ";

    if (!empty($serialNo)) {
        $sql .= "AND a.serial_no = :serial_no ";
    }

    $sql .= "LIMIT :sortBy";

    $stmt = $conn->prepare($sql);

    if (!empty($serialNo)) {
        $stmt->bindParam(':serial_no', $serialNo);
    }
    $stmt->bindParam(':sortBy', $sortBy, PDO::PARAM_INT);

    $stmt->execute();

    $c = 0;
    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $k) {
            $c++;
            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= $k['file_name'];

            echo '<tr class="parent" style="cursor: pointer;">';
            echo '<td><i class="fas fa-edit text-info mx-1 hidden_edit" data-toggle="modal" data-target="#update_admin" onclick="update_data_admin(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '~!~' . $k['batch_no'] . '~!~' . $k['group_no'] . '~!~' . $k['upload_month'] . '~!~' . $k['upload_year'] . '~!~' . $k['training_group'] . '~!~' . $k['file_name'] . '~!~' . $k['checker_name'] . '~!~' . $k['checked_date'] . '~!~' . $k['approver_name'] . '~!~' . $k['approved_date'] . '~!~' . $k['main_doc'] . '&quot;)"></i></td>';
            echo '<td>' . $c . '</td>';
            
            if (file_exists($file_path)) {
                // echo '<td style="cursor: pointer;"><a href="' . $file_path . '" target="_blank">View</a></td>';
                echo '<td> <p class="badge badge-success">Active</p> </td>';
            } else {
                echo '<td><p class="badge badge-danger">Inactive</p></td>';
            }

            echo '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['group_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['upload_month']) . '</td>';
            echo '<td>' . htmlspecialchars($k['upload_year']) . '</td>';
            echo '<td>' . htmlspecialchars($k['main_doc']) . '</td>';
            echo '<td>' . htmlspecialchars($k['training_group']) . '</td>';
            echo '<td title="' . $k['file_name'] . '">' . (strlen($k['file_name']) > 45 ? substr($k['file_name'], 0, 45) . '...' : $k['file_name']) . '</td>';

            echo '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            if (empty($k['checker_name'])) {
                echo '<td></td>';
            } else {
                echo '<td>' . date('Y/m/d', strtotime($k['checked_date'])) . '</td>';
            }
            echo '<td>' . htmlspecialchars($k['approver_name']) . '</td>';
            echo '<td>' . date('Y/m/d', strtotime($k['approved_date'])) . '</td>';



            // echo '<td style="cursor: pointer;">

            //  <i class="fas fa-edit text-info mx-1" data-toggle="modal" data-target="#update_admin" onclick="update_data_admin(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '~!~' . $k['batch_no'] . '~!~' . $k['group_no'] . '~!~' . $k['upload_month'] . '~!~' . $k['upload_year'] . '~!~' . $k['training_group'] . '~!~' . $k['file_name'] . '~!~' . $k['checker_name'] . '~!~' . $k['checked_date'] . '~!~' . $k['approver_name'] . '~!~' . $k['approved_date'] . '~!~' . $k['main_doc'] . '&quot;)"></i>

            // </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>';
    }
}


if ($method == 'update_admin') {
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
        $sql_training_record = "UPDATE t_training_record SET batch_no = :batch_no, group_no = :group_no, training_group = :training_group, upload_month = :upload_month, upload_year = :upload_year WHERE id = :id AND serial_no = :serialNo";
        $stmt = $conn->prepare($sql_training_record);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':serialNo', $serialNo);
        $stmt->bindParam(':batch_no', $batchNo);
        $stmt->bindParam(':group_no', $groupNo);
        $stmt->bindParam(':training_group', $trainingGroup);
        $stmt->bindParam(':upload_month', $month);
        $stmt->bindParam(':upload_year', $year);

        if ($stmt->execute()) {

            $sql_upload_file = "UPDATE t_upload_file SET main_doc = :main_doc, file_name = :file_name WHERE id = :id AND serial_no = :serialNo";
            $stmt = $conn->prepare($sql_upload_file);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':serialNo', $serialNo);
            $stmt->bindParam(':main_doc', $mainDoc);
            $stmt->bindParam(':file_name', $filename);
            $stmt->execute();
            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
}

if ($method == 'remove_data') {
    $id = $_POST['id'];
    $serialNo = $_POST['serialNo'];

    try {
        $del_training_record = "DELETE FROM t_training_record WHERE id = '$id' AND serial_no = '$serialNo' ";
        $stmt = $conn->prepare($del_training_record);

        if ($stmt->execute()) {
            $del_upload_file = "DELETE FROM t_upload_file WHERE id = '$id' AND serial_no = '$serialNo' ";
            $stmt = $conn->prepare($del_upload_file);
            $stmt->execute();

            echo 'success';
        } else {
            echo 'error';
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
}

if ($method == 'counts') {
    $sql = "SELECT count(*) as count FROM t_training_record WHERE approver_status = 'APPROVED'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result !== false) {
        echo 'Total: ' . $result['count'];
    } else {
        echo 'Total: 0';
    }
}
