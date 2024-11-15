<?php
include ('../includes-admin/connection.php');
if (isset($_POST['emp_id'], $_POST['emp_name'], $_POST['password'], $_POST['role'])) {
    $emp_id = $_POST['emp_id'];
    $emp_name = $_POST['emp_name'];
    $password = $_POST['password'];
    //$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    $sql = "INSERT INTO emp (emp_id, emp_name, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $emp_id, $emp_name, $password, $role);

    if ($stmt->execute()) {
        $data = array(
            'status' => 'true'
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'false'
        );
        echo json_encode($data);
    }
} else {
    $data = array(
        'status' => 'false',
        'error' => 'Missing parameters'
    );
    echo json_encode($data);
}
?>
