<?php
// fetch_teacher_name.php

include('../config.php'); // Include the file that establishes the database connection

if(isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
    
    // Query to fetch teacher name based on ID
    $query = mysqli_query($con, "SELECT name FROM teacher WHERE teacher_id = '$teacher_id'");
    if(mysqli_num_rows($query) > 0) {
        $teacher = mysqli_fetch_assoc($query);
        echo $teacher['name'];
    } else {
        echo "Teacher not found";
    }
} else {
    echo "Invalid request";
}
?>
