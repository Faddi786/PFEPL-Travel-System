<?php
include ('../includes-admin/connection.php');


if (isset($_POST['site_id'])) {
    $site_id = $_POST['site_id'];

    $sql = "DELETE FROM sites WHERE site_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $site_id);

    if ($stmt->execute()) {
        $data = array(
            'status' => 'success'
        );
        echo json_encode($data);
    } else {
        $data = array(
            'status' => 'failure'
        );
        echo json_encode($data);
    }
} else {
    $data = array(
        'status' => 'failure',
        'error' => 'site_id parameter not provided'
    );
    echo json_encode($data);
}
?>