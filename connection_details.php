connection.php

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "employee";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// if($conn){
//     echo "Connection Esablished!!!";
// }

define('SITE_URL',"http://localhost/Sample/")
?>


config.php

<?php
// $servername = "localhost";
// $username = "u221987201_travel_root";
// $password = "Travel_Root_01";
// $dbname = "u221987201_travel";

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "employee";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



connection.php
<?php
$servername = "localhost";
$username = "u221987201_travel_root";
$password = "Travel_Root_01";
$dbname = "u221987201_travel";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
// if($conn){
//     echo "Connection Esablished!!!";
// }
define('SITE_URL',"http://pfepltech.com/travelsystem/")
?>



config.php
<?php
$servername = "localhost";
$username = "u221987201_travel_root";
$password = "Travel_Root_01";
$dbname = "u221987201_travel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

