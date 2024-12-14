<?php
// fetch_departments.php
include('../config.php');

if(isset($_GET['stream'])) {
    $stream = $_GET['stream'];
    
    // Fetch departments based on the selected stream
    $departments_query = mysqli_query($con, "SELECT * FROM department WHERE stream = '$stream'");
    $departments_options = "";
    while ($department = mysqli_fetch_array($departments_query)) {
        $departments_options .= "<option value='".$department['department_id']."'>".$department['department_name']."</option>";
    }

    echo $departments_options;
}
?>
