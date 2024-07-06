<?php
include '../../process/conn.php';

$uploader_name = isset($_POST['uploader_name']) ? $_POST['uploader_name'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
$offset = ($page - 1) * $rowsPerPage;

// $sql = "SELECT DISTINCT a.serial_no AS serial_no, a.uploader_name AS u_name, a.checker_status AS c_status, a.checker_name AS c_name, a.checker_email AS c_email, a.upload_date AS u_date, a.batch_no AS batch_no, b.file_name AS file_name 
//         FROM t_training_record a 
//         RIGHT JOIN (
//             SELECT serial_no AS serial_no, main_doc, sub_doc, file_name 
//             FROM t_upload_file
//         ) b ON a.serial_no = b.serial_no 
//         WHERE a.uploader_name = :uploader_name";

$sql = "SELECT DISTINCT * FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file GROUP BY serial_no) b ON a.serial_no = b.serial_no WHERE checker_status = :status AND uploader_name = :uploader_name";

$conditions = [];
if (!empty($date)) {
    $conditions[] = "DATE(a.upload_date) = :date";
}
if (!empty($status)) {
    $conditions[] = "a.checker_status = :status";
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);
$stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

if (!empty($date)) {
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
}
if (!empty($status)) {
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
}

$stmt->execute();

$c = $offset + 1;
$data = '';

if ($stmt->rowCount() > 0) {
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
        $data .= '<tr>';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td><span>' . htmlspecialchars($k['checker_status']) .'</span></td>';
        $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['uploader_name']) . '</td>';
        $data .= '<td>' . htmlspecialchars($k['upload_date']) . '</td>';
        $data .= '</tr>';
        $c++;
    }
} else {
    $data .= '<tr><td colspan="6">No records found.</td></tr>';
}

// Check if there are more rows beyond the current page
$nextOffset = $offset + $rowsPerPage;
$stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $nextOffset, PDO::PARAM_INT);

if (!empty($date)) {
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
}
if (!empty($status)) {
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
}

$stmt->execute();
$has_more = $stmt->rowCount() > 0;

echo json_encode(['html' => $data, 'has_more' => $has_more]);
?>
