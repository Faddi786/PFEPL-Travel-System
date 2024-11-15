<?php
session_start();
include('includes/config.php');

try {
    // Fetch input data
    $emp_id = $_POST['emp_id_end'] ?? null;
    $emp_name = $_POST['emp_name_end'] ?? null;
    $end_kilometer = $_POST['end_kilometer'] ?? 'End km missing';
    $end_location = $_POST['end_location'] ?? 'End location missing';
    $vehicle_name = $_POST['v_name_end'] ?? 'Vehicle name missing';
    $vehicle_number = $_POST['v_id_end'] ?? 'Vehicle number missing';
    $end_photo = $_POST['end_photo'] ?? 'End photo missing';
    $remark = $_POST['remark'] ?? '-';

    // // Ensure required data is available
    // if (!$emp_id || !$end_kilometer || !$end_location || !$vehicle_name || !$vehicle_number) {
    //     throw new Exception('Required data is missing');
    // }

    $start_kilometer = $_SESSION['start_kilometer'] ?? null;
    if (!$start_kilometer) {
        throw new Exception('Start kilometer not found in session.');
    }

    // Calculate the distance
    $distance = $end_kilometer - $start_kilometer;

    // Fetch the journey ID
    $sql_fetch_journey_id = "SELECT id FROM journeys WHERE emp_id=? AND journey_status=1";
    $stmt_fetch_journey_id = $conn->prepare($sql_fetch_journey_id);
    if (!$stmt_fetch_journey_id) {
        throw new Exception('Error preparing SQL for journey ID: ' . $conn->error);
    }
    $stmt_fetch_journey_id->bind_param('s', $emp_id);
    $stmt_fetch_journey_id->execute();
    $result = $stmt_fetch_journey_id->get_result();
    if (!$result || $result->num_rows === 0) {
        throw new Exception('No active journey found for the employee.');
    }
    $row = $result->fetch_assoc();
    $journey_id = $row['id'];
    $stmt_fetch_journey_id->close();

        // Set the default timezone to Indian Standard Time (IST)
    date_default_timezone_set('Asia/Kolkata');

    // Get the current time in IST
    $end_time = date('Y-m-d H:i:s');

    // Update the journey details
    $sql_update = "UPDATE journeys SET end_location=?, end_kilometer=?, end_photo=?, end_time=?, remark=?, distance=?, journey_status=0 WHERE id=? and journey_status=1";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        throw new Exception('Error preparing SQL for updating journey: ' . $conn->error);
    }

    $stmt_update->bind_param('sssssii', $end_location, $end_kilometer, $end_photo, $end_time, $remark, $distance, $journey_id);
    if (!$stmt_update->execute()) {
        throw new Exception('Error updating journey: ' . $stmt_update->error);
    }
    $stmt_update->close();

    // Fetch vehicle details from the journey record to update its status
    $sql_fetch_vehicle = "SELECT vehicle_number FROM journeys WHERE id=?";
    $stmt_fetch_vehicle = $conn->prepare($sql_fetch_vehicle);
    if (!$stmt_fetch_vehicle) {
        throw new Exception('Error preparing SQL for fetching vehicle: ' . $conn->error);
    }
    $stmt_fetch_vehicle->bind_param('i', $journey_id);
    $stmt_fetch_vehicle->execute();
    $stmt_fetch_vehicle->bind_result($vehicle_number);
    $stmt_fetch_vehicle->fetch();
    $stmt_fetch_vehicle->close();

    // Update the vehicle status
    $sql_update_vehicle = "UPDATE vehicle SET status = 0 WHERE v_id = ?";
    $stmt_update_vehicle = $conn->prepare($sql_update_vehicle);
    if (!$stmt_update_vehicle) {
        throw new Exception('Error preparing SQL for updating vehicle: ' . $conn->error);
    }
    $stmt_update_vehicle->bind_param('s', $vehicle_number);
    if (!$stmt_update_vehicle->execute()) {
        throw new Exception('Error updating vehicle status: ' . $stmt_update_vehicle->error);
    }
    $stmt_update_vehicle->close();

    // Success message and redirect
    echo "<script>alert('Journey ended successfully. Journey ID: $journey_id'); window.location.href = 'login.html';</script>";
} catch (Exception $e) {
    // Handle any caught exceptions and log the error
    error_log("Error: " . $e->getMessage(), 0);
    echo "<script>alert('An error occurred: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = 'login.html';</script>";
} finally {
    $conn->close();
}
?>
