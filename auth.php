<?php

// Disable caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // HTTP 1.0
header("Pragma: no-cache"); // HTTP 1.0

session_start();
include('includes/config.php');

// Check if the action is set in the URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    session_destroy();
    header("Location: login.html");
    exit();
}

// Handle login action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action === 'login') {
    $emp_id = $_POST['login-ID'];
    $password = $_POST['login-pass'];

    // Query to check the credentials
    $sql = "SELECT * FROM emp WHERE emp_id = '$emp_id' AND password = '$password'";
    $result = $conn->query($sql);

    // Validate login
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Store user data in session
        $_SESSION['emp_id'] = $user['emp_id'];
        $_SESSION['emp_name'] = $user['emp_name'];
        $_SESSION['password'] = $user['password'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['vehicle_number'] = "";
        $_SESSION['vehicle_name'] = "";
        $_SESSION['start_kilometer'] = "";
        $_SESSION['journey_id'] = "";


        // Check for unfinished journeys
        $journeyCheckSql = "SELECT * FROM journeys WHERE emp_id = '$emp_id' AND journey_status = 1";
        $journeyCheckResult = $conn->query($journeyCheckSql);

        if ($journeyCheckResult->num_rows > 0) {
            // Set a session variable to indicate an unfinished journey
            $_SESSION['unfinished_journey'] = true;

            // Fetch the vehicle information
            $journey = $journeyCheckResult->fetch_assoc();
            $_SESSION['vehicle_number'] = $journey['vehicle_number'];
            $_SESSION['vehicle_name'] = $journey['vehicle_name'];
            $_SESSION['start_kilometer'] = $journey['start_kilometer'];
            $_SESSION['journey_id'] = $journey['id'];

            $_SESSION['journey_alert_message'] = "Your previous journey is not ended. Please end it before starting a new one.";
        } else {
            $_SESSION['unfinished_journey'] = false;
        }

        // Redirect based on role
        if ($_SESSION['role'] == 1) {
            header("Location: records.php");
            exit();
        } elseif ($_SESSION['role'] == 0) {
            header("Location: index.php");
            exit();
        } elseif ($_SESSION['role'] == 2) {
            header("Location: records.php");
            exit();
        } else {
            header("Location: login.html?error=1");
            exit();
        }
    } else {
        // Invalid credentials, redirect back to login with an error
        header("Location: login.html?error=1");
        exit();
    }
}
?>
