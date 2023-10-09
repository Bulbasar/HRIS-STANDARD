<?php 
    include "../../config.php";
    

if(isset($_POST['update_data'])) {
    $id = $_POST['name_pay'];
    $editName = $_POST['payofrule'];


    $sql = "UPDATE `Payrule_tb` SET `rule_name` = '$editName' WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if($result) {
        header("Location: ../../Payrules.php?msg=Data updated successfully");
    }
    else {
        echo "Failed: " . mysqli_error($conn);
    }
}

?>