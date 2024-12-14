<?php 
include('../config.php');

// Check if a new department is added and redirect accordingly
if (isset($_GET['department_added']) && $_GET['department_added'] == 'true') {
    header("Location: admindashboard.php");
    exit;
}

// Display data
echo "<table border='1' class='table table-striped'>";

echo "<tr class='danger'><th colspan='5'><a href='admindashboard.php?info=add_course'>Add New</a></th></tr>";

echo "<tr><th>Id</th><th>Department</th><th>Stream</th><th>Update</th><th>Delete</th></tr>";

// Fetch departments grouped by stream
$streams = array("CSE", "BTECH","Mtech"); // Add more streams if needed
foreach ($streams as $stream) {
    echo "<tr class='info'><th colspan='5'>$stream Stream</th></tr>";

    $que = mysqli_query($con, "SELECT * FROM department WHERE stream='$stream'");
    while ($res = mysqli_fetch_array($que)) {
        echo "<tr>";
        echo "<td>".$res['department_id']."</td>";
        echo "<td>".$res['department_name']."</td>";
        echo "<td>".$res['stream']."</td>";
        echo "<td><a href='admindashboard.php?info=updatecourse&department_id=".$res['department_id']."'>Update</a></td>";
        echo "<td><a href='javascript:deleteData(\"".$res['department_id']."\")'>Delete</a></td>";
        echo "</tr>";
    }
}

echo "</table>";   
?>
