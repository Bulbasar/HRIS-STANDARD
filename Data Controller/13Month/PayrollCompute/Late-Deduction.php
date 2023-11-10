<?php
$employeeQueries = mysqli_query($conn, "SELECT *,
CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name
FROM employee_tb WHERE empid = '$EmployeeID'");

if($employeeQueries->num_rows > 0){
    $rowEmp = $employeeQueries->fetch_assoc();
    $Fullnames = $rowEmp['full_name'];
    $DailyRates = $rowEmp['drate'];
    $BasicSalaries = $rowEmp['empbsalary'];
    $EmpOTrates = $rowEmp['otrate'];
    $EmpStatuses = $rowEmp['status'];
    $EmpPayRules = $rowEmp['payrules'];
    $Classifications = $rowEmp['classification'];

    @$TotalDeductionLate = 0;
    @$MONDAY_LATE_DEDUCTION = 0;
    @$TuesLateDeduction = 0;
    @$wedLateDeduction = 0;
    @$thursLateDeduction = 0;
    @$FriLateDeduction = 0;
    @$satLateDeduction = 0;
    @$sunLateDeduction = 0;

    $MONDAY_DEDUCT_LATE_hours = 0;
    $MONDAY_DEDUCT_LATE_minutes = 0;
    $tuesLateHour = 0;
    $tuesLateMinute = 0;
    $wedsLateHour = 0;
    $wedsLateMinute = 0;
    $thursLateHour = 0;
    $thursLateMinute = 0;
    $friLateHour = 0;
    $friLateMinute = 0;
    $satLateHour = 0;
    $satLateMinute = 0;
    $sunLateHour = 0;
    $sunLateMinute = 0;

    $attendanceQuery = "SELECT * FROM attendances WHERE empid = '$EmployeeID' AND `date` BETWEEN '$str_date' AND  '$end_date'";
    $result = mysqli_query($conn, $attendanceQuery);
    
    if ($result->num_rows > 0) {
        $datesArrays = array(); // Array to store the dates
    
        while ($rowAttendance = $result->fetch_assoc()) {
            $Lates = $rowAttendance["late"];
            $Dates = $rowAttendance["date"];
            $datesArrays[] = array('late' => $Lates, 'date' => $Dates);
            
        }
    
        foreach ($datesArrays as $dateAttendance) {
            $day_of_week = date('l', strtotime($dateAttendance['date']));

            if ($day_of_week === 'Monday') {
                if ($MOn_total_works === '00:00:00') {
                    $MONDAY_LATE_DEDUCTION = 0;
                } else {
                    $monEmpdailyRate = $DailyRates;
                    $monEmpOtRate = $EmpOTrates;
                    $Mon_total_work_hour = (int)substr($MOn_total_works, 0, 2);
                    @$mon_hourRate = $monEmpdailyRate / $Mon_total_work_hour;
                    $MON_minuteRate = $mon_hourRate / 60;

                    if($dateAttendance['late'] !== null){
    
                        $monTimeString = $dateAttendance['late'];
                        $monTime = DateTime::createFromFormat('H:i:s', $monTimeString);
        
                        $monLateH = $monTime->format('H');
                        $monLateM = $monTime->format('i');
                        $monTotalMinutes = intval($monLateM);
                        $monTotalhours = intval($monLateH);
        
                        @$MONDAY_DEDUCT_LATE_hours += $monTotalhours * $mon_hourRate;
                        @$MONDAY_DEDUCT_LATE_minutes += $monTotalMinutes * $MON_minuteRate;
                        @$MONDAY_LATE_DEDUCTION = @$MONDAY_DEDUCT_LATE_hours + @$MONDAY_DEDUCT_LATE_minutes;
                    } else {
                        $MONDAY_LATE_DEDUCTION = 0;
                    }
                }
            } else if($day_of_week === 'Tuesday'){
                if($Tue_total_works === '00:00:00'){
                    $TuesLateDeduction = 0;
                }else{
                    $tueEmp_dailyRate =  $DailyRates;
                    $tueEmp_OtRate = $EmpOTrates;
                    $tue_total_work_hour = (int)substr($Tue_total_works, 0, 2);
                    @$tue_hourRate =  $tueEmp_dailyRate / $tue_total_work_hour;
                    $tue_minuteRate = $tue_hourRate / 60; 

                if($dateAttendance['late'] !== null){
                        $tueTimeString = $dateAttendance['late'];

                        $tueTime = DateTime::createFromFormat('H:i:s', $tueTimeString);

                        //For latee
                        $tueLateH = $tueTime->format('H');
                        $tueLateM = $tueTime->format('i');
                        $tueTotalMinutes = intval($tueLateM);
                        $tueTotalhours = intval($tueLateH);

                        @$tuesLateHour += $tueTotalhours * $tue_hourRate;
                        @$tuesLateMinute += $tueTotalMinutes * $tue_minuteRate;
                        @$TuesLateDeduction =  @$tuesLateHour +  @$tuesLateMinute;
                } else {
                    $TuesLateDeduction = 0;
                }
            }
         } else if($day_of_week === 'Wednesday'){
                if($wed_total_works === '00:00:00'){
                    $wedLateDeduction = 0;
                }else{
                    $wedsEmp_dailyRate = $DailyRates;
                    $wedsEmp_OtRate = $EmpOTrates;
                    $weds_total_work_hours = (int)substr($wed_total_works, 0, 2);
                    @$weds_hourRate =  $wedsEmp_dailyRate / $weds_total_work_hours;
                    $weds_minuteRate = $weds_hourRate / 60;
                    
                    if($dateAttendance['late'] !== null){
                        $wedsTimeString = $dateAttendance['late'];

                        $wedsTime = DateTime::createFromFormat('H:i:s', $wedsTimeString);

                        //For latee
                        $wedsLateH = $wedsTime->format('H');
                        $wedsLateM = $wedsTime->format('i');
                        $wedsTotalMinutes = intval($wedsLateM);
                        $wedsTotalhours = intval($wedsLateH);

                        @$wedsLateHour += $wedsTotalhours * $weds_hourRate;
                        @$wedsLateMinute += $wedsTotalMinutes * $weds_minuteRate;
                        @$wedLateDeduction =  @$wedsLateHour +  @$wedsLateMinute;
                    } else {
                        $wedLateDeduction = 0;
                    }
                }
            } else if($day_of_week === 'Thursday'){
                if($thurs_total_works === '00:00:00'){
                    $thursLateDeduction = 0;
                }else{
                    $thursEmp_dailyRate = $DailyRates;
                    $thursEmp_OtRate = $EmpOTrates;
                    $thurs_total_work_hours = (int)substr($thurs_total_works, 0, 2);
                    @$thurs_hourRate =  $thursEmp_dailyRate / $thurs_total_work_hours;
                    $thurs_minuteRate = $thurs_hourRate / 60; 
                    
                    if($dateAttendance['late'] !== null){
                        $thurs_timeString = $dateAttendance['late'];

                        $thursTime = DateTime::createFromFormat('H:i:s', $thurs_timeString);

                        //For latee
                        $thursLateH = $thursTime->format('H');
                        $thursLateM = $thursTime->format('i');
                        $thursTotalMinutes = intval($thursLateM);
                        $thursTotalhours = intval($thursLateH);

                        @$thursLateHour += $thursTotalhours * $thurs_hourRate;//hours to deduct
                        @$thursLateMinute += $thursTotalMinutes * $thurs_minuteRate;//minutes to deduct
                        @$thursLateDeduction =  @$thursLateHour +  @$thursLateMinute;
                    } else {
                        $thursLateDeduction = 0;
                    }
                }
            } else if($day_of_week === 'Friday') {
                if ($fri_total_works === '00:00:00') {
                    $FriLateDeduction = 0;
                } else {
                    $friEmp_dailyRate = $DailyRates;
                    $friEmp_OtRate = $EmpOTrates;
                    $fri_total_work_hour = (int)substr($fri_total_works, 0, 2);
                    @$fri_hourRate =  $friEmp_dailyRate / $fri_total_work_hour;
                    $fri_minuteRate = $fri_hourRate / 60;
            
                    if ($dateAttendance['late'] !== null) {
                        $friTimeString = $dateAttendance['late'];
            
                        $friTime = DateTime::createFromFormat('H:i:s', $friTimeString); // Convert time string
            
                        // For latee
                        $friLateH = $friTime->format('H');
                        $friLateM = $friTime->format('i');
                        $friTotalMinutes = intval($friLateM);
                        $friTotalhours = intval($friLateH);
            
                        @$friLateHour += $friTotalhours * $fri_hourRate;
                        @$friLateMinute += $friTotalMinutes * $fri_minuteRate;
                        @$FriLateDeduction = @$friLateHour + @$friLateMinute;
                    } else {
                        $FriLateDeduction = 0;
                    }
                }
            } else if($day_of_week === 'Saturday'){
                if($sat_total_works === '00:00:00'){
                    $satLateDeduction = 0;
                }else{
                    $satEmp_dailyRate = $DailyRates;
                    $satEmp_OtRate = $EmpOTrates;
                    $sat_total_work_hour = (int)substr($sat_total_works, 0, 2);
                    @$sat_hourRate =  $satEmp_dailyRate / $sat_total_work_hour;
                    $sat_minuteRate = $sat_hourRate / 60; 

                    if ($dateAttendance['late'] !== null) {
                        $satTimeString = $dateAttendance['late'];

                        $satTime = DateTime::createFromFormat('H:i:s', $satTimeString);// Convert time string to DateTime object
                        //For latee
                        $satLateH = $satTime->format('H');
                        $satLateM = $satTime->format('i');
                        $satTotalMinutes = intval($satLateM);
                        $satTotalhours = intval($satLateH);

                        @$satLateHour += $satTotalhours * $sat_hourRate;
                        @$satLateMinute += $satTotalMinutes * $sat_minuteRate;
                        @$satLateDeduction =  @$satLateHour +  @$satLateMinute;
                    } else {
                        $satLateDeduction = 0;
                    }
                }
            } else if($day_of_week === 'Sunday'){
                if($sun_total_works === '00:00:00'){
                    $sunLateDeduction = 0;
                }else{                                                  
                    $sunEmp_dailyRate = $DailyRates;
                    $sunEmp_OtRate = $EmpOTrates;
                    $sun_total_work_hour = (int)substr($sun_total_works, 0, 2);
                    @$sun_hourRate =  $sunEmp_dailyRate / $sun_total_work_hour;
                    $sun_minuteRate = $sun_hourRate / 60; 

                    if ($dateAttendance['late'] !== null) {
                        $sunTimeString = $dateAttendance['late'];

                        $sunTime = DateTime::createFromFormat('H:i:s', $sunTimeString);// Convert time string to DateTime object

                        //For latee
                        $sunLateH = $sunTime->format('H');
                        $sunLateM = $sunTime->format('i');
                        $sunTotalMinutes = intval($sunLateM);
                        $sunTotalhours = intval($sunLateH);
                        
                        @$sunLateHour += $sunTotalhours * $sun_hourRate;
                        @$sunLateMinute += $sunTotalMinutes * $sun_minuteRate;
                        @$sunLateDeduction =  @$sunLateHour +  @$sunLateMinute;
                    } else {
                        $sunLateDeduction = 0;
                    }
                }
            }
            $TotalDeductionLate = $MONDAY_LATE_DEDUCTION + $TuesLateDeduction + $wedLateDeduction + $thursLateDeduction + $FriLateDeduction + $satLateDeduction + $sunLateDeduction;
        }
        
    } else {
        $TotalDeductionLate = 0;
    }
} else {
    echo "No data found";
}    

?>