<?php 
include('../config.php');

// Check if a new semester is added and redirect accordingly
if (isset($_GET['semester_added']) && $_GET['semester_added'] == 'true') {
    header("Location: admindashboard.php");
    exit;
}

// Display data
echo "<table border='1' class='table'>";

echo "<tr class='danger'><th colspan='5'><a href='admindashboard.php?info=add_semester'>Add New</a></th></tr>";

echo "<tr><th>Sem Id</th><th>Semester</th><th>Department</th><th>Update</th><th>Delete</th></tr>";

$que = mysqli_query($con, "SELECT * FROM semester");

while ($res = mysqli_fetch_array($que)) {
    echo "<tr>";
    echo "<td>".$res['sem_id']."</td>";
    echo "<td>".$res['semester_name']."</td>";
    
    // Display department name
    $que1 = mysqli_query($con, "SELECT * FROM department WHERE department_id='".$res['department_id']."'");
    $res1 = mysqli_fetch_array($que1);
    
    echo "<td>".$res1['department_name']."</td>";
    echo "<td><a href='admindashboard.php?info=updatesemester&sem_id=".$res['sem_id']."'>Update</a></td>";
    echo "<td><a href='javascript:deleteData(\"".$res['sem_id']."\")'>Delete</a></td>";
    echo "</tr>";
}

echo "</table>";   
?>
