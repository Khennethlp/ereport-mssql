<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_viewer_data') {
    // Retrieve search parameters
    $search_by_serial = isset($_POST['search_by_serial']) ? $_POST['search_by_serial'] : '';
    $search_by_batch = isset($_POST['search_by_batch']) ? $_POST['search_by_batch'] : '';
    $search_by_group = isset($_POST['search_by_group']) ? $_POST['search_by_group'] : '';
    $search_by_training = isset($_POST['search_by_training']) ? $_POST['search_by_training'] : '';
    $search_by_filename = isset($_POST['search_by_filename']) ? $_POST['search_by_filename'] : '';
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

    // Pagination parameters
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 20;
    $offset = ($page - 1) * $rowsPerPage;

    // Base SQL query
    $sql = "SELECT a.*, b.* 
            FROM t_training_record a 
            LEFT JOIN t_upload_file b ON a.serial_no = b.serial_no 
            WHERE a.approver_status = 'APPROVED'";

    $conditions = [];
    // $params = [];

    // Build conditions based on provided search parameters
    if (!empty($search_by_serial)) {
        $conditions[] = "a.serial_no = :search_by_serial";
        // $params[':search_by_serial'] = "%$search_by_serial%";
    }
    if (!empty($search_by_batch)) {
        $conditions[] = "  a.batch_no = :search_by_batch";
        // $params[':search_by_batch'] = "%$search_by_batch%";
    }
    if (!empty($search_by_group)) {
        $conditions[] = " a.group_no = :search_by_group";
        // $params[':search_by_group'] = "%$search_by_group%";
    }
    if (!empty($search_by_training)) {
        $conditions[] = " a.training_group = :search_by_training";
        // $params[':search_by_training'] = "%$search_by_training%";
    }
    if (!empty($search_by_filename)) {
        $conditions[] = " b.file_name = :search_by_filename";
        // $params[':search_by_filename'] = "%$search_by_filename%";
    }
    if (!empty($date_from) && !empty($date_to)) {
        $conditions[] = " b.approved_date BETWEEN :date_from AND :date_to";
        // $params[':date_from'] = $date_from;
        // $params[':date_to'] = $date_to;
    }
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    // Append conditions to the base SQL query
    // if (!empty($conditions)) {
    //     $sql .= " AND " . implode(" AND ", $conditions);
    // }

    // Add LIMIT and OFFSET for pagination
    $sql .= " LIMIT :limit OFFSET :offset";

    // Prepare and execute the main SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Bind parameters
    // foreach ($params as $param => $value) {
    //     $stmt->bindValue($param, $value, PDO::PARAM_STR);
    // }

    if (!empty($search_by_serial)) {
        $stmt->bindParam(':search_by_serial', $search_by_serial, PDO::PARAM_STR);
    }
    if (!empty($search_by_batch)) {
        $stmt->bindParam(':search_by_batch', $search_by_batch, PDO::PARAM_STR);
    }
    if (!empty($search_by_group)) {
        $stmt->bindParam(':search_by_group', $search_by_group, PDO::PARAM_STR);
    }
    if (!empty($search_by_training)) {
        $stmt->bindParam(':search_by_training', $search_by_training, PDO::PARAM_STR);
    }
    if (!empty($search_by_filename)) {
        $stmt->bindParam(':search_by_filename', $search_by_filename, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    $stmt->execute();

    $c = $offset + 1;
    $data = '';

    // Process fetched data
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
        // If no records found, construct appropriate message
        $data .= '<tr>';
        $data .= '<td colspan="8" class="text-center">Nothing found.</td>';
        $data .= '</tr>';
    }

    // Determine if there are more records to load for pagination
    $nextOffset = $offset + $rowsPerPage;
    $sql_more = "SELECT 1 
                 FROM t_training_record a 
                 LEFT JOIN t_upload_file b ON a.serial_no = b.serial_no 
                 WHERE a.approver_status = 'APPROVED'";

    // Add conditions for more records check
    if (!empty($conditions)) {
        $sql_more .= " AND " . implode(" AND ", $conditions);
    }

    $sql_more .= " LIMIT 1 OFFSET :next_offset";

    // Prepare and execute the more records check query
    $stmt_more = $conn->prepare($sql_more);
    $stmt_more->bindParam(':next_offset', $nextOffset, PDO::PARAM_INT);
    if (!empty($search_by_serial)) {
        $stmt->bindParam(':search_by_serial', $search_by_serial, PDO::PARAM_STR);
    }
    if (!empty($search_by_batch)) {
        $stmt->bindParam(':search_by_batch', $search_by_batch, PDO::PARAM_STR);
    }
    if (!empty($search_by_group)) {
        $stmt->bindParam(':search_by_group', $search_by_group, PDO::PARAM_STR);
    }
    if (!empty($search_by_training)) {
        $stmt->bindParam(':search_by_training', $search_by_training, PDO::PARAM_STR);
    }
    if (!empty($search_by_filename)) {
        $stmt->bindParam(':search_by_filename', $search_by_filename, PDO::PARAM_STR);
    }
    if (!empty($date_from) && !empty($date_to)) {
        $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
    }

    $stmt_more->execute();
    $has_more = $stmt_more->rowCount() > 0;

    // Return JSON response with data and pagination indicator
    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}

