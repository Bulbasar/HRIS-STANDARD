<?php
if(isset($_POST['yesdelete'])){
    include "../../config.php";
     $cutoff13 = $_POST['cutoffname'];

    $sql = "DELETE FROM `thirteencutoff_tb` WHERE id = '$cutoff13'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM `empthirteen_tb` WHERE `cut_id` = '$cutoff13'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header ("Location: ../../13month.php?deleted");
        }
        else {
            header("Location: ../../13month.php?notdeleted");
        }

    }
    else {
        header("Location: ../../13month.php?notdeleted");
    }

}
    
?>