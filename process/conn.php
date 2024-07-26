<?php
$server_month = date('Y-m-d');
date_default_timezone_set('Asia/Manila');
$servername = 'localhost'; $username = 'root'; $password = '';
// $servername = 'localhost'; $username = 'root'; $password = 'trspassword2022';

try {
    $conn = new PDO ("mysql:host=$servername;dbname=e-report",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION'.$e->getMessage();
}

// $serverName = '172.25.114.171\SQLEXPRESS';
// $database = 'e-report';
// $username = 'sa';
// $password = 'SystemGroup2018';

// try {
//     // Create the PDO object with the correct DSN
//     $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);

//     // Set the PDO error mode to exception
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     // echo "Connection successful!";
// } catch (PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }


?>