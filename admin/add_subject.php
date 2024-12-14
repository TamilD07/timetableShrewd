<?php 
include('../config.php');
extract($_POST);

// Fetch streams
$streams_query = mysqli_query($con, "SELECT DISTINCT stream FROM department");
$streams_options = "";
while ($stream = mysqli_fetch_array($streams_query)) {
    $streams_options .= "<option value='".$stream['stream']."'>".$stream['stream']."</option>";
}

// Fetch teachers
$teachers_query = mysqli_query($con, "SELECT * FROM teacher"); // Assuming you have a table named 'teacher'
$teachers_options = "";
if(mysqli_num_rows($teachers_query) > 0) {
    while ($teacher = mysqli_fetch_array($teachers_query)) {
        $teachers_options .= "<option value='".$teacher['teacher_id']."'>".$teacher['teacher_id']."</option>";
    }
} else {
    $teachers_options = "<option value='' disabled>No teachers available</option>";
}

if(isset($save))
{
    // Retrieve sem_id based on selected stream and semester
    $sem_query = mysqli_query($con, "SELECT sem_id FROM semester WHERE stream='$stream' AND semester_name='$semester'");
    $sem_row = mysqli_fetch_array($sem_query);
    $sem_id = $sem_row['sem_id'];

    // Retrieve department_id
    $department_id = $_POST['department_id'];

    $que=mysqli_query($con,"SELECT * FROM subject WHERE subject_name='$subname'");
    $row=mysqli_num_rows($que);
    if($row)
    {
        $err="<font color='red'>This Subject already exists</font>";
    }
    else
    {
        // Assuming $s is the stream, $courseid is the course id, and $semester is the semester
        mysqli_query($con,"INSERT INTO subject ( subject_name, sem_id, department_id, teacher_id, lecture_per_week, type) VALUES ( '$subname', '$semester', '$department_id', '$teacher_id', '$lpw', '$type')");    
        $err="<font color='blue'>Congrats Your Data Saved!!!</font>";
    }    
}
?>

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

// Function to display teacher name based on teacher ID selection
function displayTeacherName() {
    var teacherId = document.getElementById("teacher_id").value;
    var teacherName = document.getElementById("teacher_name");
    teacherName.textContent = ""; // Reset teacher name
    // Send an AJAX request to fetch teacher name based on ID
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                teacherName.textContent = xhr.responseText || "Teacher not found";
            } else {
                teacherName.textContent = "Error fetching teacher name";
            }
        }
    };
    xhr.open("GET", "fetch_teacher_name.php?id=" + teacherId, true);
    xhr.send();
}
</script>

<style>
    /* Style for the dropdown menu */
    select.form-control {
        color: black; /* Text color */
        background-color: white; /* Background color */
        border: 1px solid #ced4da; /* Border color */
        border-radius: 0.25rem; /* Border radius */
        padding: 0.375rem 0.75rem; /* Padding */
        line-height: 1.5; /* Line height */
        appearance: none; /* Remove default appearance */
    }

    /* Style for the options in the dropdown menu */
    select.form-control option {
        color: black; /* Text color */
    }
</style>

<div class="row">
    <div class="col-md-8">
        <h2>Add Subject</h2>
        <form method="POST" enctype="multipart/form-data">
            <table border="0" cellspacing="5" cellpadding="5" class="table">
                <tr>
                    <td colspan="2"><?php echo @$err; ?></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Select Stream</th>
                    <td width="213">
                        <select name="stream" id="stream" onchange="fetchDepartments(this.value)" class="form-control">
                            <option disabled selected>Select Stream</option>
                            <?php echo $streams_options; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Select Department</th>
                    <td width="213">
                        <select name="department_id" id="department" class="form-control">
                            <option disabled selected>Select Department</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Select Teacher ID</th>
                    <td width="213">
                        <select name="teacher_id" id="teacher_id" class="form-control" onchange="displayTeacherName()">
                            <option disabled selected>Select Teacher ID</option>
                            <?php echo $teachers_options; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Teacher Name</th>
                    <td width="213">
                        <span id="teacher_name"></span>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Subject Name</th>
                    <td width="213"><input type="text" name="subname" class="form-control"/></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Semester</th>
                    <td width="213"><input type="text" name="semester" class="form-control"/></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Lecture/Week</th>
                    <td width="213"><input type="number" name="lpw" class="form-control"/></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Type</th>
                    <td width="213">
                        <input class="form-check-input" type="radio" name="type" value="Theory" checked>
                        <label class="form-check-label" for="Theory">Theory</label>
                        <input class="form-check-input" type="radio" name="type" value="Lab">
                        <label class="form-check-label" for="Lab">Lab</label>    
                    </td>
                </tr>
                <tr>
                    <th colspan="1" scope="row"></th>
                    <td>
                        <input type="submit" value="Add subject" name="save" class="btn btn-success" />
                        <input type="reset" value="Reset" class="btn btn-success"/>
                    </td>
                </tr>  
            </table>
        </form>
    </div>
</div>

