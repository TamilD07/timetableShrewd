<?php
// semester_ajax.php

include('../config.php');

if(isset($_GET['stream']) && isset($_GET['departmentId'])) {
    $stream = mysqli_real_escape_string($con, $_GET['stream']);
    $departmentId = mysqli_real_escape_string($con, $_GET['departmentId']);
  
    // Query to fetch semesters based on the selected department and stream
    $query = "SELECT * FROM semester WHERE department_id = $departmentId";
    $result = mysqli_query($con, $query);
  
    if($result && mysqli_num_rows($result) > 0) {
      // Display fetched semesters as options in the select element
      echo "<option disabled selected>Select Semester</option>";
      while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='".$row['sem_id']."'>".$row['semester_name']."</option>";
      }
    } else {
      echo "<option disabled selected>No Semesters Available</option>";
    }
} else {
    echo "<option disabled selected>Error: Invalid Request</option>";
}
?>
