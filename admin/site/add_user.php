<?php
include ('../includes-admin/connection.php');

if (isset($_POST['site_name'], $_POST['project_id'])) {
    $site_name = $_POST['site_name'];
    $project_id = $_POST['project_id'];

    $sql = "INSERT INTO sites (site_name, project_id) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $site_name, $project_id);

    if ($stmt->execute()) {
        echo json_encode(array('status' => 'true'));
    } else {
        echo json_encode(array('status' => 'false'));
    }
} else {
    echo json_encode(array('status' => 'false', 'error' => 'Site name or project ID not provided'));
}
?>
