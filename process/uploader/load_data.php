<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_data') {

    $uploader_name = isset($_POST['uploader_name']) ? $_POST['uploader_name'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT 
                a.*, 
                b.main_doc, 
                b.sub_doc, 
                b.file_name,
                CASE 
                    WHEN a.checker_status = 'Pending' THEN 'FOR CHECKING' 
                    WHEN a.checker_status = 'Disapproved' THEN 'Disapproved'
                    WHEN a.checker_status = 'Approved' AND a.approver_status = 'Pending' THEN 'For Approval'
                    WHEN a.checker_status = 'Disapproved' AND a.approver_status = 'Pending' THEN 'Disapproved'
                    WHEN a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' THEN 'Disapproved'
                    WHEN a.checker_status = 'Disapproved' AND a.approver_status = 'Disapproved' THEN 'Disapproved'
                    WHEN a.checker_status = 'Approved' AND a.approver_status = 'Approved' THEN 'Approved'
                END AS global_status, 
                CASE 
                    WHEN a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' THEN a.approver_name
                    -- WHEN a.checker_status = 'Disapproved' AND a.approver_status = 'Disapproved' THEN a.checker_name
                    WHEN a.checker_status = 'Disapproved' THEN a.checker_name
                    -- ELSE 'a.checker_name'
                END AS disapprover_name
            FROM 
                t_training_record a 
            RIGHT JOIN 
                (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file ) b 
            ON 
                a.serial_no = b.serial_no AND a.id=b.id
            WHERE 
                uploader_name = :uploader_name";

    $conditions = [];
    if (!empty($status)) {
        $conditions[] = "(
            (a.checker_status = 'Pending' AND :status = 'Pending') OR
            (a.checker_status = 'Disapproved' AND :status = 'Disapproved') OR
            (a.checker_status = 'Approved' AND a.approver_status = 'Pending' AND :status = 'Pending') OR
            (a.checker_status = 'Approved' AND a.approver_status = 'Approved' AND :status = 'Approved') OR
            (a.checker_status = 'Disapproved' AND a.approver_status = 'Disapproved' AND :status = 'Disapproved') OR
            (a.checker_status = 'Disapproved' AND a.approver_status = 'Pending' AND :status = 'Disapproved') OR
            (a.checker_status = 'Approved' AND a.approver_status = 'Disapproved' AND :status = 'Disapproved')
        )";
    }

    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = "a.upload_date BETWEEN :date_from AND :date_to";
    }

    if (!empty($search)) {
        $conditions[] = "a.batch_no = :search OR a.group_no = :search OR a.serial_no = :search OR a.training_group = :search OR a.checker_name = :search OR a.approver_name = :search";
    }

    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY a.id ASC LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if (!empty($status)) {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }

    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    if (!empty($search)) {
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    }

    $stmt->execute();

    $c = $offset + 1;
    $data = '';

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $status_text = strtoupper(htmlspecialchars($k['global_status']));
            $checked_date = !empty($k['checked_date']) ? date('Y/m/d', strtotime($k['checked_date'])) : '';
            $approved_date = !empty($k['approved_date']) ? date('Y/m/d', strtotime($k['approved_date'])) : '';

            // Set background color based on status
            $status_bg_color = '';
            // $status_badge_color = '';
            switch ($status_text) {
                case 'APPROVED':
                    $status_bg_color = 'background-color: var(--success); color: #fff;';
                    // $status_badge_color = 'badge-success';
                    break;
                case 'FOR CHECKING': //PENDING
                    $status_bg_color = 'background-color: var(--warning);';
                    // $status_badge_color = 'badge-secondary';
                    break;
                case 'DISAPPROVED':
                    $status_bg_color = 'background-color: var(--danger); color: #fff;';
                    // $status_badge_color = 'badge-danger';
                    break;
                case 'FOR APPROVAL':
                    $status_bg_color = 'background-color: var(--primary); color: #fff;';
                    // $status_badge_color = 'badge-danger';
                    break;
                default:
                    $status_bg_color = 'background-color: var(--secondary);';
                    // $status_badge_color = 'badge-primary';
                    break;
            }

            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= htmlspecialchars($k['file_name']);
            $filename = htmlspecialchars($k['file_name']);

            $data .= '<tr style="cursor:pointer;' . $status_bg_color . ' " data-toggle="" data-target="#view_upload" onclick="get_uploads_details(&quot;' . $k['id'] . '~!~' . $k['serial_no'] . '~!~' . $status_text . '&quot;)">';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td ><span>' . $status_text . '</span></td>';
            $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';
            // $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';

            if ($status_text == 'DISAPPROVED'){
                if (file_exists($file_path)) {
                    $data .= '<td style="cursor: pointer; color: #ffffff;"><a class="text-warning" href="' . $file_path . '" download>' . $filename . '</a></td>';
                } else {
                    $data .= '<td>File not found</td>';
                }
            }else{
                $data .= '<td style="cur sor: pointer; ;">' . $filename . '</td>';

            }
            $data .= '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            $data .= '<td>' . htmlspecialchars($checked_date) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['checker_comment']) . '</td>';
            $data .= '<td><span>' . strtoupper(htmlspecialchars($k['approver_status'])) . '</span></td>';
            $data .= '<td>' . htmlspecialchars($k['approver_name']) . '</td>';
            $data .= '<td>' . htmlspecialchars($approved_date) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['approver_comment']) . '</td>';

            // Display disapprover name if approver status is 'Disapproved'
            if ($status_text == 'DISAPPROVED') {
                $data .= '<td>' . htmlspecialchars($k['disapprover_name']) . '</td>';
            } else {
                $data .= '<td></td>';
            }

            $data .= '</tr>';
            $c++;
        }
    } else {
        $data .= '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }

    // Check if there are more rows beyond the current page
    $nextOffset = $offset + $rowsPerPage;
    $stmt->bindParam(':offset', $nextOffset, PDO::PARAM_INT);

    if (!empty($status)) {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }

    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    if (!empty($search)) {
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
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
            WHERE a.serial_no = :serial_no AND b.checker_status = :status";

    // Modify the query based on the status filter
    // if ($status === 'PENDING') {
    //     $sql .= " AND b.checker_status = :status";
    // } elseif ($status === 'APPROVED' || $status === 'DISAPPROVED') {
    //     $sql .= " AND b.approver_status = :status";
    // }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);
    // $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    // Bind the status parameter if necessary
    if ($status === 'APPROVED' || $status === 'DISAPPROVED' || $status === 'PENDING') {
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    }

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

            // $checker_stats = ($c_status == 'Pending') ? 'For Checking':'For Approval';
            // $approver_status = ($a_status == 'Pending') ? 'For Checking':'Approved';

            if ($k['a_status'] == 'DISAPPROVED') {
                echo '<td style="cursor: pointer;" data-toggle="modal" data-target="#update_upload" onclick="get_disapprovedDetails(&quot; ' . $k['id'] . '~!~' .  $k['serial_no'] . '~!~' . $k['a_comment'] . '&quot;)"><i class="fas fa-ellipsis-h"></i></td>';
            } else {
                echo '<td></td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No records found.</td></tr>';
    }
}
