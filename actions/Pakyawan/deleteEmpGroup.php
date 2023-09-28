<?php
include '../../config.php';

if (isset($_POST['delete_data'])) {
    $empid = $_POST['empid'];

    // Start a transaction
    mysqli_begin_transaction($conn);

    // Delete from pakyawan_group_tb
    $query1 = "DELETE FROM pakyawan_group_tb WHERE `empid` = '$empid'";
    $query_run1 = mysqli_query($conn, $query1);

    // Delete from employee_pakyawan_work_tb
    $query2 = "DELETE FROM employee_pakyawan_work_tb WHERE `empid` = '$empid'";
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
