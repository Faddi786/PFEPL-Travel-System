<?php
include ('includes/config.php');
$emp_id = $_GET['emp_id'];
$sql = "SELECT emp_name FROM emp WHERE emp_id = '$emp_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row['emp_name'];
    }
} else {
    echo "";
}
$conn->close();
?>
