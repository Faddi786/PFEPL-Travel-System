<?php
include ('../includes-admin/connection.php');

if (isset($_POST['id'], $_POST['username'], $_POST['password'], $_POST['role'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $sql = "UPDATE emp SET emp_name = ?, password = ?, role = ? WHERE emp_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssis", $username, $password, $role, $id);
    
    if ($stmt->execute()) {
        echo json_encode(array('status' => 'true'));
    } else {
        echo json_encode(array('status' => 'false'));
    }
} else {
    echo json_encode(array('status' => 'false', 'error' => 'ID, username, password, or role not provided'));
}
?>
