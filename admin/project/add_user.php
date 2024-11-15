<?php
include ('../includes-admin/connection.php');

// if (isset($_POST['project_id'], $_POST['project_name'])) {
if (isset($_POST['project_name'])) {
    // $project_id = $_POST['project_id'];
    $project_name = $_POST['project_name'];

    $sql = "INSERT INTO project (project_name) VALUES (?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $project_name);

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