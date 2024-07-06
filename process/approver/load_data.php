<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'approver_table') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $checker_name = isset($_POST['checker_name']) ? $_POST['checker_name'] : '';
    $search_by = isset($_POST['search_by']) ? $_POST['search_by'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT a.series_no, a.main_doc, a.sub_doc, a.filename, a.uploaded_by, a.checked_by, a.created_at, b._status 
            FROM uploads a 
            RIGHT JOIN (SELECT series_no, _status FROM t_status) b 
            ON a.series_no = b.series_no";

    $conditions = [];
    if (!empty($search_by)) {
        $conditions[] = "(a.series_no LIKE :search_by OR a.filename LIKE :search_by)";
    }
    if (!empty($status)) {
        $conditions[] = "b._status = :status";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "a.created_at BETWEEN :date_from AND :date_to";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY a.id ASC LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if (!empty($search_by)) {
        $search_by = "%$search_by%";
        $stmt->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }
    if (!empty($status)) {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    $stmt->execute();
    $c = ($page - 1) * $rowsPerPage;

    $response = ['html' => '', 'has_more' => false];

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll() as $k) {
            $c++;
            $file_path = '../../uploads/' . $k['main_doc'] . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= $k['sub_doc'] . '/';
            }
            $file_path .= $k['series_no'] . '/' . $k['filename'];
            $series_no = $k['series_no'];

            $response['html'] .= '<tr>';
            $response['html'] .= '<td>' . $c . '</td>';
            $response['html'] .= '<td>' . htmlspecialchars($k['series_no']) . '</td>';

            if (file_exists($file_path)) {
                $response['html'] .= '<td><a href="../../pages/approver/file_view.php?series_no=' . $series_no . '&file_path=' . urlencode($file_path) . '&checked_by=' . htmlspecialchars($checker_name) . '" target="_blank">' . htmlspecialchars($k['filename']) . '</a></td>';
            } else {
                $response['html'] .= '<td>File not found</td>';
            }

            $statusClass = '';
            switch ($k['_status']) {
                case 'pending':
                    $statusClass = 'badge-secondary';
                    break;
                case 'checked':
                    $statusClass = 'badge-info';
                    break;
                case 'approved':
                    $statusClass = 'badge-success';
                    break;
                case 'disapproved':
                    $statusClass = 'badge-danger';
                    break;
                default:
                    $statusClass = '';
                    break;
            }
            $response['html'] .= '<td><span class="badge ' . $statusClass . '">' . htmlspecialchars($k['_status']) . '</span></td>';
            $response['html'] .= '<td>' . htmlspecialchars($k['checked_by']) . '</td>';
            $response['html'] .= '<td>' . htmlspecialchars($k['created_at']) . '</td>';
            $response['html'] .= '</tr>';
        }
    }

    // Check if more data is available
    $sql_count = "SELECT COUNT(*) FROM uploads a 
                  LEFT JOIN (SELECT series_no, _status FROM t_status) b 
                  ON a.series_no = b.series_no";
    if (!empty($conditions)) {
        $sql_count .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt_count = $conn->prepare($sql_count);
    if (!empty($search_by)) {
        $stmt_count->bindParam(':search_by', $search_by, PDO::PARAM_STR);
    }
    if (!empty($status)) {
        $stmt_count->bindParam(':status', $status, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt_count->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt_count->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }
    $stmt_count->execute();
    $total_rows = $stmt_count->fetchColumn();

    if (($page * $rowsPerPage) < $total_rows) {
        $response['has_more'] = true;
    }

    echo json_encode($response);
}
?>
