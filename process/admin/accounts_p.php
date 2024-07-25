<?php
include '../conn.php';

$method = $_POST['method'];

if ($method == 'load_accounts') {

    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $sql = "SELECT * FROM m_accounts WHERE role != 'admin' ";
    if (!empty($search)) {
        // Use placeholders and prepare statement for better security
        $sql .= "AND (emp_id = :search OR fullname = :search OR email = :search OR username = :search)";
    }
    
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    
    if (!empty($search)) {
        // Bind parameter for each placeholder
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    }
    $stmt->execute();

    $c = 0;
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchAll() as $k) {
            $c++;
            echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_account" onclick="get_accounts_details(&quot;' . $k['id'] . '~!~' . $k['emp_id'] . '~!~' . $k['username'] . '~!~' . $k['fullname'] . '~!~' . $k['email'] . '~!~' . $k['password'] . '~!~' . $k['role'] . '&quot;)">';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $k['emp_id'] . '</td>';
            echo '<td>' . $k['fullname'] . '</td>';
            echo '<td>' . $k['username'] . '</td>';
            // echo '<td>' . $k['email'] . '</td>';
            echo '<td>' . $k['role'] . '</td>';
            // echo '<td>' .  date('Y/M/d', strtotime($k['created_at'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="8" style="text-align:center;">No user found.</td>';
        echo '</tr>';
    }
}

if ($method == 'add_accounts') {
    $emp_id = $_POST['emp_id'];
    $fullname = $_POST['fullname'];
    // $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO m_accounts (emp_id, fullname, username, password, role)
    VALUES ('$emp_id', '$fullname', '$username', '$password', '$role')";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'edit_account') {
    $id = $_POST['id'];
    $emp_id = $_POST['emp_id'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    // $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $update_qry = "UPDATE m_accounts SET emp_id = :emp_id, fullname = :fullname, username = :username, password = :password, role = :role WHERE id = :id";
    $stmt = $conn->prepare($update_qry, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':emp_id', $emp_id);
    $stmt->bindParam(':fullname', $fullname);
    // $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'del_account') {
    $id = $_POST['id'];

    $query = "DELETE FROM m_accounts WHERE id = '$id'";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
