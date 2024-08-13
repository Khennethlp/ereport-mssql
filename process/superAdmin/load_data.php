<?php
require '../../process/conn.php';


// Database connection
// $conn = new PDO('mysql:host=hostname;dbname=database', 'username', 'password');
$sortBy = intval($_POST['sortBy'] ?? 0);
$serialNo = $_POST['serialNo'] ?? '';

// Initialize output
$html = '';
$data = [
    'batch_no' => '',
    'group_no' => '',
    'training_group' => '',
    'error' => ''
];

// Fetch data from t_upload_file
$sql = 'SELECT * FROM t_upload_file LIMIT :sortBy';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':sortBy', $sortBy, PDO::PARAM_INT);
$stmt->execute();

$c = 0;
if ($stmt->rowCount() > 0) {
    foreach ($stmt as $k) {
        $c++;
        $html .= '<tr>';
        $html .= '<td>' . $c . '</td>';
        $html .= '<td>' . $k['serial_no'] . '</td>';
        $html .= '<td>' . (strlen($k['file_name']) > 50 ? substr($k['file_name'], 0, 50) . '...' : $k['file_name']) . '</td>';
        $html .= '<td><a href="" data-toggle="modal" data-target="#super_record_m" data-serial="' . $k['serial_no'] . '">View</a></td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td>No data.</td></tr>';
}

// Fetch data from t_training_record if serialNo is set
if ($serialNo) {
    $sql = 'SELECT * FROM t_training_record WHERE serial_no = :serialNo';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':serialNo', $serialNo, PDO::PARAM_STR);
    $stmt->execute();

    $k = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($k !== false) {
        $data['batch_no'] = $k['batch_no'];
        $data['group_no'] = $k['group_no'];
        $data['training_group'] = $k['training_group'];
    } else {
        $data['error'] = 'No record found with the provided serial number.';
    }
}

// Output both HTML and additional data as JSON
echo json_encode([
    'html' => $html,
    'data' => $data
]);
?>