// if ($method == 'load_data') {

//     $search_by_serial = isset($_POST['search_by_serial']) ? $_POST['search_by_serial'] : '';
//     $search_by_batch = isset($_POST['search_by_batch']) ? $_POST['search_by_batch'] : '';
//     $search_by_group = isset($_POST['search_by_group']) ? $_POST['search_by_group'] : '';
//     $search_by_training = isset($_POST['search_by_training']) ? $_POST['search_by_training'] : '';
//     $search_by_filename = isset($_POST['search_by_filename']) ? $_POST['search_by_filename'] : '';
//     $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
//     $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

//     $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
//     $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
//     $offset = ($page - 1) * $rowsPerPage;

//     $sql = "SELECT a.*, b.* 
//             FROM t_training_record a 
//             LEFT JOIN t_upload_file b ON a.serial_no = b.serial_no 
//             WHERE a.approver_status = 'APPROVED'";

//     $conditions = [];


//     if (!empty($date_from) && !empty($date_to)) {
//         $conditions[] = "a.approved_date BETWEEN :date_from AND :date_to";
//     }

//     if (!empty($search_by_serial)) {
//         $conditions[] = "a.batch_no = :search OR a.group_no = :search OR a.serial_no = :search OR a.training_group = :search OR a.checker_name = :search OR a.approver_name = :search";
//     }

//     if (!empty($conditions)) {
//         $sql .= " AND " . implode(" AND ", $conditions);
//     }

//     $sql .= " GROUP BY a.serial_no ORDER BY a.id ASC LIMIT :limit OFFSET :offset";

//     $stmt = $conn->prepare($sql);
//     $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
//     $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

//     if (!empty($date_from) && !empty($date_to)) {
//         $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
//         $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
//     }

//     if (!empty($search_by_serial)) {
//         $stmt->bindParam(':search', $search_by_serial, PDO::PARAM_STR);
//     }

//     $stmt->execute();

//     $c = $offset + 1;
//     $data = '';

//     if ($stmt->rowCount() > 0) {
//         foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
//             $serial_no = htmlspecialchars($k['serial_no']);
//             $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
//             $file_path .= htmlspecialchars($k['main_doc']) . '/';
//             if (!empty($k['sub_doc'])) {
//                 $file_path .= htmlspecialchars($k['sub_doc']) . '/';
//             }
//             $file_path .= htmlspecialchars($k['file_name']);

//             $data .= '<tr>';
//             $data .= '<td>' . $c . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['batch_no']) . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['group_no']) . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['training_group']) . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['file_name']) . '</td>';
//             $data .= '<td>' . htmlspecialchars($k['approved_date']) . '</td>';
//             if (file_exists($file_path)) {
//                 $data .= '<td style="cursor: pointer; ;"><a href="' . $file_path . '" download><i class="fas fa-download text-success"></i></a></td>';
//             } else {
//                 $data .= '<td>File not found</td>';
//             }
//             $data .= '</tr>';
//             $c++;
//         }
//     } else {
//         $data .= '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
//     }

//     // Check if there are more rows beyond the current page
//     $nextOffset = $offset + $rowsPerPage;
//     $stmt->bindParam(':offset', $nextOffset, PDO::PARAM_INT);

//     if (!empty($date_from) && !empty($date_to)) {
//         $stmt->bindParam(':date_from', $date_from, PDO::PARAM_STR);
//         $stmt->bindParam(':date_to', $date_to, PDO::PARAM_STR);
//     }

//     if (!empty($search_by_serial)) {
//         $stmt->bindParam(':search', $search_by_serial, PDO::PARAM_STR);
//     }

//     $stmt->execute();
//     $has_more = $stmt->rowCount() > 0;

//     echo json_encode(['html' => $data, 'has_more' => $has_more]);
// }
