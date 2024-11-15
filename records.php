<?php include ('./admin/includes-admin/connection.php'); ?>
<?php
session_start();

if (($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header('Location:' . SITE_URL . 'login.html');
    exit();
}

?>
<?php include ('includes/header.php'); ?>
<?php include ('./admin/includes-admin/navbar-admin.php'); ?>

<div class="container_tbl" id="travelrecords" style="padding: 20px; width:100%">
    <!-- Filter Form -->
    <form method="POST" action="">
        
        <div class="table-wrapper" style="overflow: scroll;">
                    
            <table id="employeeTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Employee Name</th>
                        <th>Project Name</th>
                        <th>Vehicle Number</th>

                        <th>Distance</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Remark</th>
                        <!-- <th>Journey Status</th>                         -->
                        <th>View PDF</th>
                        <th>Start KM</th>
                        <th>End KM</th>
                        <th>Start Photo</th>
                        <th>End Photo</th>
                        <th>Start Location</th>
                        <th>End Location</th>

                        <?php if ($_SESSION['role'] == 1) { ?>
                            <th>Delete Row</th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            <select  name="emp_name" id="emp_name">
                                <option value="">Select Employee</option>
                                <?php
                                // Fetch unique employee names based on current filtered data
                                $whereClauses = [];

                                if (!empty($_POST['emp_name'])) {
                                    $emp_name = $_POST['emp_name'];
                                    $whereClauses[] = "emp_name = '$emp_name'";
                                }

                                if (!empty($_POST['vehicle_number'])) {
                                    $vehicle_number = $_POST['vehicle_number'];
                                    $whereClauses[] = "vehicle_number = '$vehicle_number'";
                                }

                                if (!empty($_POST['project'])) {
                                    $project = $_POST['project'];
                                    $whereClauses[] = "project = '$project'";
                                }

                                if (!empty($_POST['start_date'])) {
                                    $start_date = $_POST['start_date'];
                                    $whereClauses[] = "DATE(start_time) >= '$start_date'";
                                }

                                if (!empty($_POST['end_date'])) {
                                    $end_date = $_POST['end_date'];
                                    $whereClauses[] = "DATE(start_time) <= '$end_date'";
                                }


                                if (!empty($_POST['distance_filter'])) {
                                    $distance_filter = $_POST['distance_filter'];
                                    if ($distance_filter === 'show_all') {
                                        // Show all rows where distance is greater than 300
                                        $whereClauses[] = "distance > 300"; // Adjust the condition to show all distances greater than 300
                                    } else {
                                        // Filter by specific distance
                                        $whereClauses[] = "distance = $distance_filter"; // Filter distances equal to the selected value
                                    }
                                }
                                
                                



                                $whereSQL = '';
                                if (count($whereClauses) > 0) {
                                    $whereSQL = ' WHERE ' . implode(' AND ', $whereClauses);
                                }

                                // Fetch unique employee names based on the filter conditions
                                $empNameSql = "SELECT DISTINCT emp_name FROM journeys $whereSQL order by emp_name asc";
                                $empNameResult = $con->query($empNameSql);
                                while ($row = $empNameResult->fetch_assoc()) {
                                    // Mark the selected option as "selected"
                                    $selected = (isset($_POST['emp_name']) && $_POST['emp_name'] == $row['emp_name']) ? 'selected' : '';
                                    echo "<option value='" . $row['emp_name'] . "' $selected>" . $row['emp_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </th>

                        <th>
                            <select  name="project" id="project">
                                <option value="">Select Project</option>
                                <?php
                                // Fetch unique project names based on the filter conditions
                                $projectNameSql = "SELECT DISTINCT project_name from journeys $whereSQL order by project_name asc";
                                $projectNameResult = $con->query($projectNameSql);
                                while ($row = $projectNameResult->fetch_assoc()) {
                                    // Mark the selected option as "selected"
                                    $selected = (isset($_POST['project_name']) && $_POST['project_name'] == $row['project_name']) ? 'selected' : '';
                                    echo "<option value='" . $row['project_name'] . "' $selected>" . $row['project_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </th>
                        
                        <th>
                            <select name="vehicle_number" id="vehicle_number">
                                <option value="">Select Vehicle</option>
                                <?php
                                // Fetch unique vehicle numbers based on the filter conditions
                                $vehicleNumberSql = "SELECT DISTINCT vehicle_number FROM journeys $whereSQL order by vehicle_number asc";
                                $vehicleNumberResult = $con->query($vehicleNumberSql);
                                while ($row = $vehicleNumberResult->fetch_assoc()) {
                                    // Mark the selected option as "selected"
                                    $selected = (isset($_POST['vehicle_number']) && $_POST['vehicle_number'] == $row['vehicle_number']) ? 'selected' : '';
                                    echo "<option value='" . $row['vehicle_number'] . "' $selected>" . $row['vehicle_number'] . "</option>";
                                }
                                ?>
                            </select>
                        </th>
                        
                        <th>
    <select name="distance_filter" id="distance_filter">
        <option value="">Distance > 300</option>
        <option value="show_all" <?php echo (isset($_POST['distance_filter']) && $_POST['distance_filter'] == 'show_all') ? 'selected' : ''; ?>>Show All</option>
        <?php
        // Fetch unique distance values based on the filter conditions
        $distanceSql = "SELECT distance FROM journeys WHERE distance > 300 order by distance asc"; // Use $whereSQL to filter
        $distanceResult = $con->query($distanceSql);

        if ($distanceResult->num_rows > 0) {
            while ($row = $distanceResult->fetch_assoc()) {
                // Check if the current option is the selected value
                $selected = (isset($_POST['distance_filter']) && $_POST['distance_filter'] == $row['distance']) ? 'selected' : '';
                // Append 'km' to the displayed option
                echo "<option value='" . $row['distance'] . "' $selected>" . $row['distance'] . " km</option>";
            }
        }
        ?>
    </select>
</th>

                        <th>
                            <input style = "height:25px ; width:100%" type="date" id="start_date" name="start_date" value="<?php echo $_POST['start_date'] ?? ''; ?>">
                        </th>
                        <th>
                            <input style = "height:25px ;  width:100%" type="date" id="end_date" name="end_date" value="<?php echo $_POST['end_date'] ?? ''; ?>">
                        </th>
                        <th>

                        <button type="submit" name="filter" style=" padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px;">
            Filter
        </button>


                        </th>
                        <th>

    </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <!-- <th></th> -->
                        <th></th>
                        <?php if ($_SESSION['role'] == 1) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php

                                        // Function to format date into 12-hour format
                                        function formatDate($dateTime) {
                                            if ($dateTime) {
                                                // Convert the datetime string into a timestamp
                                                $timestamp = strtotime($dateTime);
                                                // Format the timestamp to 12-hour format
                                                return date('Y-m-d h:i:s A', $timestamp); // e.g., 2024-10-28 02:30:45 PM
                                            }
                                            return '-'; // Default value if datetime is null
                                        }

                                        
                // 3. Fetch and display filtered results based on form input
                $sql = "SELECT * FROM journeys" . $whereSQL . " ORDER BY start_time DESC";
                $result = $con->query($sql);
                $i = 1; // Initialize serial number

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>"; // Increment serial number
                        echo "<td>" . ($row["emp_name"] ?? '-') . "</td>";

                        echo "<td>" . ($row["project_name"] ?? '-') . "</td>";
                        echo "<td>" . ($row["vehicle_number"] ?? '-') . "</td>";

                        echo "<td>" . ($row["distance"] ?? '0') . " km</td>";
                        echo "<td>" . formatDate($row["start_time"]) . "</td>";
                        echo "<td>" . formatDate($row["end_time"]) . "</td>";
                        

                        
                        echo "<td>" . ($row["remark"] ?? '-') . "</td>";
                        if ($row["journey_status"]==0) {
                           echo "<td><a href='generate_pdf.php?id=" . $row["id"] . "' target='_blank' style='padding: 0px;border-radius: 5%;' >View Pdf</a></td>";
                        }
                            else {
                           echo "<td><a href='generate_pdf.php?id=" . $row["id"] . "' target='_blank' style='padding: 0px; color:rgb(255, 70, 70); border-radius: 5%;' >Incomplete Journey</a></td>";
                       };
                        echo "<td>" . ($row["start_kilometer"] ?? '-') . "</td>";
                        echo "<td>" . ($row["end_kilometer"] ?? '-') . "</td>";
// Check if start_photo exists
if (!empty($row["start_photo"])) {
    // If it exists, show "Yes" and set up the modal to open the image
    echo "<td><a href='javascript:void(0);' onclick='openModal(\"" . $row["start_photo"] . "\")'>Yes</a></td>";
} else {
    // If it doesn't exist, show "No"
    echo "<td>No</td>";
}
                        
// Check if end_photo exists
if (!empty($row["end_photo"])) {
    // If it exists, show "Yes" and set up the modal to open the image
    echo "<td><a href='javascript:void(0);' onclick='openModal(\"" . $row["end_photo"] . "\")'>Yes</a></td>";
} else {
    // If it doesn't exist, show "No"
    echo "<td>No</td>";
}
                        echo "<td>" . ($row["start_location"] ?? '-') . "</td>";
                        echo "<td>" . ($row["end_location"] ?? '-') . "</td>";
        

                                  
                        if ($_SESSION['role'] == 1) {
                            echo "<td><button class='delete-btn' data-id='" . $row['id'] . "' style='padding: 5px 20px; background:#ef1212 ;border: none; border-radius: 20px; color:white'>Delete</button></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No data found</td></tr>";
                }

                $con->close();
                ?>
                </tbody>
            </table>
        </div>

    </form>
</div>
<!-- Include jQuery and DataTables JS and CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>


<script>
$(document).ready(function() {
    var table = $('#employeeTable').DataTable({
        responsive: true, // This will make the table responsive

        columnDefs: [
            { orderable: false, targets: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14] } // Adjust as necessary
        ]
    });

    // Update serial numbers dynamically on table draw (e.g., after search or filter)
    table.on('draw.dt', function() {
        var pageInfo = table.page.info();
        table.column(0, { page: 'current' }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1 + pageInfo.start;
        });
    });

    // Close the modal when the user clicks outside of the image
    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target === modal) { // Check if the clicked element is the modal itself
            modal.style.display = "none";
        }
    };
});
</script>


<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<?php include ('includes/footer.php'); ?>
