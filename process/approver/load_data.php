<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'approver_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $approver_id = isset($_POST['approver_id']) ? $_POST['approver_id'] : '';
    $search_by_serialNo = isset($_POST['search_by_serialNo']) ? $_POST['search_by_serialNo'] : '';
    $search_by_batchNo = isset($_POST['search_by_batchNo']) ? $_POST['search_by_batchNo'] : '';
    $search_by_groupNo = isset($_POST['search_by_groupNo']) ? $_POST['search_by_groupNo'] : '';
    $search_by_tgroup = isset($_POST['search_by_tgroup']) ? $_POST['search_by_tgroup'] : '';
    $search_by_docs = isset($_POST['search_by_docs']) ? $_POST['search_by_docs'] : '';
    $search_by_filename = isset($_POST['search_by_filename']) ? $_POST['search_by_filename'] : '';
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 50;
    $offset = ($page - 1) * $rowsPerPage;

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
            WHEN a.approver_status = 'Pending' THEN a.upload_date
            WHEN a.checker_status = 'Approved' AND a.approver_status = 'Pending' THEN a.checked_date
            WHEN a.approver_status = 'Approved' THEN a.approved_date
            WHEN a.approver_status = 'Disapproved' THEN a.approved_date
        END AS approved_date,
            CASE
                    WHEN a.upload_date != '' AND a.update_upload_date = '' THEN a.upload_date
                    WHEN a.update_upload_date != '' THEN a.update_upload_date
                END AS upload_date,
        CASE
            WHEN a.checker_status = 'Approved' AND a.approver_status = 'Pending' THEN a.checker_name
            WHEN a.checker_status = 'Approved' AND a.approver_status = 'Approved' THEN a.checker_name
            WHEN a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' THEN a.checker_name
        END AS checker_name,
        CASE
            WHEN a.approver_status = 'Pending' THEN a.approver_name
            WHEN a.approver_status = 'Approved' THEN a.approver_name
            WHEN a.approver_status = 'Disapproved' THEN a.approver_name
        END AS approver_name,
        CASE
            WHEN a.approver_status = 'Pending' THEN a.uploader_name
            WHEN a.approver_status = 'Approved' THEN a.uploader_name
            WHEN a.approver_status = 'Disapproved' THEN a.uploader_name
        END AS uploader_name,
    a.approver_id AS a_id, 
    a.approver_email AS approver_email, 
    a.group_no AS group_no, 
    a.upload_month AS upload_month, 
    a.upload_year AS upload_year, 
    a.training_group AS training_group, 
    b.serial_no, 
    b.main_doc AS main_doc,
    b.sub_doc AS sub_doc, 
    b.updated_file AS updated_file, 
    b.file_name AS filenames 
    FROM t_training_record a 
    RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, updated_file, file_name FROM t_upload_file) b 
    ON a.serial_no = b.serial_no AND a.id=b.id 
    WHERE a.approver_id = :approver_id AND a.approver_status = '{$status}' ";

    if (!empty($status)) {
        $sql .=  " AND (
                   (a.approver_status = 'Pending' AND '{$status}' = 'Pending') OR
                   (a.approver_status = 'Disapproved' AND '{$status}' = 'Disapproved') OR
                   (a.approver_status = 'Approved' AND '{$status}' = 'Approved')
                )";
    }
    if (!empty($search_by_serialNo)) {
        $sql .= " AND a.serial_no LIKE :search_by_serialNo";
    }
    if (!empty($search_by_batchNo)) {
        $sql .= " AND a.batch_no LIKE :search_by_batchNo";
    }
    if (!empty($search_by_groupNo)) {
        $sql .= " AND a.group_no LIKE :search_by_groupNo";
    }
    if (!empty($search_by_tgroup)) {
        $sql .= " AND a.training_group LIKE :search_by_tGroup";
    }
    if (!empty($search_by_filename)) {
        $sql .= " AND b.file_name LIKE :search_by_filename";
    }
    if (!empty($search_by_docs)) {
        $sql .= " AND b.main_doc LIKE :search_by_docs";
    }
    if (!empty($year)) {
        $sql .= " AND a.upload_year = :year";
    }
    if (!empty($month)) {
        $sql .= " AND a.upload_month LIKE :month";
    }
    $sql .= " ORDER BY id ASC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':approver_id', $approver_id, PDO::PARAM_STR);

    if (!empty($search_by_serialNo)) {
        $search_by_serialNo = "$search_by_serialNo%";
        $stmt->bindParam(':search_by_serialNo', $search_by_serialNo, PDO::PARAM_STR);
    }
    if (!empty($search_by_batchNo)) {
        $search_by_batchNo = "$search_by_batchNo%";
        $stmt->bindParam(':search_by_batchNo', $search_by_batchNo, PDO::PARAM_STR);
    }
    if (!empty($search_by_groupNo)) {
        $search_by_groupNo = "$search_by_groupNo%";
        $stmt->bindParam(':search_by_groupNo', $search_by_groupNo, PDO::PARAM_STR);
    }
    if (!empty($search_by_tgroup)) {
        $search_by_tgroup = "$search_by_tgroup%";
        $stmt->bindParam(':search_by_tGroup', $search_by_tgroup, PDO::PARAM_STR);
    }
    if (!empty($search_by_filename)) {
        $search_by_filename = "$search_by_filename%";
        $stmt->bindParam(':search_by_filename', $search_by_filename, PDO::PARAM_STR);
    }
    if (!empty($search_by_docs)) {
        $search_by_docs = "%$search_by_docs%";
        $stmt->bindParam(':search_by_docs', $search_by_docs, PDO::PARAM_STR);
    }
    if (!empty($year)) {
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    }
    if (!empty($month)) {
        $month = "$month%";
        $stmt->bindParam(':month', $month, PDO::PARAM_STR);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($rows) > $rowsPerPage;
    if ($has_more) {
        array_pop($rows);
    }

    $data = '';
    $c = $offset + 1;
    foreach ($rows as $k) {
        $serial_no = htmlspecialchars($k['serial_no']);
        $status_text = strtoupper(htmlspecialchars($k['approver_status']));

        $status_bg_color = '';
        switch ($status_text) {
            case 'APPROVED':
                $status_bg_color = 'background-color: var(--success); color: #fff;';
                break;
            case 'PENDING':
                $status_bg_color = 'background-color: var(--warning);';
                break;
            case 'DISAPPROVED':
                $status_bg_color = 'background-color: var(--danger); color: #fff;';
                break;
            default:
                $status_bg_color = 'background-color: var(--primary);';
                break;
        }

        $file_path = '../../../uploads/ereport/' . $k['serial_no'] . '/' . $k['main_doc'] . '/';
        if (!empty($k['sub_doc'])) {
            $sub_doc_path = $file_path . $k['sub_doc'] . '/';

            $file_path = $sub_doc_path;
        }
        $file_path .= $k['filenames'];

        $a_id = htmlspecialchars($k['a_id']);
        $id = htmlspecialchars($k['id']);

        $data .= '<tr style="  ' . $status_bg_color . ' ">';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td><span>' . strtoupper(htmlspecialchars($k['approver_status'])) . '</span></td>';
        $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['upload_month']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['upload_year']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['main_doc']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';

        if (file_exists($file_path)) {
            if ($status == 'approved' || $status == 'disapproved') {
                $data .= '<td title="' . $k['filenames'] . '">' . (strlen($k['filenames']) > 50 ? substr($k['filenames'], 0, 50) . '...' : $k['filenames']) . '</td>';
            } else {
                $data .= '<td><a href="../../pages/approver/file_view.php?id=' . $id . '&serial_no=' . $serial_no . '&file_path=' . $file_path . '&approver=' . htmlspecialchars($a_id) . '" target="_blank">' . htmlspecialchars($k['filenames']) . '</a></td>';
            }
        } else {
            $data .= '<td>File not found</td>';
        }

        $data .= '<td>' . htmlspecialchars($k['uploader_name']) . '</td>';
        $data .= '<td>' . date('Y/m/d', strtotime($k['upload_date'])) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
        $data .= '<td>' . date('Y/m/d', strtotime($k['checked_date'])) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['approver_name']) . '</td>';
        $data .= '<td>' . date('Y/m/d', strtotime($k['approved_date'])) . '</td>';
        $data .= '</tr>';
        $c++;
    }
    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No data found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}
