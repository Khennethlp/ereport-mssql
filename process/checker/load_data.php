<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'checker_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $checker_name = isset($_POST['checker_name']) ? $_POST['checker_name'] : '';

    $acc_sql = "SELECT email, fullname FROM m_accounts WHERE fullname = :checker_name";
    $acc_stmt = $conn->prepare($acc_sql);
    $acc_stmt->bindParam(':checker_name', $checker_name, PDO::PARAM_STR);
    $acc_stmt->execute();
    $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
    $checker_email = $account['email'];

    $sql = "SELECT DISTINCT
                a.serial_no AS serial_no, 
                a.id AS id,
                a.uploader_name, 
                a.checker_status, 
                a.checker_name, 
                a.checker_email, 
                a.upload_date, 
                b.serial_no AS b_serial_no, 
                b.main_doc, 
                b.sub_doc, 
                b.file_name AS filenames
            FROM t_training_record a RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file GROUP BY serial_no) b ON a.serial_no = b.serial_no RIGHT JOIN (SELECT fullname, email FROM m_accounts) m ON a.uploader_name = m.fullname WHERE a.checker_name = :checker_name AND a.checker_email = :checker_email AND a.checker_status = '$status' GROUP BY b.serial_no";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':checker_name', $checker_name, PDO::PARAM_STR);
    $stmt->bindParam(':checker_email', $checker_email, PDO::PARAM_STR);
    $stmt->execute();
    
    $c = 0;

    if ($stmt->rowCount() > 0) {
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            $serial_no = htmlspecialchars($k['b_serial_no']);

            echo '<tr style="cursor:pointer" data-toggle="modal" data-target="#checker_modal"  onclick="checker(&quot;' . $k['id'] . '~!~' . $k['b_serial_no'] . '&quot;)">';
            echo '<td>' . $c . '</td>';
            echo '<td><span>' . htmlspecialchars($k['checker_status']) . '</span></td>';
            echo '<td>' . htmlspecialchars($k['b_serial_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['uploader_name']) . '</td>';
            // echo '<td>' . htmlspecialchars($k['upload_date']) . '</td>';
            echo '</tr>';
        }
    }
}

if ($method == 'checker_modal_table') {

    $serial_no = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // $sql = "SELECT a.id as id, a.serial_no AS serial_no, a.main_doc AS main_doc, a.sub_doc AS sub_doc, a.file_name AS file_name, b.checker_status AS c_status, b.checker_name AS c_name FROM t_upload_file a RIGHT JOIN (SELECT serial_no, uploader_name, checker_status, checker_name FROM t_training_record GROUP BY serial_no) b ON a.serial_no = b.serial_no WHERE a.serial_no = :serial_no";
    // $sql = "SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file WHERE serial_no = :serial_no";

    $sql = "SELECT 
    a.id AS id, 
    a.serial_no AS serial_no, 
    a.main_doc AS main_doc, 
    a.sub_doc AS sub_doc, 
    a.file_name AS file_name, 
    b.checker_status AS c_status, 
    b.checker_name AS c_name 
    FROM t_upload_file a 
    LEFT JOIN t_training_record b 
    ON a.serial_no = b.serial_no AND a.id = b.id 
    WHERE a.serial_no = :serial_no AND b.checker_status = '$status'";

    $stmt = $conn->prepare($sql);
    // $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);

    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            // Construct file path
            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= htmlspecialchars($k['file_name']);
            $serial_no = htmlspecialchars($k['serial_no']);
            $c_name = htmlspecialchars($k['c_name']);
            $id = htmlspecialchars($k['id']);

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            // echo '<td>' . $serial_no . '</td>';

            // Check if file exists and display link
            if (file_exists($file_path)) {
                echo '<td><a href="../../pages/checker/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . urlencode($file_path) . '&checker=' . htmlspecialchars($c_name) . '" target="_blank">' . htmlspecialchars($k['file_name']) . '</a></td>';
            } else {
                echo '<td>File not found</td>';
            }

            // echo '<td><span>' . htmlspecialchars($k['c_status']) . '</span></td>';
            // echo '<td>' . htmlspecialchars($k['sub_doc']) . '</td>';
            // echo '<td>' . htmlspecialchars($k['file_name']) . '</td>';
            echo '</tr>';
        }
    }
}
