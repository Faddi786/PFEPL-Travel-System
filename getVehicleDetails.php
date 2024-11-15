<?php
include ('includes/config.php');

$v_id = $_GET['v_id'];
$sql = "SELECT v_name, status FROM vehicle WHERE v_id = '$v_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Return both v_name and status as JSON
        echo json_encode(['v_name' => $row['v_name'], 'status' => $row['status']]);
    }
} else {
    echo json_encode(['v_name' => 'No vehicle found', 'status' => '']);
}

$conn->close();
?>
