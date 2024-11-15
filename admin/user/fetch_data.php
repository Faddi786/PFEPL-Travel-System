<?php 
include ('../includes-admin/connection.php');

$output= array();
$sql = "SELECT * FROM emp ";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'emp_id',
	1 => 'emp_name',
	2 => 'password',
	3 => 'role',
);

if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " WHERE emp_name like '%".$search_value."%'";
	$sql .= " OR emp_id like '%".$search_value."%'";
}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY ".$columns[$column_name]." ".$order."";
}
else
{
	$sql .= " ORDER BY emp_id desc";
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
	$sub_array[] = $row['emp_id'];
	$sub_array[] = $row['emp_name'];
	$sub_array[] = $row['password'];
	$sub_array[] = $row['role'];
	$sub_array[] = '<a href="javascript:void();" data-id="'.$row['emp_id'].'"  class="btn btn-secondary btn-sm editbtn" >Edit</a>  <a href="javascript:void();" data-id="'.$row['emp_id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
	$data[] = $sub_array;
}

$output = array(
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
	'data'=>$data,
);
echo  json_encode($output);
