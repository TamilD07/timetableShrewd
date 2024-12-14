<?php 
include('../config.php');
extract($_POST);
if(isset($save))
{
    $que=mysqli_query($con,"SELECT * FROM department WHERE department_name='$c' AND stream='$stream'");    
    $row=mysqli_num_rows($que);
    if($row)
    {
        $err="<font color='red'>This department already exists under the selected stream</font>";
    }
    else
    {
        mysqli_query($con,"INSERT INTO department VALUES(null,'$c', '$stream')");    

        $err="<font color='blue'>Congrats Your Data Saved!!!</font>";
    }    
}
?>
<div class="row">
    <div class="col-md-5">
        <h2>Add Department</h2>
        <form method="POST" enctype="multipart/form-data">
            <table  class="table">
                <tr>
                    <td colspan="2"><?php echo @$err; ?></td>
                </tr>
                <tr>
                    <th width="237" scope="row">Stream </th>
                    <td width="213">
                        <select name="stream" class="form-control">
                            <option value="cse">cse</option>
                            <option value="BTech">BTech</option>
                            <option value="Mtech">Mtech</option>
                            <!-- Add more options if needed -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="237" scope="row">Department Name </th>
                    <td width="213"><input type="text" name="c" class="form-control"/></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Add Course" name="save" class="btn btn-success" />
                        <input type="reset" value="Reset" class="btn btn-success"/>
                    </td>
                </tr>  
            </table>
        </form>
    </div>
</div>
