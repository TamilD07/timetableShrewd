<select>
    <option value="" selected="selected" disabled="disabled">Select Semester</option>
    <?php 
    include('../config.php');
    $department_id = $_GET['id']; // Retrieving department ID from URL parameter
    $q = mysqli_query($con, "SELECT * FROM semester WHERE department_id='$department_id'");
    while ($res = mysqli_fetch_assoc($q)) {
        echo "<option value='".$res['sem_id']."'>".$res['semester_name']."</option>";
    }
    ?>
</select>
