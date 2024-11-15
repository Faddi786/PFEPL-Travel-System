<?php
require ('fpdf/fpdf.php');
include ('includes/config.php'); // Include your database connection configuration


// Function to check if the image is a valid JPEG
function isValidJPEG($filePath) {
    if (file_exists($filePath)) {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo && $imageInfo[2] == IMAGETYPE_JPEG) {
            return true; // Valid JPEG
        }
    }
    return false; // Not a valid JPEG
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch data from database based on id
    $stmt = $conn->prepare("SELECT * FROM journeys WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Data from database
        $emp_id = $row['emp_id'];
        $emp_name = $row['emp_name'];
        $start_location = $row['start_location'];
        $end_location = $row['end_location'];
        $start_kilometer = $row['start_kilometer'];
        $end_kilometer = $row['end_kilometer'];
        $start_photo_path = $row['start_photo'];
        $end_photo_path = $row['end_photo'];
        $project = $row['project_name'];
        // $sqlFetchProjectName = "SELECT project_name FROM project WHERE project_id = " . $row['project'];
        // $resultProjectName = $conn->query($sqlFetchProjectName);
        // if ($resultProjectName->num_rows > 0) {
        //     $projectRow = $resultProjectName->fetch_assoc();
        //     $project = $projectRow["project_name"];
        // } else {
        //     $project = "Unknown"; // Default value if project name is not found
        // }

        $distance = $row['distance'];
        $start_time = $row['start_time'];
        $end_time = $row['end_time'];
        $vehicle_number = $row['vehicle_number'];
        $vehicle_name = $row['vehicle_name'];
        $remark = $row['remark'];

        // PDF generation using FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', 'B', 16);

        // Output data to PDF
        $pdf->Cell(0, 10, 'Travel Reimbursement Details', 0, 1, 'C');
        $pdf->Ln(5);

        // Set font for labels and values
        $pdf->SetFont('Arial', '', 12);

        // Employee ID
        // $pdf->Cell(60, 10, 'Employee ID / Name ', 1, 0, 'L');
        // $pdf->Cell(0, 10, $emp_id .' / '.$emp_name , 1, 1, 'L');

        //$pdf->SetFillColor(179, 223, 253);
        $pdf->Cell(30, 10, 'Employee ID ', 1, 0, 'L');
        $pdf->Cell(45, 10, $emp_id, 1, 0, 'L');
        $pdf->Cell(50, 10, 'Employee Name ', 1, 0, 'L');
        $pdf->Cell(65, 10, $emp_name, 1, 1, 'L');

        // Vehicle Number
        $pdf->Cell(30, 10, 'Vehicle No. ', 1, 0, 'L');
        $pdf->Cell(45, 10, $vehicle_number, 1, 0, 'L');

        // Vehicle Name
        $pdf->Cell(50, 10, 'Vehicle Name', 1, 0, 'L');
        $pdf->Cell(65, 10, $vehicle_name, 1, 1, 'L');

        // Employee Name
        // $pdf->Cell(60, 10, 'Employee Name', 1, 0, 'L');
        // $pdf->Cell(0, 10, $emp_name, 1, 1, 'L');

        // Project
        $pdf->Cell(60, 10, 'Project', 1, 0, 'L');
        $pdf->Cell(0, 10, $project, 1, 1, 'L');

        // Start Location
        $pdf->Cell(60, 10, 'Start Location', 1, 0, 'L');
        $pdf->Cell(0, 10, $start_location, 1, 1, 'L');

        // End location
        $pdf->Cell(60, 10, 'End Location', 1, 0, 'L');
        $pdf->Cell(0, 10, $end_location, 1, 1, 'L');

        // Start Kilometer
        $pdf->Cell(60, 10, 'Start Kilometer', 1, 0, 'L');
        $pdf->Cell(0, 10, $start_kilometer . " km", 1, 1, 'L');

        // End Kilometer
        $pdf->Cell(60, 10, 'End Kilometer', 1, 0, 'L');
        $pdf->Cell(0, 10, $end_kilometer . " km", 1, 1, 'L');

        // Distance
        $pdf->Cell(60, 10, 'Distance Travelled', 1, 0, 'L');
        $pdf->Cell(0, 10, $distance . " km", 1, 1, 'L');

        // Start time
        $pdf->Cell(60, 10, 'Start time', 1, 0, 'L');
        $pdf->Cell(0, 10, $start_time, 1, 1, 'L');

        // End time
        $pdf->Cell(60, 10, 'End Time', 1, 0, 'L');
        $pdf->Cell(0, 10, $end_time, 1, 1, 'L');

        // Remark
        $pdf->Cell(60, 10, 'Remark', 1, 0, 'L');
        $pdf->Cell(0, 10, $remark, 1, 1, 'L');


        $pdf->AddPage();



        // Display images side by side
        $pdf->Ln(10); // Add some vertical space
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, 'Start Photo:', 0, 1, 'L');
        // $pdf->Ln(10); // Add a small line break



// Define parameters for photo dimensions
$photoWidth = 150;  // Width of the photo
$photoHeight = 100; // Height of the photo
$photoSpacing = 10; // Space between photos

$pdf->SetFont('Arial', '', 12);
$startX = $pdf->GetX(); // Get the current X position
$startY = $pdf->GetY(); // Get the current Y position

// Display Start Photo
if (file_exists($start_photo_path)) {
    $pdf->Image($start_photo_path, $startX, $startY, $photoWidth, $photoHeight);
} else {
    $pdf->Cell($photoWidth, $photoHeight, 'Start Photo not found', 1, 0, 'C');
}


// Move to the position for the End Photo (below the Start Photo)
$pdf->SetXY($startX, $startY + $photoHeight + $photoSpacing);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'End Photo:', 0, 1, 'L');
$pdf->Ln(2); // Add a small line break

// Display End Photo
if (file_exists($end_photo_path)) {
    $pdf->Image($end_photo_path, $pdf->GetX(), $pdf->GetY(), $photoWidth, $photoHeight);
} else {
    $pdf->Cell($photoWidth, $photoHeight, 'End Photo not found', 1, 0, 'C');
}


        // Output PDF
        $pdf->Output();
    } else {
        echo "No record found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>