<?php
require '../../process/conn.php';

$method = $_POST['method'];

if($method == 'getData_masterlist'){

    $sortBy = intval($_POST['sortBy'] ?? 0);
    $serialNo = isset($_POST['serialNo']) ? $_POST['serialNo'] : '';

    $sql = 'SELECT * FROM t_training_record where serial_no = :serial_no';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':serial_no', $serialNo, PDO::PARAM_INT);
    $stmt->execute();

    // $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // foreach($res as $k){

    //     $batchNo = $k['batch_no'];
    // }
    
    $sql = 'SELECT * FROM t_upload_file LIMIT :sortBy';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sortBy', $sortBy, PDO::PARAM_INT);
    $stmt->execute();
    
    $c = 0;
    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $k) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['serial_no'] . '</td>';
            echo '<td>' . (strlen($k['file_name']) > 50 ? substr($k['file_name'], 0, 50) . '...' : $k['file_name']) . '</td>';
            echo '<td><a href="" data-toggle="modal" data-target="#super_record_m" onclick=get_details("' . $k['serial_no'] . '");>View</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td>No data.</td></tr>';
    }    
}

?>
