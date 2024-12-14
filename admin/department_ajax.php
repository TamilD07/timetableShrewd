 <?php
include('../config.php');

// Check if stream is provided
if(isset($_GET['stream'])) {
    // Sanitize input
    $stream = mysqli_real_escape_string($con, $_GET['stream']);

    // Fetch departments based on stream
    $query = "SELECT * FROM department WHERE stream='$stream'";
    $result = mysqli_query($con, $query);

    // Check if any department found
    if(mysqli_num_rows($result) > 0) {
        // Generate HTML options for departments
        $output = "<option disabled selected>Select Department</option>";
        while($row = mysqli_fetch_assoc($result)) {
            $output .= "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
        }
    } else {
        $output = "<option disabled selected>No departments found</option>";
    }

    // Output the generated HTML
    echo $output;
} else {
    // If stream is not provided, output error message
    echo "Invalid request";
}
?>
