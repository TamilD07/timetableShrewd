<?php 
include('../config.php');
include("timetablegen.php");
extract($_POST);

if(isset($generate) || isset($regenerate))
{
    $_GET['generated'] = "true";
}
else{
    $_GET['generated'] = "";
}
// Define the lab hour time slots
$labHourSlots = array(
    array('start' => '08:45', 'end' => '10:45'),
    array('start' => '11:00', 'end' => '13:00'),
    array('start' => '13:00', 'end' => '15:00'),
    array('start' => '15:15', 'end' => '17:15')
);

?>

<script>
function showSubject(str)
{
if (str=="")
{
document.getElementById("txtHint").innerHTML="";
return;
}

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}



xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
document.getElementById("subject").innerHTML=xmlhttp.responseText;
}
}
//alert(str);
xmlhttp.open("GET","subject_ajax.php?id="+str,true);
xmlhttp.send();
}

function showSemester(str)
{
if (str=="")
{
document.getElementById("txtHint").innerHTML="";
return;
}

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}



xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
document.getElementById("semester").innerHTML=xmlhttp.responseText;
}
}
//alert(str);
xmlhttp.open("GET","semester_ajax.php?id="+str,true);
xmlhttp.send();
}
</script>


<div class="row">
<div class="col-sm-12">
<h2>Generate Time Table</h2>
<form method="POST" enctype="multipart/form-data">
  <table border="0" class="table">
  <tr>
  <td colspan="2"><?php echo @$err; ?></td>
  </tr>
  <tr>
    <th width="237" scope="row">Select Department</th>
    <td width="213">
    <select name="courseid" class="form-control" onchange="showSemester(this.value)" id="courseid">
    <option disabled selected >Select Department</option>
    <?php 
    $dep=mysqli_query($con,"select * from department");
    while($dp=mysqli_fetch_array($dep))
    {
    $dp_id=$dp[0];
    echo "<option value='$dp_id'>".$dp[1]."</option>";
    }
    ?>

    </select>
    </td>
  </tr>
    
 <tr>
    <th width="237" scope="row">Select Semester</th>
    <td width="213">
    <select name="s" id="semester" onchange="showSubject(this.value)" class="form-control"/>
    <option disabled selected >Select Semester</option>
    
    </select>
    </td>
  </tr>

  <tr>
    <th colspan="1" scope="row"></th>
    <td>
    <input type="submit" value="Generate Time Table" name="generate" class="btn btn-success" />
    </td>
  </tr>
  <?php
     if($_GET['generated']){
  ?>
  <tr>
    <td>
    <!-- <input type="submit" value="Regenerate" name="regenerate" class="btn btn-primary" /> -->
    </td>
    <td class="text-right">
    <!-- <input type="submit" value="Save" name="save" class="btn btn-primary text-right" /> -->
    </td>
  </tr>
  <?php
     }
  ?>

  </table>
  </form>
  </div>
  </div>
