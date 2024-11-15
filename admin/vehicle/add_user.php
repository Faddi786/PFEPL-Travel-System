<?php
include ('../includes-admin/connection.php');

if (isset($_POST['v_id'], $_POST['v_name'])) {
    $v_id = $_POST['v_id'];
    $v_name = $_POST['v_name'];

    $sql = "INSERT INTO vehicle (v_id, v_name) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $v_id, $v_name);

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