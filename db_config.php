
<?php
/* Database connection */
//$servername = 'awssprbotcom.csigzgfx7gdl.us-east-1.rds.amazonaws.com';
//$username = "awssprbotcom";
//$password = "Bd92U5LCg9L4";
//$dbname = "sprbqktz_iphone";

$servername = $_ENV{DATABASE_SERVER};
$username = "sprbqktz_iphone";
$password = "*KE5[f%%QQiF";
$dbname = "sprbqktz_iphone";


$connection =mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error($connection));
}
?>