<div>
<?php
if($_GET['generated']){
  $check_query = "SELECT * FROM timeschedule WHERE department_name = '$branch' AND semester_name = '$semester'";
  $result = mysqli_query($con, $check_query);
  if(mysqli_num_rows($result) > 0) {
      // Fetch and display existing timetable
      echo "<table border='1' class='table'>";
      echo "<tr><th>Department</th><th>Semester</th><th>Subject</th><th>Time</th><th>Date</th><th>Teacher ID</th></tr>";
      while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['department_name'] . "</td>";
          echo "<td>" . $row['semester_name'] . "</td>";
          echo "<td>" . $row['subject_name'] . "</td>";
          echo "<td>" . $row['time'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['teacher_id'] . "</td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
      $lunch = "LUNCH";

      $query = "select * from department where department_id = $courseid";
      $que=mysqli_query($con, $query);
      $row = mysqli_fetch_assoc($que);
      $branch = $row['department_name'];

      $query = "select * from semester where sem_id = $s";
      $que=mysqli_query($con, $query);
      $row = mysqli_fetch_assoc($que);
      $semester = $row['semester_name'];

      if($branch && $semester){
          echo "<div style='font-size: 32; color: blue'><b>".$branch." ".$semester." Semester</b></div><br>";
      }

      $weekTimeTable = generate_time_table($con, $courseid, $s, $department_name, $semester_name);

      if($weekTimeTable){
        // Display timetable
        echo "<table border='1' class='table'>";
        echo "<tr class='danger text-center'>
        <th class='text-center'>Days/Lecture</th>
        <th class='text-center'>Lecture 1<br>08:45-09:45</th>
        <th class='text-center'>Lecture 2<br>09:45-10:45</th>
        <th class='text-center'>Lecture 3<br>11:00-12:00</th>
        <th class='text-center'>Lecture 4<br>12:00-01:00</th>
        <th class='text-center'>lecture 5<br>01:00-02:00</th>
        <th class='text-center'>Lecture 6<br>02:00-03:00</th>
        <th class='text-center'>Lecture 7<br>03:15-04:15</th>
        <th class='text-center'>Lecture 8<br>04:15-05:15</th>";

        // Priority scheduling for labs and lunch
        for ($i = 0; $i < 5; $i++) {
            $labScheduled = false;
            $lunchScheduled = false;

            // Check if lab is scheduled from 1:00 to 3:00
            $labBetween1To3 = false;
            for ($j = 4; $j <= 5; $j++) {
                if ($weekTimeTable[$i][$j]['type'] === 'Lab') {
                    $labBetween1To3 = true;
                    break;
                }
            }

            if ($labBetween1To3) {
                // Set lunch hour from 12:00 to 1:00 if lab is allocated between 1:00 to 3:00
                $weekTimeTable[$i][3]['subject_name'] = 'LUNCH';
                $lunchScheduled = true;
            } elseif (!$lunchScheduled) {
                // Set lunch hour from 1:00 to 2:00 if no lab is allocated between 1:00 to 3:00
                $weekTimeTable[$i][4]['subject_name'] = 'LUNCH';
                $lunchScheduled = true;
            }

            // Schedule remaining subjects based on lecture_per_week
            if (!$labScheduled) {
                // Scheduling additional subjects based on lecture_per_week
                for ($j = 0; $j < 8; $j++) {
                    if ($weekTimeTable[$i][$j]['type'] === '') {  
                        $weekTimeTable[$i][$j]['type'] = 'theory'; // Example assignment
                        $weekTimeTable[$i][$j]['subject_name'] = 'Subject'; // Example subject name
                    }
                }
            }

            // Output timetable
            echo "<tr>";
            echo "<th center class='danger text-center'>" . $weekDays[$i] . "</th>";
            for ($j = 0; $j < 8; $j++) {
                if ($weekTimeTable[$i][$j]['type'] === 'Lab') {
                    echo "<th class=' text-center' colspan=2 style='color: green'>" . $weekTimeTable[$i][$j]['subject_name'] . "</th>";
                    $j++; // Skip next time slot as lab hours are double
                } else {
                    echo "<th class=' text-center' style='color: red'>" . $weekTimeTable[$i][$j]['subject_name'] . "</th>";
                }
            }
            echo "</tr>";
        }

        // Insert the generated timetable into the database
        $insert_query = "INSERT INTO timeschedule (department_name, semester_name, subject_name, time, date, teacher_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        if ($stmt) {
            $date = date("Y-m-d");
            $teacher_id = 1; // Assuming a default teacher ID for now

            foreach ($weekTimeTable as $index => $daySchedule) {
                foreach ($daySchedule as $slot) {
                    $subject_name = $slot['subject_name'];
                    $time = $slot['start'] . ' - ' . $slot['end'];
                    mysqli_stmt_bind_param($stmt, "sssssi", $branch, $semester, $subject_name, $time, $date, $teacher_id);
                    mysqli_stmt_execute($stmt);       
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error in preparing the statement: " . mysqli_error($con);
        }
    } else {
        echo "<div style='text-size=28; color: red'><b>Not enough data for selected Course and semester.</b></div>";
    }
  }
}
?>