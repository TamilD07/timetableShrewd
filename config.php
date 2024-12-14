<?php 
error_reporting(1);
$con = mysqli_connect("localhost:33066", "root", "", "timetable");

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
?>
