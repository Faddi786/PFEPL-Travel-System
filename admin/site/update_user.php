<?php
include ('../includes-admin/connection.php');

if (isset($_POST['id'], $_POST['site_name'], $_POST['project_id'])) {
    $id = $_POST['id'];
    $site_name = $_POST['site_name'];
    $project_id = $_POST['project_id'];
    
    $sql = "UPDATE sites SET site_name = ?, project_id = ? WHERE site_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii", $site_name, $project_id, $id);
    
    if ($stmt->execute()) {
        echo json_encode(array('status' => 'true'));
    } else {
        echo json_encode(array('status' => 'false'));
    }
} else {
    echo json_encode(array('status' => 'false', 'error' => 'ID, site name, or project ID not provided'));
}
?>
