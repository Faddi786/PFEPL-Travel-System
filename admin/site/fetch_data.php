<?php 
include ('../includes-admin/connection.php');

$output= array();
$sql = "SELECT sites.site_id, sites.site_name, project.project_name FROM sites 
        JOIN project ON sites.project_id = project.project_id";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'site_id',
    1 => 'site_name',
    2 => 'project_name'
);

if(isset($_POST['search']['value']))
{
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE sites.site_name LIKE '%".$search_value."%'";
    $sql .= " OR sites.site_id LIKE '%".$search_value."%'";
    $sql .= " OR project.project_name LIKE '%".$search_value."%'";
}

if(isset($_POST['order']))
{
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
    $sql .= " ORDER BY sites.site_id DESC";
}

if($_POST['length'] != -1)
{
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  ".$start.", ".$length;
}   

$query = mysqli_query($con,$sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while($row = mysqli_fetch_assoc($query))
{
    $sub_array = array();
    $sub_array[] = $row['site_id'];
    $sub_array[] = $row['site_name'];
    $sub_array[] = $row['project_name'];
    $sub_array[] = '<a href="javascript:void();" data-id="'.$row['site_id'].'"  class="btn btn-secondary btn-sm editbtn" >Edit</a>  
                    <a href="javascript:void();" data-id="'.$row['site_id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
    $data[] = $sub_array;
}

$output = array(
    'draw'=> intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);
echo json_encode($output);
?>
