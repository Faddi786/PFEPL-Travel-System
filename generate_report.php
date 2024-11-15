<?php
require('fpdf/fpdf.php'); // Include FPDF library
include('includes/config.php'); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $vehicle_number = $_POST['vehicle_number'];
    $vehicle_number = isset($_POST['vehicle_number']) ? trim($_POST['vehicle_number']) : '';

    $start_date = $_POST['start_date'];

    $end_date = $_POST['end_date'];

    // $month_start = $month . '-01';
    // $month_end = date("Y-m-t", strtotime($month_start));

    // Modified SQL query to match the journeys table structure
    $sql = "SELECT DATE(end_time) AS date_of_usage, 
                   GROUP_CONCAT(DISTINCT emp_name ORDER BY emp_name ASC SEPARATOR ', ') AS emp_names, 
                   GROUP_CONCAT(DISTINCT project_name ORDER BY project_name ASC SEPARATOR ', ') AS projects,
                   SUM(distance) AS total_distance,
                   MIN(start_photo) AS first_photo,
                   MAX(end_photo) AS last_photo
            FROM journeys
            WHERE vehicle_number = ? 
            AND DATE(start_time) BETWEEN ? AND ?
            AND journey_status != 1
            GROUP BY DATE(end_time)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Error in preparing the SQL statement: ' . $conn->error);
    }

    $stmt->bind_param("sss", $vehicle_number, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die('Error in executing the query: ' . $stmt->error);
    }

    $data = [];
    $total_distance_month = 0;

    while ($row = $result->fetch_assoc()) {
        // Directly use the projects fetched from the main query
        $row['project_names'] = $row['projects']; // No need for an additional query
        $data[] = $row;
        $total_distance_month += $row['total_distance'];
    }

    $total_days_travelled = count($data);

    $stmt->close();
    $conn->close();

    // Generate PDF using FPDF
    class PDF extends FPDF
    {
        function Header()
        {
            // Add company logo
            $this->Image('static/logo/logo.png', 170, 10, 25); // Change the path and size as needed
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'PFEPL Vehicle Usage Report', 0, 1, 'C');
            $this->Ln(20);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }

        function MultiCellRow($widths, $data)
        {
            // Calculate the height of the row
            $nb = 0;
            for ($i = 0; $i < count($data); $i++) {
                $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
            }
            $h = 5 * $nb;

            // Issue a page break first if needed
            $this->CheckPageBreak($h);

            // Draw the cells of the row
            for ($i = 0; $i < count($data); $i++) {
                $w = $widths[$i];
                $a = 'L';

                // Save the current position
                $x = $this->GetX();
                $y = $this->GetY();

                // Draw the border only if data is not empty
                if (!empty($data[$i])) {
                    $this->Rect($x, $y, $w, $h);
                }

                // Print the text
                $this->MultiCell($w, 5, $data[$i], 0, $a);

                // Put the position to the right of the cell
                $this->SetXY($x + $w, $y);
            }

            // Go to the next line
            $this->Ln($h);
        }

        function NbLines($w, $txt)
        {
            $cw = &$this->CurrentFont['cw'];
            if ($w == 0) {
                $w = $this->w - $this->rMargin - $this->x;
            }
            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if ($nb > 0 && $s[$nb - 1] == "\n") {
                $nb--;
            }
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;
            while ($i < $nb) {
                $c = $s[$i];
                if ($c == "\n") {
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                    continue;
                }
                if ($c == ' ') {
                    $sep = $i;
                }
                $l += $cw[$c];
                if ($l > $wmax) {
                    if ($sep == -1) {
                        if ($i == $j) {
                            $i++;
                        }
                    } else {
                        $i = $sep + 1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                } else {
                    $i++;
                }
            }
            return $nl;
        }

        function CheckPageBreak($h)
        {
            if ($this->GetY() + $h > $this->PageBreakTrigger) {
                $this->AddPage($this->CurOrientation);
            }
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();

// Set the font for the vehicle number, month, and current date
$pdf->SetFont('Arial', '', 10);

// Get the current date in YYYY-MM-DD format
$currentDate = date('Y-m-d'); // e.g., 2024-10-20

// Add cells for Vehicle Number, Month, and Current Date
$pdf->Cell(0, 10, 'Vehicle Number: ' . $vehicle_number, 0, 1, 'L');
// $pdf->Cell(0, 10, 'For the Month of: ' . $month, 0, 1, 'L'); // Move to the next line
$pdf->Cell(0, 10, 'Today\'s Date: ' . $currentDate, 0, 1, 'L'); // Move to the next line

// Add total distance and days travelled
$pdf->Cell(0, 10, 'Total Days Travelled: ' . $total_days_travelled, 0, 1);
$pdf->Cell(0, 10, 'Total Distance Travelled: ' . $total_distance_month . ' km', 0, 1);

$pdf->Ln(10); // Add some space before the totals


// Define custom column widths
$colWidths = [23, 60, 50, 25];

// Header row with custom column widths
$pdf->SetFillColor(200, 220, 255);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 8);
$headers = ['Date', 'Employee Names', 'Project Names', 'Total Distance'];
foreach ($headers as $key => $header) {
    $pdf->Cell($colWidths[$key], 10, $header, 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 9);

// Content rows with custom column widths
foreach ($data as $row) {
    $empNames = explode(', ', $row['emp_names']);
    $projects = explode(', ', $row['project_names']);

    $maxLines = max(count($empNames), count($projects));

    for ($line = 0; $line < $maxLines; $line++) {
        // Date
        $pdf->Cell($colWidths[0], 10, ($line === 0 ? $row['date_of_usage'] : ''), 'LR', 0, 'C');
        
        // Employee Names
        $pdf->Cell($colWidths[1], 10, isset($empNames[$line]) ? $empNames[$line] : '', 'LR', 0, 'C');
        
        // Project Names
        $pdf->Cell($colWidths[2], 10, isset($projects[$line]) ? $projects[$line] : '', 'LR', 0, 'C');
        
        // Total Distance
        $pdf->Cell($colWidths[3], 10, ($line === 0 ? $row['total_distance'] : ''), 'LR', 0, 'C');
        
        $pdf->Ln();


    }

    // Horizontal line after each date record
    $pdf->Cell(array_sum($colWidths), 0, '', 'T');
    $pdf->Ln();
    }


    
    // Add a new page for the photos
$pdf->AddPage();

// Add a title for the photo section
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Photos of the Month:', 0, 1, 'C');
// $pdf->Ln(10); // Add a vertical space before the first photo

// Define parameters for photo dimensions
$photoWidth = 150;  // Width of the photo
$photoHeight = 100; // Height of the photo
$photoSpacing = 10; // Space between photos

// Display First Photo with title
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'First Photo of the Month:', 0, 1, 'L'); // Title for the first photo
// $pdf->Ln(5); // Small space between title and photo

if (!empty($data[0]['first_photo']) && file_exists($data[0]['first_photo'])) {
    $pdf->Image($data[0]['first_photo'], $pdf->GetX(), $pdf->GetY(), $photoWidth, $photoHeight);
} else {
    $pdf->Cell($photoWidth, $photoHeight, 'First Photo not found', 1, 0, 'C');
}

$pdf->Ln($photoHeight + $photoSpacing); // Space before the next photo

// Display Last Photo with title
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, 'Last Photo of the Month:', 0, 1, 'L'); // Title for the last photo
// $pdf->Ln(5); // Small space between title and photo

$lastIndex = count($data) - 1;
if (!empty($data[$lastIndex]['last_photo']) && file_exists($data[$lastIndex]['last_photo'])) {
    $pdf->Image($data[$lastIndex]['last_photo'], $pdf->GetX(), $pdf->GetY(), $photoWidth, $photoHeight);
} else {
    $pdf->Cell($photoWidth, $photoHeight, 'Last Photo not found', 1, 0, 'C');
}




    // Output PDF as inline (I) or download (D)
    $pdf->Output('I', 'vehicle_report.pdf');
}

// Function to validate images
function isValidImage($filePath)
{
    $validTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
    $imageType = exif_imagetype($filePath);
    return in_array($imageType, $validTypes) && is_readable($filePath);
}
?>
