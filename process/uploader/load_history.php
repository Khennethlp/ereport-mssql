<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_revision_data') {

    $uploader_name = isset($_POST['uploader_name']) ? $_POST['uploader_name'] : '';
    $serialNo = isset($_POST['serialNo']) ? $_POST['serialNo'] : '';
    $batchNo = isset($_POST['batchNo']) ? $_POST['batchNo'] : '';
    $groupNo = isset($_POST['groupNo']) ? $_POST['groupNo'] : '';
    $training_group = isset($_POST['training_group']) ? $_POST['training_group'] : '';
    $dateFrom = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '';
    $dateTo = isset($_POST['dateTo']) ? $_POST['dateTo'] : '';

    echo '<thead>
    <tr>
    <th>#</th>
    <th>Serial No</th>
    <th>Batch No</th>
    <th>Group No</th>
    <th>Training Group</th>
    <th>Revision Count</th>
    </tr>
</thead>';

    $revisions_sql = "
        SELECT b.id, a.serial_no, a.file_name, b.count, b.revised_by AS revised_by, 
               b.revision_date AS revision_date, c.batch_no AS batch_no, 
               c.group_no AS group_no, c.training_group AS training_group 
        FROM t_upload_file a 
        INNER JOIN (
            SELECT id, COUNT(serial_no) AS count, serial_no, revision_date, revised_by 
            FROM file_revisions 
            GROUP BY serial_no
        ) b ON a.serial_no = b.serial_no 
        INNER JOIN t_training_record c ON b.serial_no = c.serial_no 
        WHERE c.uploader_name = :uploader_name";

    if (!empty($serialNo)) {
        $revisions_sql .= " AND a.serial_no LIKE :serial_no";
    }
    if (!empty($batchNo)) {
        $revisions_sql .= " AND c.batch_no LIKE :batch_no";
    }
    if (!empty($groupNo)) {
        $revisions_sql .= " AND c.group_no LIKE :group_no";
    }
    if (!empty($training_group)) {
        $revisions_sql .= " AND c.training_group LIKE :training_group";
    }
    if (!empty($dateFrom) && !empty($dateTo)) {
        $revisions_sql .= " AND b.revision_date BETWEEN :dateFrom AND :dateTo";
    }

    $revisions_sql .= " GROUP BY a.serial_no ORDER BY a.id DESC";

    $stmt = $conn->prepare($revisions_sql);
    $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);

    if (!empty($serialNo)) {
        $serialNo = "%$serialNo%";
        $stmt->bindParam(':serial_no', $serialNo, PDO::PARAM_STR);
    }
    if (!empty($batchNo)) {
        $batchNo = "%$batchNo%";
        $stmt->bindParam(':batch_no', $batchNo, PDO::PARAM_STR);
    }
    if (!empty($groupNo)) {
        $groupNo = "%$groupNo%";
        $stmt->bindParam(':group_no', $groupNo, PDO::PARAM_STR);
    }
    if (!empty($training_group)) {
        $training_group = "$training_group%";
        $stmt->bindParam(':training_group', $training_group, PDO::PARAM_STR);
    }
    if (!empty($dateFrom) && !empty($dateTo)) {
        $stmt->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
        $stmt->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
    }

    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr onclick="load_t2(&quot;' . $k['serial_no'] . '&quot;);">';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['serial_no'] . '</td>';
            echo '<td>' . $k['batch_no'] . '</td>';
            echo '<td>' . $k['group_no'] . '</td>';
            echo '<td>' . $k['training_group'] . '</td>';
            echo '<td>' . $k['count'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }
}

// if ($method == 'load_revision_data2') {
//     $uploader_name = isset($_POST['uploader_name']) ? $_POST['uploader_name'] : '';
//     $serialNo = isset($_POST['serialNo']) ? $_POST['serialNo'] : '';
//     $batchNo = isset($_POST['batchNo']) ? $_POST['batchNo'] : '';
//     $groupNo = isset($_POST['groupNo']) ? $_POST['groupNo'] : '';
//     $dateFrom = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '';
//     $dateTo = isset($_POST['dateTo']) ? $_POST['dateTo'] : '';

