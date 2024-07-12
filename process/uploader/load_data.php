<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_data') {

    $uploader_name = isset($_POST['uploader_name']) ? $_POST['uploader_name'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    // $sql = "SELECT DISTINCT 
    // a.id as id,
    // a.checker_status AS checker_status, 
    // a.serial_no AS serial_no,
    //  a.batch_no AS batch_no, 
    //  a.uploader_name AS uploader_name, 
    //  a.upload_date AS upload_date, 
    //  b.serial_no, 
    //  b.main_doc AS main_doc, 
    //  b.sub_doc AS sub_doc, 
    //  b.file_name AS file_name 
    //  FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no WHERE checker_status = :status AND a.uploader_name =:uploader_name";

    $sql = "SELECT * FROM t_training_record a RIGHT JOIN (SELECT serial_no, main_doc, sub_doc, file_name FROM t_upload_file GROUP BY serial_no) b ON a.serial_no = b.serial_no WHERE checker_status = :status AND uploader_name = :uploader_name ";

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

    $sql .= " GROUP BY a.serial_no ORDER BY a.id ASC LIMIT :limit OFFSET :offset";

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
            $data .= '<tr style="cursor:pointer;" data-toggle="modal" data-target="#view_upload" onclick="get_uploads_details(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '~!~' . $k['approver_status'] . '&quot;)">';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td><span>' . strtoupper(htmlspecialchars($k['checker_status'])) . '</span></td>';
            $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            // $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            // $data .= '<td>' . htmlspecialchars($k['upload_date']) . '</td>';
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
}

if ($method == 'uploads_modal_table') {

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $serial_no = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $sql = "SELECT 
                a.id AS id, 
                a.serial_no AS serial_no, 
                a.main_doc AS main_doc, 
                a.sub_doc AS sub_doc, 
                a.file_name AS file_name, 
                b.checker_status AS c_status, 
                b.approver_status AS a_status, 
                b.checker_name AS c_name, 
                b.approver_name AS a_name, 
                b.checker_comment AS c_comment,
                b.approver_comment AS a_comment 
            FROM t_upload_file a 
            LEFT JOIN t_training_record b 
            ON a.serial_no = b.serial_no AND a.id = b.id 
            WHERE a.serial_no = :serial_no AND (b.checker_status = :status OR b.approver_status = :status)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

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
            $c_status = htmlspecialchars($k['c_status']);
            $a_name = htmlspecialchars($k['a_name']);
            $a_status = htmlspecialchars($k['a_status']);
            $id = htmlspecialchars($k['id']);

            echo '<tr>';
            echo '<td>' . $c . '</td>';

            if (file_exists($file_path)) {
                echo '<td><p href="#">' . htmlspecialchars($k['file_name']) . '</p></td>';
            } else {
                echo '<td>File not found</td>';
            }
            
            $checker_stats = ($c_status == 'Pending') ? 'For Checking':'For Approval';
            $approver_status = ($a_status == 'Pending') ? 'For Checking':'Approved';

            if ($k['a_status'] == 'disapproved') {
                echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_upload" onclick="get_disapprovedDetails(&quot; ' . $k['id'] . '~!~' .  $k['serial_no'] . '~!~' . $k['c_comment'] . '&quot;)"><i class="fas fa-ellipsis-h"></i></td>';
            } 
            else{
                echo '<td>'.$checker_stats.'</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No records found.</td></tr>';
    }
}
?>
