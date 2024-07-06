<?php
session_name("e_report_system");
session_start();
include 'conn.php';

$title = "E-REPORT";

if (isset($_POST['Login'])) {
    // Sanitize and validate user inputs
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Check if a user role is selected
    if (isset($_POST['users'])) {
        $user = $_POST['users']; // selected user role
        
        // Prepare SQL statement with placeholders
        $sql = "SELECT * FROM m_accounts WHERE username = :username AND password = :password AND role = :role";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':role', $user);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        // Check if there is a matching row
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $result['fullname'];
            $role = $result['role'];
            $emp_id = $result['emp_id'];
            $email = $result['email'];
            
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['emp_id'] = $emp_id;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role; // Set the role from the database
            
            // Check if the role from the database matches the selected user role
            if ($role === $user) {
                switch ($role) {
                    case 'admin':
                        header('Location: pages/admin/index.php');
                        exit;
                    case 'approver':
                        header('Location: pages/approver/index.php');
                        exit;
                    case 'uploader':
                        header('Location: pages/uploader/index.php');
                        exit;
                    case 'checker':
                        header('Location: pages/checker/index.php');
                        exit;
                    default:
                        $_SESSION['status'] = 'error';
                        $_SESSION['msg'] = 'Invalid role.';
                        header('Location: index.php');
                        exit;
                }
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['msg'] = 'Role mismatch.';
                header('Location: index.php'); // Redirect to login page
                exit;
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['msg'] = 'Sign In Failed. Please try again.';
            header('Location: index.php'); // Redirect to login page
            exit;
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Please select a user role.';
        header('Location: index.php'); // Redirect to login page
        exit;
    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('Location: /e-report/index.php');
    exit;
}
?>
