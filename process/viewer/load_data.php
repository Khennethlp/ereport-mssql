<?php
include '../../process/conn.php';

$method = $_POST['method'];

if($method == 'load_viewer_data'){
    $search_by_serial = $_POST['search_by_serial'] ?? '';
    $search_by_batch = $_POST['search_by_batch'] ?? '';
    $search_by_group = $_POST['search_by_group'] ?? '';
    $search_by_training = $_POST['search_by_training'] ?? '';
    $search_by_filename = $_POST['search_by_filename'] ?? '';
    $date_from = $_POST['date_from'] ?? '';
    $date_to = $_POST['date_to'] ?? '';
    
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 20;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT a.*, b.* FROM t_training_record a 
            LEFT JOIN t_upload_file b ON a.serial_no = b.serial_no 
            WHERE approver_status = 'APPROVED'";
    
    $conditions = [];
    $params = [];
    
    // Add conditions based on non-empty inputs
    if (!empty($search_by_serial)) {
        $conditions[] = "a.serial_no LIKE :search_by_serial";
        $params[':search_by_serial'] = "%$search_by_serial%";
    }
    if (!empty($search_by_batch)) {
        $conditions[] = "a.batch_no LIKE :search_by_batch";
        $params[':search_by_batch'] = "%$search_by_batch%";
    }
    if (!empty($search_by_group)) {
        $conditions[] = "a.group_no LIKE :search_by_group";
        $params[':search_by_group'] = "%$search_by_group%";
    }
    if (!empty($search_by_training)) {
        $conditions[] = "a.training_group LIKE :search_by_training";
        $params[':search_by_training'] = "%$search_by_training%";
    }
    if (!empty($search_by_filename)) {
        $conditions[] = "b.file_name LIKE :search_by_filename";
        $params[':search_by_filename'] = "%$search_by_filename%";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "b.approved_date BETWEEN :date_from AND :date_to";
        $params[':date_from'] = $date_from;
        $params[':date_to'] = $date_to;
    }
    
    // Combine all conditions
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " LIMIT :limit OFFSET :offset";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
    // Bind all parameters
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value, PDO::PARAM_STR);
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
            $file_path .= htmlspecialchars($k['file_name']);

            $data .= '<tr>';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['approved_date']) . '</td>';
            if (file_exists($file_path)) {
                $data .= '<td style="cursor: pointer; ;"><a href="' . $file_path . '" download><i class="fas fa-download text-success"></i></a></td>';
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

    $nextOffset = $offset + $rowsPerPage;
    $sql_more = "SELECT 1 
                 FROM t_training_record a 
                 RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b 
                 ON a.serial_no = b.serial_no AND a.id = b.id 
                 WHERE a.approver_status = 'Approved'";

    if (!empty($conditions)) {
        $sql_more .= " AND " . implode(" AND ", $conditions);
    }

    $sql_more .= " LIMIT 1 OFFSET :next_offset";

    $stmt_more = $conn->prepare($sql_more);
    // $stmt_more->bindParam(':approver_name', $approver_name, PDO::PARAM_STR);
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