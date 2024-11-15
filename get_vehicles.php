<?php
include ('includes/config.php');
$searchTerm = $_GET['term'];
$sql = "SELECT v_id FROM vehicle WHERE v_id LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%{$searchTerm}%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];
while($row = $result->fetch_assoc()) {
  $vehicles[] = $row['v_id'];
}

echo json_encode($vehicles);

$stmt->close();
$conn->close();
?>