<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');

$sql = "SELECT project_id, project_name FROM project";
$result = $conn->query($sql);

$projects = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = array("project_id" => $row["project_id"], "project_name" => $row["project_name"]);
    }
}

header('Content-Type: application/json');
echo json_encode($projects);
$conn->close();
?>
