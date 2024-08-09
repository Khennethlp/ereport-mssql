<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'checker_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $checker_id = isset($_POST['checker_id']) ? $_POST['checker_id'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 50;
    $offset = ($page - 1) * $rowsPerPage;

    $acc_sql = "SELECT emp_id, fullname FROM m_accounts WHERE emp_id = :checker_id";
    $acc_stmt = $conn->prepare($acc_sql);
    $acc_stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
    $acc_stmt->execute();
    $account = $acc_stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT DISTINCT
                a.serial_no AS serial_no, 
                a.id AS id,
                a.uploader_name, 
                a.checker_status, 
                a.checker_id AS c_id, 
                 CASE 
                WHEN a.checker_status = 'Pending' THEN 'Pending'
                WHEN a.checker_status = 'Disapproved' THEN 'Disapproved'
                WHEN a.checker_status = 'Approved' AND a.approver_status = 'Approved' THEN 'Approved'
                    ELSE a.checker_status
                END AS checker_status,
                CASE
                    WHEN a.upload_date != '' AND a.update_upload_date = '' THEN a.upload_date
                    WHEN a.update_upload_date != '' THEN a.update_upload_date
                END AS checked_date,
                a.checker_name, 
                a.checker_email, 
                a.upload_date, 
                a.update_upload_date, 
                a.batch_no AS batch_no, 
                a.group_no AS group_no, 
                a.upload_month AS upload_month, 
                a.upload_year AS upload_year, 
                a.training_group AS training_group, 
                b.serial_no AS b_serial_no, 
                b.main_doc, 
                b.sub_doc, 
                b.file_name AS file_name
            FROM t_training_record a RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no AND a.id=b.id WHERE a.checker_id = :checker_id  ";

    if (!empty($status)) {
        $sql .=  " AND (
                       (a.checker_status = 'Pending' AND :status = 'Pending') OR
                       (a.checker_status = 'Disapproved' AND :status = 'Disapproved') OR
                       (a.checker_status = 'Approved' AND a.approver_status = 'Approved' AND :status = 'Approved')
                    )";
    }
    // (a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' AND :status = 'Disapproved')
    // WHEN a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' THEN 'Disapproved'
    if (!empty($search_by)) {
        $sql .= " AND a.serial_no LIKE :search_by OR a.batch_no LIKE :search_by OR a.group_no LIKE :search_by OR a.training_group LIKE :search_by OR a.upload_month LIKE :search_by OR b.file_name LIKE :search_by";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $sql .= " AND a.upload_date BETWEEN :date_from AND :date_to";
    }

    $sql .= "   ORDER BY id ASC LIMIT :limit_plus_one OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':checker_id', $checker_id, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if (!empty($search_by)) {
        $search_by = "%$search_by%";
        $stmt->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($rows) > $rowsPerPage;
    if ($has_more) {
        array_pop($rows); // Remove the extra row used for the check
    }

    $data = '';
    $c = $offset + 1;

    // if ($stmt->rowCount() > 0) {
        // while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach ($rows as $k) {
            $serial_no = htmlspecialchars($k['b_serial_no']);
            $status_text = strtoupper(htmlspecialchars($k['checker_status']));
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

            // $file_path = '../../../uploads/ereport/' . $k['serial_no'] . '/';
            // $file_path .= $k['main_doc'] . '/';
            // if (!empty($k['sub_doc'])) {
                //  $file_path .= $k['sub_doc'] . '/';
                // }
                // $file_path .= $k['file_name'];
            // Check if 'sub_doc' is provided and adjust the path
           
            $file_path = '../../../uploads/ereport/' . $k['serial_no'] . '/' . $k['main_doc'] . '/';
            if (!empty($k['sub_doc'])) {
                $sub_doc_path = $file_path . $k['sub_doc'] . '/';

                $file_path = $sub_doc_path;
            }

            $file_path .= !empty($k['updated_file']) ? $k['updated_file'] : $k['file_name'];

            $c_id = htmlspecialchars($k['c_id']);
            $id = htmlspecialchars($k['id']);

            $data .= '<tr style="' . $status_bg_color . '">';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td><span>' . strtoupper(htmlspecialchars($k['checker_status'])) . '</span></td>';
            $data .= '<td>' . htmlspecialchars($k['b_serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['upload_month']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['upload_year']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';

            
            if (file_exists($file_path)) {
                if ($status == 'approved' || $status == 'disapproved') {
                    // $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';
                    $data .= '<td title="' . $k['file_name'] . '">' . (strlen($k['file_name']) > 50 ? substr($k['file_name'], 0, 50) . '...' : $k['file_name']) . '</td>';
                } else {
                    $data .= '<td><a href="../../pages/checker/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . $file_path . '&checker=' . htmlspecialchars($c_id) . '" target="_blank">' . $k['file_name'] . '</a></td>';
                }
            } else {
                $data .= '<td>File not found</td>';
            }

            $data .= '<td>' . htmlspecialchars($k['uploader_name']) . '</td>';
            $data .= '<td>' . date('Y/m/d', strtotime($k['checked_date'])) . '</td>';
            $data .= '</tr>';
            $c++;
        }
    

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}
