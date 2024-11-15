<?php 
include ('../includes-admin/connection.php');

$output = array();
$sql = "SELECT * FROM vehicle";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'v_id',
    1 => 'v_name',
    2 => 'status', // Status column for sorting
);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE (v_name LIKE '%" . $search_value . "%' ";
    $sql .= " OR v_id LIKE '%" . $search_value . "%' ";

    // Check the first character of the search value
    $first_char = strtoupper(substr($search_value, 0, 1));

    if ($first_char === 'W') {
        $sql .= " OR status = 1"; // 1 for Working
    } elseif ($first_char === 'N') {
        $sql .= " OR status = 0"; // 0 for Not Working
    }

    // Ensure to close the WHERE clause
    $sql .= ")";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $columns[$column_name] . " " . $order . "";
} else {
    $sql .= " ORDER BY v_id DESC";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}    

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['v_id'];       // Vehicle ID
    $sub_array[] = $row['v_name'];     // Vehicle Name

    // Check the status and set display text accordingly
    if ($row['status'] == 1) {
        // If status is 1 (Working), fetch the project name and employee name from the journeys table
        $vehicle_number = $row['v_id'];
        $sql_journey = "SELECT project_name, emp_name FROM journeys WHERE vehicle_number = ? AND journey_status = 1 LIMIT 1";
        $stmt_journey = $con->prepare($sql_journey);
        $stmt_journey->bind_param('s', $vehicle_number);
        $stmt_journey->execute();
        $result_journey = $stmt_journey->get_result();
        
        if ($result_journey->num_rows > 0) {
            // Fetch the project name and employee name if found
            $journey_row = $result_journey->fetch_assoc();
            $sub_array[] = $journey_row['project_name'];  // Project Name
            $sub_array[] = $journey_row['emp_name'];      // Employee Name
        } else {
            $sub_array[] = "Working (No project assigned)";  // No project found
            $sub_array[] = "-";  // No employee name found
        }

        $stmt_journey->close();
    } else {
        $sub_array[] = "-";    // If status is 0 (Not Working)
        $sub_array[] = "-";    // No employee name for not working status
    }

    // Action buttons
    $sub_array[] = '<a href="javascript:void();" data-id="' . $row['v_id'] . '" class="btn btn-secondary btn-sm editbtn">Edit</a> ' .
                   '<a href="javascript:void();" data-id="' . $row['v_id'] . '" class="btn btn-danger btn-sm deleteBtn">Delete</a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);
echo json_encode($output);
?>
