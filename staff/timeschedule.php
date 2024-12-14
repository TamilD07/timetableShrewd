<?php 
echo "<table border='1' class='table' height='500px'>";
echo "<tr>
<th><font color='#000000'><h3>Time Schedule Id</h3></font></th>
<th><font color='#000000'><h3>Department</h3></font></th>
<th><font color='#000000'><h3>Subject Name</h3></font></th>
<th><font color='#000000'><h3>Semester Name</h3></font></th>
<th><font color='#000000'><h3>Teacher Name</h3></font></th>
<th><font color='#000000'><h3>Time</h3></font></th>
<th><font color='#000000'><h3>Date</h3></font></th>
</tr>";

$que=mysqli_query($con,"SELECT * FROM timeschedule WHERE teacher_id='".$_SESSION['teacher_id']."'");
while($res=mysqli_fetch_array($que)) {
    echo "<tr>";
    echo "<td style='color:black'>".$res['timeschedule_id']."</td>";
    
    // Display department name
    $que2=mysqli_query($con,"SELECT * FROM department WHERE department_name='".$res['department_name']."'");
    $res2=mysqli_fetch_array($que2);
    echo "<td style='color:black'>".$res2['department_name']."</td>";
    
    // Display subject name
    $que3=mysqli_query($con,"SELECT * FROM subject WHERE subject_name='".$res['subject_name']."'");
    $res3=mysqli_fetch_array($que3);
    echo "<td style='color:black'>".$res3['subject_name']."</td>";
    
    // Display semester name
    $que4=mysqli_query($con,"SELECT * FROM semester WHERE semester_name='".$res['semester_name']."'");
    $res4=mysqli_fetch_array($que4);
    echo "<td style='color:black'>".$res4['semester_name']."</td>";
    
    // Display teacher name
    $que5=mysqli_query($con,"SELECT * FROM teacher WHERE teacher_id='".$res['teacher_id']."'");
    $res5=mysqli_fetch_array($que5);
    echo "<td style='color:black'>".$res5['name']."</td>";
    echo "<td style='color:black'>".$res['time']."</td>";
    echo "<td style='color:black'>".$res['date']."</td>";
    
    echo "</tr>";
}
echo "</table>";  
?>