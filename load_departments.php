<?php
include 'config.php';

$sql = "SELECT col_ID, col_deptname FROM dept_tb";
$result = mysqli_query($conn, $sql);

$options = "";
echo "<option value disabled selected>Select Department</option>";
while ($row = mysqli_fetch_assoc($result)) {
    
    $options .= "<option value='".$row['col_ID']."'>" .$row['col_deptname'].  "</option>";
   
    
}


echo $options;
?>
