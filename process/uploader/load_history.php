<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_revision_data') {

    $revisions_sql = "SELECT a.serial_no, a.file_name, b.count, b.revised_by AS revised_by, b.revision_date AS revision_date FROM t_upload_file a INNER JOIN (SELECT COUNT(serial_no) AS count, serial_no, revision_date, revised_by FROM file_revisions GROUP BY serial_no) b ON a.serial_no = b.serial_no GROUP BY a.serial_no";
    $stmt = $conn->prepare($revisions_sql);
    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr>';
            echo '<td>'.$c.'</td>';
            echo '<td>'.$k['serial_no'].'</td>';
            echo '<td>'.$k['file_name'].'</td>';
            echo '<td>'.$k['revised_by'].'</td>';
            echo '<td>'.$k['count'].'</td>';
            echo '<td>'.$k['revision_date'].'</td>';
            echo '</tr>';
        }
    }else {
        echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }
}
