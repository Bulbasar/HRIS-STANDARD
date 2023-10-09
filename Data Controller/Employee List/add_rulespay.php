<?php 
    include "../../config.php";

    
    if(isset($_POST['add_payrule'])) {
        $payname = $_POST['payingrule'];

        $sql = "SELECT * FROM `Payrule_tb` WHERE `rule_name`='$payname'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            // Duplicate branch name found
            header("Location: ../../Payrules.php?error=Pay rule name already exists");
        }
        else {
            $sql = "INSERT INTO `Payrule_tb`(`rule_name`) 
            VALUES ('$payname')";
            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location: ../../Payrules.php?msg=New record created successfully");
            }
            else {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    }

?>