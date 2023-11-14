<?php
include '../../config.php';

if (isset($_POST['btn_save'])) {
    $empids = $_POST['name_empId'][0];
    $empIDs = explode(",", $empids);

    $type = $_POST['name_type'];
    $frequency = $_POST['name_frequency'];
    $month = $_POST['name_Month'];
    $year = $_POST['name_year'];
    $strDate = $_POST['name_strDate'];
    $endDate = $_POST['name_endDate'];
    $Cut_num = $_POST['name_cutoffNum'];

    foreach ($empIDs as $empID) {
        // Check if employee has attendances within the date range
        $checkAttendances = "SELECT * FROM attendances WHERE `empid` = '$empID' AND `status` = 'Present' AND `date` BETWEEN '$strDate' AND '$endDate'";
        $attendancesRun = mysqli_query($conn, $checkAttendances);

        if (mysqli_num_rows($attendancesRun) > 0) {

            if (!empty($empID)) {
                $CheckcutOff = "SELECT * FROM cutoff_tb WHERE ('$strDate' BETWEEN `col_startDate` AND `col_endDate`) OR ('$endDate' BETWEEN `col_startDate` AND `col_endDate`)";
                $cutoffRun = mysqli_query($conn, $CheckcutOff);

                if (mysqli_num_rows($cutoffRun) == 0) {
                    // Insert into cutoff_tb table
                    $sql = "INSERT INTO cutoff_tb (`col_type`, `col_frequency`, `col_month`, `col_year`, `col_startDate`, `col_endDate`, `col_cutOffNum`)
                            VALUES ('$type', '$frequency', '$month', '$year', '$strDate', '$endDate', '$Cut_num')";
                    $sqlrun = mysqli_query($conn, $sql);

                    if ($sqlrun) {
                        $cutoff = "SELECT max(col_ID) AS cutoffID FROM cutoff_tb";
                        $cutoffRun = mysqli_query($conn, $cutoff);

                        if (mysqli_num_rows($cutoffRun) > 0) {
                            $row = mysqli_fetch_assoc($cutoffRun);
                            $cutID = $row['cutoffID'];

                            foreach ($empIDs as $empID) {
                                // Insert into empcutoff_tb table
                                $checkPresentStatus = "SELECT * FROM attendances WHERE `empid` = '$empID' AND `status` = 'Present' AND `date` BETWEEN '$strDate' AND '$endDate'";
                                $presentStatusRun = mysqli_query($conn, $checkPresentStatus);
                                if (mysqli_num_rows($presentStatusRun) == 0) {
                                    continue; // Skip this employee if no present status found
                                }
                                $query = "INSERT INTO empcutoff_tb (`cutOff_ID`, `emp_ID`) VALUES ('$cutID', '$empID')";
                                $queryrun = mysqli_query($conn, $query);
                            }

                            header("Location: ../../cutoff.php?inserted");
                            exit();
                        }
                    } else {
                        header("Location: ../../cutoff.php?notfound");
                        exit();
                    }
                } else {
                    header("Location: ../../cutoff.php?existed");
                    exit();
                }
            } else {
                header("Location: ../../cutoff.php?noattendance");
                exit();
            }
        }
    }
}


?>
