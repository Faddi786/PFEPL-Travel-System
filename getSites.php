<?php
include ('includes/config.php');

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];



    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT site_id, site_name FROM sites WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sites = array();
    while ($row = $result->fetch_assoc()) {
        $sites[] = $row;
    }

    echo json_encode($sites);

    $stmt->close();
}

$conn->close();
?>
