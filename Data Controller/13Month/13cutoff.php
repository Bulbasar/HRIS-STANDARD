<?php
include '../../config.php';

if(isset($_POST['thirteen-submit'])){
        $empId = $_POST['name_empId'][0];
        $empIDs = explode(",", $empId);

        $month = $_POST['monthpicker'];
        $year = $_POST['yearpicker'];
        $datestart = $_POST['startdatepicker'];
        $dateend = $_POST['enddatepicker'];

        $multiEmpid = array();

        foreach($empIDs as $employeeId){
            $att_sql = "SELECT * FROM attendances WHERE empid = '$employeeId' AND `date` BETWEEN '$datestart' AND '$dateend'";
            $attresult = mysqli_query($conn, $att_sql);
        
            if($attresult->num_rows > 0){
                $PresentStatus = false;
        
                while($attrow = $attresult->fetch_assoc()){
                    if($attrow['status'] === 'Present'){
                        $PresentStatus = true;
                        break;
                    }
                }
        
                if($PresentStatus){
                    $multiEmpid[] = $employeeId;
                }
            } else {
                header("Location: ../../13month.php?notfound");
                exit();
            }
        }
        
        if (!empty($multiEmpid)) {
            $cutchecking = "SELECT * FROM thirteencutoff_tb WHERE `month` = '$month' AND `year` = '$year' AND `start_date` = '$datestart' AND `end_date` = '$dateend'";
            $checkresult = mysqli_query($conn, $cutchecking);

            if($checkresult->num_rows === 0){
                $sql_cut = "INSERT INTO thirteencutoff_tb (`month`, `year`, `start_date`, `end_date`)
                VALUES ('$month', '$year', '$datestart', '$dateend')";
                $sqlrun = mysqli_query($conn, $sql_cut);
            
                if ($sqlrun) {
                    $cuthirteen = "SELECT max(id) AS thirteencut FROM thirteencutoff_tb";
                    $cutoffRun = mysqli_query($conn, $cuthirteen);
            
                    if ($cutoffRun->num_rows > 0) {
                        $rowcut = $cutoffRun->fetch_assoc();
                        $cutoff = $rowcut['thirteencut'];
            
                        foreach ($multiEmpid as $empID) {
                            $sql_query = "INSERT INTO empthirteen_tb(`cut_id`,`empid`) VALUES ('$cutoff', '$empID')";
                            $sqlRun = mysqli_query($conn, $sql_query);
                            if (!$sqlRun) {
                                echo "Error: " . $sql_query . "<br>" . mysqli_error($conn);
                            }
                        }
                        header("Location: ../../13month.php?inserted");
                        exit();
                    }
                }
            } else {
                header("Location: ../../13month.php?error");
                exit();
            }
        } else {
            header("Location: ../../13month.php?notfound");
            exit();
        }
    
}

?>