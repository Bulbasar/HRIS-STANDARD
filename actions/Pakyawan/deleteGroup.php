<?php
include '../../config.php';

if (isset($_POST['delete_data'])) {
    $group_name = $_POST['group_name'];

    // Check if the group_name exists in both tables
    $check_query = "SELECT 1 FROM pakyawan_group_tb WHERE `group_name` = '$group_name' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // group_name exists in pakyawan_group_tb, don't delete
        header("Location: ../../pakyawan_group?cannotDelete");
        exit();
    }

    $check_query = "SELECT 1 FROM employee_pakyawan_work_tb WHERE `group_name` = '$group_name' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // group_name exists in employee_pakyawan_work_tb, don't delete
        header("Location: ../../pakyawan_group?cannotDelete");
        exit();
    }

    // Start a transaction
    mysqli_begin_transaction($conn);

    // Delete from pakyawan_group_tb
    $query1 = "DELETE FROM pakyawan_group_tb WHERE `group_name` = '$group_name'";
    $query_run1 = mysqli_query($conn, $query1);

    // Delete from employee_pakyawan_work_tb
    $query2 = "DELETE FROM employee_pakyawan_work_tb WHERE `group_name` = '$group_name'";
    $query_run2 = mysqli_query($conn, $query2);

    if ($query_run1 && $query_run2) {
        // Commit the transaction if both deletions were successful
        mysqli_commit($conn);
        header("Location: ../../pakyawan_group?deleted");
    } else {
        // Rollback the transaction if any deletion fails
        mysqli_rollback($conn);
        echo "Failed: " . mysqli_error($conn);
    }
}
?>
