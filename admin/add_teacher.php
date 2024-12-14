<?php 
include('../config.php');
extract($_POST);

if(isset($save))
{
    $que=mysqli_query($con,"SELECT * FROM teacher WHERE eid='$e'");      
    $row=mysqli_num_rows($que);
    if($row)
    {
        $err="<font color='red'>This teacher already exists</font>";
    }
    else
    {
        mysqli_query($con,"INSERT INTO teacher VALUES(null,'$n','$e','$p', $m,'$a','$courseid')");  

        $err="<font color='blue'>Congrates Your Data Saved!!!</font>";
    }
}

?>

<script>
// Function to fetch and display departments based on stream
function showDepartments(stream) {
    var xhttp;
    if (stream == "") {
        document.getElementById("department").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("department").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "department_ajax.php?stream="+stream, true);
    xhttp.send();
}

// Function to fetch and display semesters based on department
function showSemester(department) {
    var xhttp;
    if (department == "") {
        document.getElementById("semester").innerHTML = "";
        return;
    }
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("semester").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "semesters_ajax.php?department="+department, true);
    xhttp.send();
}
</script>


<div class="row">
<div class="col-md-12">
<h2>Add Teacher</h2>
<form method="POST" enctype="multipart/form-data">
<table border="0" class="table">
  <tr>
  <td colspan="2"><?php echo @$err; ?></td>
  </tr>
   
   <tr>
    <th width="237" scope="row">Select Stream</th>
    <td width="213">
    <select name="stream" id="stream" onchange="showDepartments(this.value)" class="form-control">
        <option disabled selected>Select Stream</option>
        <?php 
        $streams=mysqli_query($con,"SELECT DISTINCT stream FROM department");
        while($stream=mysqli_fetch_array($streams))
        {
            echo "<option value='".$stream['stream']."'>".$stream['stream']."</option>";
        }
        ?>
    </select>
    </td>
  </tr>
  
  <tr>
    <th width="237" scope="row">Select Department</th>
    <td width="213">
    <select name="courseid" id="department" class="form-control">
        <option disabled selected>Select Department</option>
        <!-- Departments will be populated dynamically based on the selected stream -->
    </select>
    </td>
  </tr>
   
  <tr>
    <th width="237" scope="row">Teacher Name </th>
    <td width="213"><input type="text" name="n" class="form-control" placeholder="Enter your name"/></td>
  </tr>
  <tr>
    <th scope="row">Enter Your Email </th>
    <td><input type="email" name="e" class="form-control" placeholder="Enter your email"/></td>
  </tr>
  
  <tr>
    <th scope="row">Enter Your Password </th>
    <td><input type="password" name="p" class="form-control" placeholder="Enter your password"/></td>
  </tr>
  
  <tr>
    <th scope="row">Enter Your Mobile </th>
    <td><input type="number" name="m" class="form-control" placeholder="Enter your mobile"/></td>
  </tr>
  
  <tr>
    <th scope="row">Enter Your Address</th>
    <td><input type="text" name="a" class="form-control" placeholder="Enter your address"/></td>
  </tr>
 
  <tr>
    <th colspan="1" scope="row"></th>
    <td>
    <input type="submit" value="Add Teacher" name="save" class="btn btn-success" />
    <input type="reset" value="Reset" class="btn btn-success"/>
    </td>
  </tr>
</table>
</form>
</div>
</div>
