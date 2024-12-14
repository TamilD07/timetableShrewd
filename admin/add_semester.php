<?php 
include('../config.php');

// Initialize $err variable to prevent undefined variable notice
$err = "";

if(isset($_POST['save'])) {
    // Sanitize input to prevent SQL injection
    $stream = mysqli_real_escape_string($con, $_POST['stream']);
    $s = mysqli_real_escape_string($con, $_POST['s']);
    $c = mysqli_real_escape_string($con, $_POST['c']);

    // Check if the semester already exists for the selected department
    $que = mysqli_query($con, "SELECT * FROM semester WHERE semester_name='$s' AND department_id='$c'");  
    $row = mysqli_num_rows($que);
    if($row > 0) {
        $err = "<font color='red'>This Semester already exists for the selected department</font>";
    } else {
        // Insert new semester into the database
        mysqli_query($con, "INSERT INTO semester (stream, semester_name, department_id) VALUES ('$stream', '$s', '$c')");  
        $err = "<font color='blue'>Congratulations! Your Data Saved!!!</font>";
    }
}
?>

<div class="row">
    <div class="col-md-5">
        <h2>Add Semester</h2>
        <form method="POST" enctype="multipart/form-data">
            <table border="0" cellspacing="5" cellpadding="5" class="table">
                <tr>
                    <td colspan="2"><?php echo $err; ?></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Select Stream</th>
                    <td width="213">
                        <select name="stream" id="stream" class="form-control" onchange="fetchDepartments(this.value)">
                            <option disabled selected>Select Stream</option>
                            <?php 
                            // Fetch streams from the database and populate the dropdown
                            $streams = mysqli_query($con, "SELECT DISTINCT stream FROM department");
                            while($stream = mysqli_fetch_array($streams)) {
                                echo "<option value='" . $stream['stream'] . "'>" . $stream['stream'] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Select Department</th>
                    <td width="213">
                        <select name="c" id="department" class="form-control">
                            <option disabled selected>Select Department</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Semester ID</th>
                    <td width="213"><input type="text" name="s" class="form-control"/></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Add Semester" name="save" class="btn btn-success" />
                        <input type="reset" value="Reset" class="btn btn-success"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
// Function to fetch departments based on the selected stream
function fetchDepartments(stream) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("department").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "fetch_departments.php?stream=" + stream, true);
    xmlhttp.send();
}
</script>
