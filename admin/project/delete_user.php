<?php
include ('../includes-admin/connection.php');

if (isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    // Prepare and execute DELETE query
    $sql = "DELETE FROM project WHERE project_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $project_id);

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success'
        );
    } else {
        $data = array(
            'status' => 'failure',
            'error' => $stmt->error
        );
    }

    echo json_encode($data);
} else {
    $data = array(
        'status' => 'failure',
        'error' => 'project_id parameter not provided'
    );
    echo json_encode($data);
}
?>
