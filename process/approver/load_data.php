<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'approver_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $approver_id = isset($_POST['approver_id']) ? $_POST['approver_id'] : '';

    $sql = "SELECT DISTINCT a.id AS id, 
    a.serial_no AS serial_no, 
    a.batch_no As batch_no, 
    a.checked_date AS checked_date, 
    a.checker_name AS checker_name, 
    a.approver_name AS approver_name, 
    CASE 
        WHEN a.approver_status = 'Pending' THEN 'Pending'
        WHEN a.approver_status = 'Disapproved' THEN 'Disapproved'
        WHEN a.approver_status = 'Approved' THEN 'Approved'
            ELSE a.approver_status
        END AS approver_status,
        CASE
            WHEN a.approver_status = 'Pending' THEN a.checked_date
            WHEN a.approver_status = 'Approved' THEN a.approved_date
            WHEN a.approver_status = 'Disapproved' THEN a.approved_date
        END AS approved_date,
    a.approver_id AS a_id, 
    a.approver_email AS approver_email, 
    b.serial_no, 
    b.main_doc AS main_doc,
    b.sub_doc AS sub_doc, 
    b.updated_file AS updated_file, 
    b.file_name AS filenames 
    FROM t_training_record a 
    RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, updated_file, file_name FROM t_upload_file) b 
    ON a.serial_no = b.serial_no AND a.id=b.id 
    WHERE a.approver_id = :approver_id AND a.approver_status = :status ";

    if (!empty($status)) {
        $sql .=  " AND (
                   (a.approver_status = 'Pending' AND :status = 'Pending') OR
                   (a.approver_status = 'Disapproved' AND :status = 'Disapproved') OR
                   (a.approver_status = 'Approved' AND :status = 'Approved')
                )";
    }
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

            // $file_path = '../../../uploads/ereport/' . ($k['serial_no']) . '/';
                // $file_path .= ($k['main_doc']) . '/';
                // if (!empty($k['sub_doc'])) {
                //     $file_path .= ($k['sub_doc']) . '/';
                // }
            // $file_path .= ($k['filenames']);

            $file_path = '../../../uploads/ereport/' . $k['serial_no'] . '/' . $k['main_doc'] . '/';
            if (!empty($k['sub_doc'])) {
                $sub_doc_path = $file_path . $k['sub_doc'] . '/';

                $file_path = $sub_doc_path;
            }
            $file_path .= $k['filenames'];

            $a_id = htmlspecialchars($k['a_id']);
            $id = htmlspecialchars($k['id']);

            echo '<tr style="  ' . $status_bg_color . ' ">';
            echo '<td>' . $c . '</td>';
            echo '<td><span>' . strtoupper(htmlspecialchars($k['approver_status'])) . '</span></td>';
            echo '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            echo '<td>' . htmlspecialchars($k['batch_no']) . '</td>';

            if (file_exists($file_path)) {
                if ($status == 'approved' || $status == 'disapproved') {
                    // echo '<td>' . ($k['filenames']) . '</td>';
                    echo '<td title="'.$k['filenames'].'">' . (strlen($k['filenames']) > 50 ? substr($k['filenames'], 0, 50) . '...' : $k['filenames']) . '</td>';
                } else {
                    echo '<td><a href="../../pages/approver/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . $file_path . '&approver=' . htmlspecialchars($a_id) . '" target="_blank">' . htmlspecialchars($k['filenames']) . '</a></td>';
                }
            } else {
                echo '<td>File not found</td>';
            }

            echo '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            echo '<td>' . date('Y/m/d', strtotime($k['approved_date'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="7" class="text-center">No records found.</td>';
        echo '</tr>';
    }
}
