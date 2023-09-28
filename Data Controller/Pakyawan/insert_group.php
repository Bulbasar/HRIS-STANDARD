<?php
if (isset($_POST['submit'])) {
    $empidArray = $_POST['empid'][0];
    $empids = explode(",", $empidArray);
    $group_name = $_POST['group_name'];
    $frequency = $_POST['frequency'];

    include '../../config.php';

    // Check if the group_name already exists in the database
    $check_sql = "SELECT COUNT(*) FROM `pakyawan_group_tb` WHERE `group_name` = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $group_name);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_bind_result($check_stmt, $count);
    mysqli_stmt_fetch($check_stmt);
    mysqli_stmt_close($check_stmt);

    if ($count > 0) {
        // Group name already exists, do not insert
        header("Location: ../../pakyawan_group?duplicate");
        exit();
    }

    // Check if any of the employee IDs already exist in the database
    $existing_empids = [];
    foreach ($empids as $empID) {
        $empID = trim($empID);
        if (!empty($empID)) {
            // Convert the employee ID to a string to preserve leading zeroes
            $empID = strval($empID);

            echo "<br> <br>", $empID, "<br>";

            // Check if the employee ID exists
            $check_emp_sql = "SELECT COUNT(*) FROM `pakyawan_group_tb` WHERE `empid` = ?";
            $check_emp_stmt = mysqli_prepare($conn, $check_emp_sql);
            mysqli_stmt_bind_param($check_emp_stmt, "s", $empID);
            mysqli_stmt_execute($check_emp_stmt);
            mysqli_stmt_bind_result($check_emp_stmt, $emp_count);
            mysqli_stmt_fetch($check_emp_stmt);
            mysqli_stmt_close($check_emp_stmt);

            if ($emp_count > 0) {
                // Employee ID already exists, do not insert
                $existing_empids[] = $empID;
            } else {
                // Employee ID is unique, proceed with the insertion into pakyawan_group_tb
                $sql = "INSERT INTO `pakyawan_group_tb` (`empid`, `group_name` ,`frequency`)
                        VALUES (?, ? , ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $empID, $group_name, $frequency);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Insert into employee_pakyawan_work_tb table
                $pieceRateIds = json_decode($_POST['piece_rate_id_hidden']);
                foreach ($pieceRateIds as $pieceRateId) {
                    // Check if the combination of group_name, pieceRateId, and empID already exists
                    $check_sql = "SELECT COUNT(*) FROM `employee_pakyawan_work_tb` WHERE `group_name` = ? AND `piece_rate_id` = ? AND `empid` = ?";
                    $check_stmt = mysqli_prepare($conn, $check_sql);
                    mysqli_stmt_bind_param($check_stmt, "sss", $group_name, $pieceRateId, $empID);
                    mysqli_stmt_execute($check_stmt);
                    mysqli_stmt_bind_result($check_stmt, $count);
                    mysqli_stmt_fetch($check_stmt);
                    mysqli_stmt_close($check_stmt);

                    if ($count == 0) {
                        // Combination of group_name, pieceRateId, and empID is unique, proceed with the insertion
                        $stmt3 = $conn->prepare("INSERT INTO employee_pakyawan_work_tb (`group_name`, `piece_rate_id`, `empid`)
                                                VALUES (?, ?, ?)");
                        if (!$stmt3) {
                            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
                        }
                        $stmt3->bind_param("sss", $group_name, $pieceRateId, $empID);
                        $stmt3->execute();
                        if ($stmt3->errno) {
                            echo "<script>alert('Error: " . $stmt3->error . "');</script>";
                            exit;
                        }
                        $stmt3->close();
                    }
                }
            }
        }
    }

    // Check if any employee IDs were found to be duplicates
    if (!empty($existing_empids)) {
        // Some employee IDs already exist, add ?duplicate to the URL
        header("Location: ../../pakyawan_group?duplicate");
        exit();
    }

    // Assuming success if there were no errors and no exit() calls
    header("Location: ../../pakyawan_group?inserted");
    exit();

    mysqli_close($conn);
}
?>
