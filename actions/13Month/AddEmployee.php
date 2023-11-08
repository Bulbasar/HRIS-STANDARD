<?php
include '../../config.php';
    if(isset($_POST['Yes-addEmp'])){
        $addempCutoff = $_POST['cuttOff_emp'][0]; // Array of selected employee IDs

        $employeeCutoff = explode(",", $addempCutoff);

        $cutOffID = $_POST['cutoffname-add'];
    

    
        $ExistingCutoff = "SELECT * FROM thirteencutoff_tb WHERE `id` = '$cutOffID'";
        $CutoffRun = mysqli_query($conn, $ExistingCutoff);
        $row = mysqli_fetch_assoc($CutoffRun);
        $startDate = $row['start_date'];
        $endDate = $row['end_date'];
    
        $employeesToAdd = [];
        foreach($employeeCutoff as $empID) {
            // Check if the employee is already in the empcutoff_tb
            $CheckExisting = "SELECT * FROM empthirteen_tb WHERE `cut_id` = '$cutOffID' AND `empid` = '$empID'";
            $RunExist = mysqli_query($conn, $CheckExisting);
    
            if(mysqli_num_rows($RunExist) > 0){
                header("Location: ../../13month.php?existed");
                exit();
            }
    
            // Check if the employee has attendance data in the specified range
            $AttendanceExist = "SELECT * FROM attendances WHERE `empid` = '$empID' AND `date` BETWEEN '$startDate' AND '$endDate'";
            $Runattendance = mysqli_query($conn, $AttendanceExist);
    
            if(mysqli_num_rows($Runattendance) > 0){
                $hasPresentStatus = false;
                

                while ($attendanceRow = mysqli_fetch_assoc($Runattendance)) {
                    if ($attendanceRow['status'] === 'Present') {
                        $hasPresentStatus = true;
                        break;
                    }
                }

                if ($hasPresentStatus) {
                    $employeesToAdd[] = $empID; // Store the selected employee ID
                } else {
                    header("Location: ../../13month.php?noattendance");
                    exit();
                }

            }
        }
    
        // Insert employees with attendance data into empcutoff_tb
        if (!empty($employeesToAdd)) {
            $values = [];
            foreach ($employeesToAdd as $empID) {
                $values[] = "('$cutOffID', '$empID')";
            }
            
            $valuesStr = implode(",", $values);
            $query = "INSERT INTO empthirteen_tb (`cut_id`, `empid`) VALUES $valuesStr";
            $query_run = mysqli_query($conn, $query);
    
            if ($query_run) {
                header("Location: ../../13month.php?employee");
                exit();
            } else {
                header("Location: ../../13month.php?noattendance");
                exit();
            }
        } else {
            header("Location: ../../13month.php?noattendance");
            exit();
        }
    }
    
    
?>
