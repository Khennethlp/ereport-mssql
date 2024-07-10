<?php
require '../../process/conn.php';

$method = $_POST['method'];

if ($method == 'history_checker_table') {

    // $status = 'Approved';
    $checker_name = isset($_POST['checker_name']) ? $_POST['checker_name'] : '';

    $sql = "SELECT DISTINCT a.serial_no AS serial_no, a.id AS id, a.checker_status as checker_status, a.checker_name as checker_name, a.checked_date as checked_date, b.serial_no AS b_serial_no, b.main_doc, b.sub_doc, b.file_name AS filenames FROM t_training_record a RIGHT JOIN (SELECT id, serial_no, main_doc, sub_doc, file_name FROM t_upload_file) b ON a.serial_no = b.serial_no AND a.id = b.id WHERE a.checker_status = 'Approved' AND a.checker_name = :checker_name ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':checker_name', $checker_name, PDO::PARAM_STR);
    // $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->execute();

    $c = 0;

    if ($stmt->rowCount() > 0) {
        while ($k = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $c++;
            $serial_no = htmlspecialchars($k['serial_no']);
            $file_path = '../../../uploads/ereport/' . htmlspecialchars($k['serial_no']) . '/';
            $file_path .= htmlspecialchars($k['main_doc']) . '/';
            if (!empty($k['sub_doc'])) {
                $file_path .= htmlspecialchars($k['sub_doc']) . '/';
            }
            $file_path .= htmlspecialchars($k['filenames']);

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            // echo '<td><span>' . htmlspecialchars($k['id']) . '</span></td>';
            echo '<td>' . htmlspecialchars($k['serial_no']) . '</td>';
            // echo '<td>' . htmlspecialchars($k['checker_name']) . '</td>';
            echo '<td>' . htmlspecialchars($k['filenames']) . '</td>';
            echo '<td>' . htmlspecialchars($k['checked_date']) . '</td>';
            if (file_exists($file_path)) {
                echo '<td style="cursor: pointer;"><a href="' . $file_path . '" download><i class="fas fa-download"></i></a></td>';
            } else {
                echo '<td>File not found</td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr >';
        echo '<td colspan="4" class="text-center">Nothing found.</td>';
        echo '</tr>';
    }
}
