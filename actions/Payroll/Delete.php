<?php
if(isset($_POST['btn_delete_modal'])){
    include "../../config.php";
     $cutOffID = $_POST['name_CutoffID'];

    $sql = "DELETE FROM `cutoff_tb` WHERE `col_ID` = '$cutOffID'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM `empcutoff_tb` WHERE `cutOff_ID` = '$cutOffID'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
    
            header ("Location: ../../cutoff.php?deleted");
        }
        else {
            header ("Location: ../../cutoff.php?notdeleted");
        }

    }
    else {
        header ("Location: ../../cutoff.php?notdeleted");
    }

}
    
?>