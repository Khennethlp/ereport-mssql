<?php
require '../conn.php';

$method = $_POST['method'];

if ($method == 'get_sub_doc') {
    $main_doc = $_POST['main_doc'];

    $sql = "SELECT sub_doc FROM m_report_title WHERE main_doc = :main_doc AND sub_doc IS NOT NULL";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute(array(':main_doc' => $main_doc));

    if ($stmt->rowCount() > 0) {
        // echo '<option selected value="">--Select Sub Document--</option>';
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach( $stmt->fetchAll() as $row){
            echo '<option value="' . htmlspecialchars($row['sub_doc']) . '">' . htmlspecialchars($row['sub_doc']) . '</option>';
        }
    } else {
        echo '<option disabled selected value="">--No Sub Documents Available--</option>';
    }
}
//  else {
//     echo '<option disabled selected value="">--Select Sub Document--</option>';
// }
?>
