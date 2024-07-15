<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'checker_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $checker_id = isset($_POST['checker_id']) ? $_POST['checker_id'] : '';

    $acc_sql = "SELECT emp_id, email, fullname FROM m_accounts WHERE emp_id = :checker_id";
    $acc_stmt = $conn->prepare($acc_sql);
    $acc_stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
    $acc_stmt->execute();
    $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
    // $checker_email = $account['email'];
    // $checker_email = $account['email'];

    $sql = "SELECT DISTINCT
                a.serial_no AS serial_no, 
                a.id AS id,
                a.uploader_name, 
                a.checker_status, 
                a.checker_id, 
                a.checker_name, 
                a.checker_email, 
                a.upload_date, 
                a.batch_no AS batch_no, 
                b.serial_no AS b_serial_no, 
                b.main_doc, 
                b.sub_doc, 
                b.file_name AS filenames
            FROM t_training_record a RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file GROUP BY serial_no) b ON a.serial_no = b.serial_no WHERE a.checker_id = :checker_id AND a.checker_status = :status ";
//  AND a.checker_email = :checker_email
    if (!empty($search_by)) {
        $sql .= " AND a.serial_no LIKE :search_by";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $sql .= " AND a.upload_date BETWEEN :date_from AND :date_to";
    }

    $sql .= " GROUP BY b.serial_no";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
    // $stmt->bindParam(':checker_email', $checker_email, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    if (!empty($search_by)) {
        $search_by = "%$search_by%";
        $stmt->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    $stmt->execute();

    $c = 0;

    if ($stmt->rowCount() > 0) {
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            $serial_no = htmlspecialchars($k['b_serial_no']);

            echo '<tr style="cursor:pointer" data-toggle="modal" data-target="#checker_modal"  onclick="checker(&quot;' . $k['id'] . '~!~' . $k['b_serial_no'] . '&quot;)">';
            echo '<td>' . $c . '</td>';
            echo '<td><span>' . strtoupper(htmlspecialchars($k['checker_status'])) . '</span></td>';
            echo '<td>' . htmlspecialchars($k['b_serial_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['uploader_name']) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr >';
        echo '<td colspan="5" class="text-center">No data found.</td>';
        echo '</tr>';
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
    b.checker_id AS c_id, 
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
            $c_id = htmlspecialchars($k['c_id']);
            $id = htmlspecialchars($k['id']);

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            // echo '<td>' . $serial_no . '</td>';

            // Check if file exists and display link
            if (file_exists($file_path)) {
                echo '<td><a href="../../pages/checker/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . urlencode($file_path) . '&checker=' . htmlspecialchars($c_id) . '" target="_blank">' . htmlspecialchars($k['file_name']) . '</a></td>';
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
