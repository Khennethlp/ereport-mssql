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
                b.updated_file AS updated_file,
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
                (SELECT id, serial_no, main_doc, sub_doc, file_name, updated_file FROM t_upload_file ) b 
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

           

            // Function to check if a file exists in the specified path
            if (!function_exists('fileExistsInPath')) {
                function fileExistsInPath($base_path, $file_name) {
                    $paths_to_check = [
                        $base_path . 'for approval/' . $file_name,
                        $base_path . 'for checking/' . $file_name,
                        $base_path . $file_name
                    ];
                    
                    foreach ($paths_to_check as $path) {
                        if (file_exists($path)) {
                            return $path;
                        }
                    }
                    
                    return false;
                }
            }
            
            $file_path = '../../../uploads/ereport/' . ($k['serial_no']) . '/';
            $file_path .= ($k['main_doc']) . '/';

            // Check if 'for approval' or 'for checking' folders exist and have files
            $sub_doc_path = !empty($k['sub_doc']) ? $file_path . $k['sub_doc'] . '/' : $file_path;
            $filename = $k['updated_file'] ? $k['updated_file']:$k['file_name'];

            $data .= '<tr style="' . $status_bg_color . ' ">';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td ><span>' . $status_text . '</span></td>';
            $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';
            // $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';

            if ($status_text == 'DISAPPROVED') {
                // Check if 'for approval' or 'for checking' folders exist and have files
                $file_path = fileExistsInPath($sub_doc_path, $k['file_name']);
                
                if ($file_path) {
                    $data .= '<td style="cursor: pointer; color: #ffffff;"><a class="text-warning" href="' . $file_path . '" download>' . $filename . '</a></td>';
                } else {
                    $data .= '<td>File not found</td>';
                }
            } else {
                // If status is not 'DISAPPROVED', just show the filename
                $data .= '<td style="cursor: pointer;">' . $filename . '</td>';
            }

            $data .= '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            $data .= '<td>' . htmlspecialchars($checked_date) . '</td>';
            $data .= '<td>' . htmlspecialchars($k['checker_comment']) . '</td>';
            '<td><span>' . strtoupper(htmlspecialchars($k['approver_status'])) . '</span></td>'; // hidden to table
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
