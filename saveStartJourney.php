<?php
include ('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $_POST['emp_id_start'];
    $emp_name = $_POST['emp_name_start'];
    $start_location = $_POST['start_location'];
    $start_kilometer = $_POST['start_kilometer'];
    $start_photo = $_POST['start_photo'];
    $vehicle_name = $_POST['v_name_start'];
    $vehicle_number = $_POST['v_id_start'];
    $project = $_POST['project'];



    // Assuming you have your database connection in $con
    $project_id = $project; // Replace this with the actual project ID you want to use

    // Prepare the SQL statement
    $get_for_projectname = "SELECT project_name FROM project WHERE project_id = ?";
    $stmt = $conn->prepare($get_for_projectname);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the project ID parameter to the prepared statement
    $stmt->bind_param("i", $project_id); // "i" specifies the type (integer)

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Bind the result to a variable
        $stmt->bind_result($project_name);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // echo "Project Name: " . $project_name; // Output the project name
        } else {
            // echo "No project found with that ID.";
        }
    } else {
        echo "Execution failed: " . htmlspecialchars($stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Set the default timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

// Get the current time in IST
$start_time = date('Y-m-d H:i:s');

    $sql = "INSERT INTO journeys (emp_id, emp_name, start_location, start_kilometer, start_photo, vehicle_name, vehicle_number, project_name, start_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Change from "ssssssss" to "sssssssss"
        $stmt->bind_param("sssssssss", $emp_id, $emp_name, $start_location, $start_kilometer, $start_photo, $vehicle_name, $vehicle_number, $project_name, $start_time);
    
        if ($stmt->execute()) {
            $journey_id = $stmt->insert_id;

            // Update the vehicle status
            $update_sql = "UPDATE vehicle SET status = 1 WHERE v_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            if ($update_stmt) {
                $update_stmt->bind_param("s",$vehicle_number);
                if ($update_stmt->execute()) {
                    session_unset();   // Unset all session variables

                    session_destroy();
                    echo "<script>alert('Journey started successfully. Journey ID: $journey_id'); window.location.href = 'login.html';</script>";
                    
                } else {
                    echo "Error updating vehicle status: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

