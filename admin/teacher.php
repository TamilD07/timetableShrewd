<?php 
include('../config.php');

// Check if a new teacher is added and redirect accordingly
if (isset($_GET['teacher_added']) && $_GET['teacher_added'] == 'true') {
    header("Location: admindashboard.php");
    exit;
}

// Display data
echo "<table border='1' class='table'>";

echo "<tr class='danger'>
<th colspan='11'>
<a href='admindashboard.php?info=add_teacher'>Add New</a>
</th></tr>";
echo "<Tr>
<th>Teacher Id</th>
<th>Teacher Name</th>
<th>Email</th>
<th>Password</th>
<th>Mobile</th>
<th>Address</th>
<th>Department</th>
<th>Update</th>
<th>Delete</th>
</tr>";

$que = mysqli_query($con, "SELECT * FROM teacher");
while ($res = mysqli_fetch_array($que)) {
    echo "<tr>";
    echo "<td>".$res['teacher_id']."</td>";
    echo "<td>".$res['name']."</td>";
    echo "<td>".$res['eid']."</td>";
    echo "<td>".$res['password']."</td>";
    echo "<td>".$res['mob']."</td>";
    echo "<td>".$res['address']."</td>";

    // Display department name
    $que2 = mysqli_query($con, "SELECT * FROM department WHERE department_id='".$res['department_id']."'");
    $res2 = mysqli_fetch_array($que2);
    echo "<td>".$res2['department_name']."</td>";

    echo "<td><a href='admindashboard.php?info=updateteacher&teacher_id=".$res['teacher_id']."'>Update</a></td>";
    echo "<td><a href='javascript:deleteData(\"".$res['teacher_id']."\")'>Delete</a></td>";
    echo "</tr>";
}

echo "</table>";   
?>
