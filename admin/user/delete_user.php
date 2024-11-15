<?php
include ('../includes-admin/connection.php');

if (isset($_POST['emp_id'])) {
    $emp_id = $_POST['emp_id'];

    $sql = "DELETE FROM emp WHERE emp_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $emp_id);

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
        'error' => 'emp_id parameter not provided'
    );
    echo json_encode($data);
}
?>