<?php
require '../conn.php';

$method = $_POST['method'] ?? '';

if ($method == 'get_sub_doc') {
    $main_doc = $_POST['main_doc'] ?? '';

    // Sanitize $main_doc to prevent SQL injection
    $main_doc = htmlspecialchars($main_doc);

    // Prepare and execute the SQL query
    $sql = "SELECT sub_doc FROM m_report_title WHERE main_doc = :main_doc AND sub_doc IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':main_doc' => $main_doc));

    if ($stmt->rowCount() > 0) {
        echo '<option selected value="">--Select Sub Document--</option>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . htmlspecialchars($row['sub_doc']) . '">' . htmlspecialchars($row['sub_doc']) . '</option>';
        }
    } else {
        echo '<option disabled selected value="">--No Sub Documents Available--</option>';
    }
} else {
    echo '<option disabled selected value="">--Select Sub Document--</option>';
}
?>
