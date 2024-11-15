<?php
include ('../includes-admin/connection.php');

if (isset($_POST['v_id'])) {
    $v_id = $_POST['v_id'];

    // Prepare and execute DELETE query
    $sql = "DELETE FROM vehicle WHERE v_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $v_id);

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
        'error' => 'v_id parameter not provided'
    );
    echo json_encode($data);
}
?>
