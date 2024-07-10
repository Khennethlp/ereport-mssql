<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'approver_history_checker_table') {

    $approver_name = isset($_POST['approver_name']) ? $_POST['approver_name'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 20;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT DISTINCT a.serial_no AS serial_no, a.id AS id, a.checker_status as checker_status, a.approver_name as approver_name, a.approved_date as approved_date, b.serial_no AS b_serial_no, b.main_doc, b.sub_doc, b.file_name AS filenames 
            FROM t_training_record a 
            RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no AND a.id = b.id 
            WHERE a.approver_status = 'Approved' AND a.approver_name = :approver_name";

    $conditions = [];
    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "DATE(a.approved_date) BETWEEN :date_from AND :date_to";
    }
    if (!empty($search_by)) {
        $conditions[] = "a.serial_no = :search_by";
    }

    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':approver_name', $approver_name, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if (!empty($date_from)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
    }
    if (!empty($date_to)) {
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }
    if (!empty($search_by)) {
        $stmt->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }

    $stmt->execute();

    $c = $offset + 1;
    $data = '';

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $serial_no = htmlspecialchars($k['serial_no']);
            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= htmlspecialchars($k['filenames']);

            $data .= '<tr>';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['filenames']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['approved_date']) . '</td>';
            if (file_exists($file_path)) {
                $data .= '<td style="cursor: pointer;"><a href="' . $file_path . '" download><i class="fas fa-download"></i></a></td>';
            } else {
                $data .= '<td>File not found</td>';
            }
            $data .= '</tr>';
            $c++;
        }
    } else {
        $data .= '<tr>';
        $data .= '<td colspan="5" class="text-center">Nothing found.</td>';
        $data .= '</tr>';
    }

    // Check if there are more rows beyond the current page
    $nextOffset = $offset + $rowsPerPage;
    $sql_more = "SELECT 1 
                 FROM t_training_record a 
                 RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b 
                 ON a.serial_no = b.serial_no AND a.id = b.id 
                 WHERE a.approver_status = 'Approved' AND a.approver_name = :approver_name";

    if (!empty($conditions)) {
        $sql_more .= " AND " . implode(" AND ", $conditions);
    }

    $sql_more .= " LIMIT 1 OFFSET :next_offset";

    $stmt_more = $conn->prepare($sql_more);
    $stmt_more->bindParam(':approver_name', $approver_name, PDO::PARAM_STR);
    $stmt_more->bindParam(':next_offset', $nextOffset, PDO::PARAM_INT);

    if (!empty($date_from)) {
        $stmt_more->bindParam(':date_from', $date_from, PDO::PARAM_STR);
    }
    if (!empty($date_to)) {
        $stmt_more->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }
    if (!empty($search_by)) {
        $stmt_more->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }

    $stmt_more->execute();
    $has_more = $stmt_more->rowCount() > 0;

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}
?>
