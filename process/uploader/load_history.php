<?php
include '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'load_revision_data') {

    $revisions_sql = "SELECT b.id, a.serial_no, a.file_name, b.count, b.revised_by AS revised_by, b.revision_date AS revision_date, c.batch_no AS batch_no, c.group_no As group_no FROM t_upload_file a INNER JOIN (SELECT id, COUNT(serial_no) AS count, serial_no, revision_date, revised_by FROM file_revisions GROUP BY serial_no) b ON a.serial_no = b.serial_no INNER JOIN (SELECT * FROM t_training_record) c ON b.serial_no=c.serial_no GROUP BY a.serial_no ORDER BY b.id";
    $stmt = $conn->prepare($revisions_sql);
    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr onclick="load_t2(&quot;' . $k['serial_no'] . '&quot;);">';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['count'] . '</td>'; 
            echo '<td>' . $k['serial_no'] . '</td>';
            echo '<td>' . $k['batch_no'] . '</td>';
            echo '<td>' . $k['group_no'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }
}

if ($method == 'load_revision_data2') {

    echo '<thead>
        <tr>
        <th>#</th>
        <th>Revision Count</th>
        <th>Serial No</th>
        <th>Batch No</th>
        <th>Group No</th>
        </tr>
    </thead>';

    $revisions_sql = "SELECT b.id, a.serial_no, a.file_name, b.count, b.revised_by AS revised_by, b.revision_date AS revision_date, c.batch_no AS batch_no, c.group_no As group_no FROM t_upload_file a INNER JOIN (SELECT id, COUNT(serial_no) AS count, serial_no, revision_date, revised_by FROM file_revisions GROUP BY serial_no) b ON a.serial_no = b.serial_no INNER JOIN (SELECT * FROM t_training_record) c ON b.serial_no=c.serial_no GROUP BY a.serial_no ORDER BY b.id";
    $stmt = $conn->prepare($revisions_sql);
    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr onclick="load_t2(&quot;' . $k['serial_no'] . '&quot;);">';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['count'] . '</td>';
            echo '<td>' . $k['serial_no'] . '</td>';
            echo '<td>' . $k['batch_no'] . '</td>';
            echo '<td>' . $k['group_no'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }
}

if ($method == 'load_t_t2') {
    $serial_no = $_POST['serial_no'];

    echo '<thead>
			<tr>
			<th>#</th>
			<th>Filename</th>
			<th>Revised By</th>
			<th>Revision Date</th>
			</tr>
		</thead>';

    $revisions_sql = "SELECT a.serial_no, a.file_name AS file_name, b.revised_by AS revised_by,b.serial_no, b.revision_date AS revision_date FROM t_upload_file a RIGHT JOIN (SELECT serial_no, revision_date, revised_by FROM file_revisions) b ON a.serial_no = b.serial_no WHERE b.serial_no = '$serial_no'";
    $stmt = $conn->prepare($revisions_sql);
    $stmt->execute();
    $c = 0;

    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $k) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['file_name'] . '</td>';
            echo '<td>' . $k['revised_by'] . '</td>';
            echo '<td>' . date('Y/m/d', strtotime($k['revision_date'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr style="text-align:center;"><td colspan="12">No records found.</td></tr>';
    }
}
