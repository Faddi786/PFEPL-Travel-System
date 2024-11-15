<?php
include ('../includes-admin/connection.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT emp_id, emp_name, password, role FROM emp WHERE emp_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'No data found'));
    }
} else {
    echo json_encode(array('error' => 'ID parameter not provided'));
}
?>
