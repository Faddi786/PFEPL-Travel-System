<?php
include ('../includes-admin/connection.php');

$sql = "SELECT project_id, project_name FROM project";
$query = mysqli_query($con, $sql);

$projects = array();
while ($row = mysqli_fetch_assoc($query)) {
    $projects[] = $row;
}
echo json_encode($projects);
?>