//     echo '<thead>
//         <tr>
//         <th>#</th>
//         <th>Serial No</th>
//         <th>Batch No</th>
//         <th>Group No</th>
//         <th>Training Group</th>
//         <th>Revision Count</th>
//         </tr>
//     </thead>';

//     $revisions_sql = "
//     SELECT b.id, a.serial_no, a.file_name, b.count, b.revised_by AS revised_by, 
//            b.revision_date AS revision_date, c.batch_no AS batch_no, 
//            c.group_no AS group_no, c.training_group AS training_group 
//     FROM t_upload_file a 
//     INNER JOIN (
//         SELECT id, COUNT(serial_no) AS count, serial_no, revision_date, revised_by 
//         FROM file_revisions 
//         GROUP BY serial_no
//     ) b ON a.serial_no = b.serial_no 
//     INNER JOIN t_training_record c ON b.serial_no = c.serial_no 
//     WHERE c.uploader_name = :uploader_name";

//     if (!empty($serialNo)) {
//         $revisions_sql .= " AND a.serial_no LIKE :serial_no";
//     }
//     if (!empty($batchNo)) {
//         $revisions_sql .= " AND c.batch_no LIKE :batch_no";
//     }
//     if (!empty($groupNo)) {
//         $revisions_sql .= " AND c.group_no LIKE :group_no";
//     }
//     if (!empty($dateFrom) && !empty($dateTo)) {
//         $revisions_sql .= " AND b.revision_date BETWEEN :dateFrom AND :dateTo";
//     }

//     $revisions_sql .= " GROUP BY a.serial_no ORDER BY a.id DESC";

//     $stmt = $conn->prepare($revisions_sql);
//     $stmt->bindParam(':uploader_name', $uploader_name, PDO::PARAM_STR);

//     if (!empty($serialNo)) {
//         $serialNo = "%$serialNo%";
//         $stmt->bindParam(':serial_no', $serialNo, PDO::PARAM_STR);
//     }
//     if (!empty($batchNo)) {
//         $batchNo = "%$batchNo%";
//         $stmt->bindParam(':batch_no', $batchNo, PDO::PARAM_STR);
//     }
//     if (!empty($groupNo)) {
//         $groupNo = "%$groupNo%";
//         $stmt->bindParam(':group_no', $groupNo, PDO::PARAM_STR);
//     }
//     if (!empty($dateFrom) && !empty($dateTo)) {
//         $stmt->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
//         $stmt->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
//     }

//     $stmt->execute();
//     $c = 0;

//     if ($stmt->rowCount() > 0) {
//         foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
//             $c++;
//             echo '<tr onclick="load_t2(&quot;' . $k['serial_no'] . '&quot;);">';
//             echo '<td>' . $c . '</td>';
//             echo '<td>' . $k['serial_no'] . '</td>';
//             echo '<td>' . $k['batch_no'] . '</td>';
//             echo '<td>' . $k['group_no'] . '</td>';
//             echo '<td>' . $k['training_group'] . '</td>';
//             echo '<td>' . $k['count'] . '</td>';
//             echo '</tr>';
//         }
//     } else {
//         echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
//     }
// }


if ($method == 'load_t_t2') {
    $serial_no = $_POST['serial_no'];

    echo '<thead>
            <tr>
                <th>#</th>
                <th>Filename</th>
                <th>Last Revised By</th>
                <th>Revision Date</th>
            </tr>
          </thead>';

    $revisions_sql = "SELECT a.serial_no, a.file_name AS file_name, b.revised_by AS revised_by, b.revision_date AS revision_date 
                      FROM t_upload_file a 
                      RIGHT JOIN file_revisions b ON a.serial_no = b.serial_no 
                      WHERE b.serial_no = :serial_no ORDER BY b.revision_date DESC";

    $stmt = $conn->prepare($revisions_sql);
    $stmt->bindParam(':serial_no', $serial_no, PDO::PARAM_STR);

    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['file_name'] . '</td>';
            echo '<td>' . $k['revised_by'] . '</td>';
            echo '<td>' . date('Y/m/d h:i:s', strtotime($k['revision_date'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr style="text-align:center;"><td colspan="4">No records found.</td></tr>';
    }
}
