<?php
session_start();
include ('includes/config.php');
// Make sure this includes the database connection

$emp_id = $_SESSION['emp_id'];

$query = "SELECT * FROM journeys WHERE journey_status = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $emp_id);
$stmt->execute();
$result = $stmt->get_result();

$response = array('hasOngoingJourney' => false);

if ($result->num_rows > 0) {
    $response['hasOngoingJourney'] = true;
}

echo json_encode($response);
?>
