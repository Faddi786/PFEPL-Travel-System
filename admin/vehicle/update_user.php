<?php
include ('../includes-admin/connection.php');


if (isset($_POST['id'], $_POST['username'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    
    $sql = "UPDATE vehicle SET v_name = ? WHERE v_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $username, $id);
    
    if ($stmt->execute()) {
        echo json_encode(array('status' => 'true'));
        // Output JavaScript for redirection
    
    } else {
        echo json_encode(array('status' => 'false'));
    }

} else {
    echo json_encode(array('status' => 'false', 'error' => 'ID or username not provided'));
}
 
?>
