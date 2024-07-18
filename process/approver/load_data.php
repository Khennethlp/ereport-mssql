<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'approver_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $approver_id = isset($_POST['approver_id']) ? $_POST['approver_id'] : '';

    // $acc_sql = "SELECT email, fullname FROM m_accounts WHERE fullname = :approver_name";
    // $acc_stmt = $conn->prepare($acc_sql);
    // $acc_stmt->bindParam(':approver_name', $approver_name, PDO::PARAM_STR);
    // $acc_stmt->execute();
    // $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);
    // $approver_email = $account['email'];

    $sql = "SELECT DISTINCT a.id AS id, a.serial_no AS serial_no, a.batch_no As batch_no, a.checked_date AS checked_date, a.checker_name AS checker_name, a.approver_status AS approver_status, a.approver_name AS approver_name, approver_id AS a_id, a.approver_email AS approver_email, b.serial_no, b.main_doc AS main_doc, b.sub_doc AS sub_doc, b.file_name AS filenames FROM t_training_record a RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no AND a.id=b.id WHERE a.approver_id = :approver_id AND a.approver_status = :status ";

    if (!empty($search_by)) {
        $sql .= " AND a.serial_no LIKE :search_by";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $sql .= " AND a.upload_date BETWEEN :date_from AND :date_to";
    }
    $sql .= " ORDER BY id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);
    // $stmt->bindParam(':approver_email', $approver_email, PDO::PARAM_STR);
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
            $serial_no = htmlspecialchars($k['serial_no']);
            $status_text = strtoupper(htmlspecialchars($k['approver_status']));

            $status_bg_color = '';
            // $status_badge_color = '';
            switch ($status_text) {
                case 'APPROVED':
                    $status_bg_color = 'background-color: var(--success); color: #fff;';
                    // $status_badge_color = 'badge-success';
                    break;
                case 'PENDING':
                    $status_bg_color = 'background-color: var(--warning);';
                    // $status_badge_color = 'badge-secondary';
                    break;
                case 'DISAPPROVED':
                    $status_bg_color = 'background-color: var(--danger); color: #fff;';
                    // $status_badge_color = 'badge-danger';
                    break;
                default:
                    $status_bg_color = 'background-color: var(--primary);';
                    // $status_badge_color = 'badge-primary';
                    break;
            }

            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= htmlspecialchars($k['filenames']);
            $a_id = htmlspecialchars($k['a_id']);
            $id = htmlspecialchars($k['id']);

            // echo '<tr style="cursor:pointer;  ' . $status_bg_color . ' " data-toggle="modal" data-target="#approver_modal"  onclick="approver(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '&quot;)">';
            echo '<tr style="  ' . $status_bg_color . ' ">';
            echo '<td>' . $c . '</td>';
            echo '<td><span>' . strtoupper(htmlspecialchars($k['approver_status'])) . '</span></td>';
            echo '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            // echo '<td>' . htmlspecialchars($k['filenames']) . '</td>';
            if (file_exists($file_path)) {
                if ($status == 'approved' || $status == 'disapproved') {
                    echo '<td>' . htmlspecialchars($k['filenames']) . '</td>';
                } else {
                    echo '<td><a href="../../pages/approver/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . urlencode($file_path) . '&approver=' . htmlspecialchars($a_id) . '" target="_blank">' . htmlspecialchars($k['filenames']) . '</a></td>';
                    // echo '<td><a href="#" onclick="downloadAndViewFile(\'' . htmlspecialchars($id) . '\', \'' . htmlspecialchars($serial_no) . '\', \'' . urlencode($file_path) . '\', \'' . htmlspecialchars($a_id) . '\', \'' . urlencode($k['filenames']) . '\')">' . htmlspecialchars($k['filenames']) . '</a></td>';

                }
            } else {
                echo '<td>File not found</td>';
            }
            echo '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            echo '<td>' . date('Y/m/d', strtotime($k['checked_date'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">No records found.</td>';
        echo '</tr>';
    }
}


if ($method == 'approver_modal_table') {

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
    b.approver_status AS a_status, 
    b.approver_id AS a_id, 
    b.approver_name AS a_name 
    FROM t_upload_file a 
    LEFT JOIN t_training_record b 
    ON a.serial_no = b.serial_no AND a.id = b.id 
    WHERE a.serial_no = :serial_no AND b.approver_status = '$status'";

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
            $a_name = htmlspecialchars($k['a_name']);
            $a_id = htmlspecialchars($k['a_id']);
            $id = htmlspecialchars($k['id']);

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            // echo '<td>' . $serial_no . '</td>';

            // Check if file exists and display link
            if (file_exists($file_path)) {
                if ($status == 'approved' || $status == 'disapproved') {
                    echo '<td>' . htmlspecialchars($k['file_name']) . '</td>';
                } else {
                    echo '<td><a href="../../pages/approver/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . urlencode($file_path) . '&approver=' . htmlspecialchars($a_id) . '" target="_blank">' . htmlspecialchars($k['file_name']) . '</a></td>';
                }
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
