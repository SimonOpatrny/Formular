<?php 
$host = "localhost"; 
$user = "root";     
$pass = "root";     
$dbname = "login_system"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
