<?php
$employeeQuery = mysqli_query($conn, "SELECT *,
CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name
FROM employee_tb WHERE empid = '$EmployeeID'");

if($employeeQuery->num_rows > 0){
    $row_emp = $employeeQuery->fetch_assoc();
    $Fullname = $row_emp['full_name'];
    $EmpDrate = $row_emp['drate'];
    $EmpSalary = $row_emp['empbsalary'];
    $EmpOTrate = $row_emp['otrate'];
    $EmpStatus = $row_emp['status'];
    $EmpPayRule = $row_emp['payrules'];
    $Classification = $row_emp['classification'];

    $LateTotalDeduction = 0;
    @$MONDAY_TO_DEDUCT_LATE_hours = 0;
    @$MONDAY_TO_DEDUCT_LATE_minutes = 0;
    @$MONDAY_TO_DEDUCT_LATE = 0;
    @$Tue_TO_DEDUCT_LATE =  0;
    @$tue_LATE_hours = 0; 
    @$tue_LATE_minutes = 0;
    @$WED_TO_DEDUCT_LATE =  0;
    @$weds_TO_DEDUCT_LATE_hours = 0;
    @$weds_TO_DEDUCT_LATE_minutes = 0;
    @$Thurs_TO_DEDUCT_LATE =  0;
    @$thurs_TO_DEDUCT_LATE_hours = 0;
    @$thurs_TO_DEDUCT_LATE_minutes = 0;
    @$Fri_TO_DEDUCT_LATE =  0; 
    @$fri_TO_DEDUCT_LATE_hours = 0; 
    @$fri_TO_DEDUCT_LATE_minutes = 0;
    @$SAT_TO_DEDUCT_LATE =  0; 
    @$sat_TO_DEDUCT_LATE_hours = 0;
    @$sat_TO_DEDUCT_LATE_minutes = 0;
    @$sun_TO_DEDUCT_LATE_hours = 0;
    @$sun_TO_DEDUCT_LATE_minutes = 0;
    @$Sun_TO_DEDUCT_LATE = 0;

    $attendanceSQL = "SELECT * FROM attendances WHERE empid = '$EmployeeID' AND `date` BETWEEN '$str_date' AND  '$end_date'";
    $result = mysqli_query($conn, $attendanceSQL);
    
    if ($result->num_rows > 0) {
        $datesArray = array(); // Array to store the dates
    
        while ($rowatt = $result->fetch_assoc()) {
            $_late = $rowatt["late"];
            $Date = $rowatt["date"];
            $datesArray[] = array('late' => $_late, 'date' => $Date);
        }
 
        foreach ($datesArray as $date_att) {
            $day_of_week = date('l', strtotime($date_att['date']));
    
            if ($day_of_week === 'Monday') {
                if ($MOn_total_work === '00:00:00') {
                    $MONDAY_TO_DEDUCT_LATE = 0;
                } else {
                    $mon_emp_dailyRate = $EmpDrate;
                    $mon_emp_OtRate = $EmpOTrate;
                    $Mon_total_work_hours = (int)substr($MOn_total_work, 0, 2);
                    @$mon_hour_rate = $mon_emp_dailyRate / $Mon_total_work_hours;
                    $MON_minute_rate = $mon_hour_rate / 60;

                    if($date_att['late'] !== null){
    
                        $mon_timeString = $date_att['late'];
                        $mon_time = DateTime::createFromFormat('H:i:s', $mon_timeString);
        
                        $mon_lateH = $mon_time->format('H');
                        $mon_lateM = $mon_time->format('i');
                        $mon_totalMinutes = intval($mon_lateM);
                        $mon_totalhours = intval($mon_lateH);
        
                        @$MONDAY_TO_DEDUCT_LATE_hours += $mon_totalhours * $mon_hour_rate;
                        @$MONDAY_TO_DEDUCT_LATE_minutes += $mon_totalMinutes * $MON_minute_rate;
                        @$MONDAY_TO_DEDUCT_LATE = @$MONDAY_TO_DEDUCT_LATE_hours + @$MONDAY_TO_DEDUCT_LATE_minutes;
                    } else {
                        $MONDAY_TO_DEDUCT_LATE = 0;
                    }
                }
            }else if($day_of_week === 'Tuesday'){
                if($Tue_total_work === '00:00:00'){
                    $Tue_TO_DEDUCT_LATE = 0;
                }else{
                        $tue_emp_dailyRate =  $EmpDrate;
                        $tue_emp_OtRate = $EmpOTrate;
                        $tue_total_work_hours = (int)substr($Tue_total_work, 0, 2);
                        @$tue_hour_rate =  $tue_emp_dailyRate / $tue_total_work_hours;
                        $tue_minute_rate = $tue_hour_rate / 60; 

                    if($date_att['late'] !== null){
                            $tue_timeString = $date_att['late'];

                            $tue_time = DateTime::createFromFormat('H:i:s', $tue_timeString);// Convert time string to DateTime object

                            //For latee
                            $tue_lateH = $tue_time->format('H');// Extract minutes from DateTime object
                            $tue_lateM = $tue_time->format('i');// Extract minutes from DateTime object
                            $tue_totalMinutes = intval($tue_lateM);
                            $tue_totalhours = intval($tue_lateH);

                            @$tue_LATE_hours += $tue_totalhours * $tue_hour_rate;//minutes to deduct
                            @$tue_LATE_minutes += $tue_totalMinutes * $tue_minute_rate;//minutes to deduct
                            @$Tue_TO_DEDUCT_LATE =  @$tue_LATE_hours +  @$tue_LATE_minutes;
                    } else {
                        $Tue_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Tuesday

            else if($day_of_week === 'Wednesday'){
                if($wed_total_work === '00:00:00'){
                    $WED_TO_DEDUCT_LATE = 0;
                }else{
                    $weds_emp_dailyRate = $EmpDrate;
                    $weds_emp_OtRate = $EmpOTrate;
                    $weds_total_work_hours = (int)substr($wed_total_work, 0, 2);
                    @$weds_hour_rate =  $weds_emp_dailyRate / $weds_total_work_hours;
                    $weds_minute_rate = $weds_hour_rate / 60;
                    
                    if($date_att['late'] !== null){
                        $weds_timeString = $date_att['late'];

                        $weds_time = DateTime::createFromFormat('H:i:s', $weds_timeString);// Convert time string to DateTime object

                        //For latee
                        $weds_lateH = $weds_time->format('H');
                        $weds_lateM = $weds_time->format('i');
                        $weds_totalMinutes = intval($weds_lateM);
                        $weds_totalhours = intval($weds_lateH);

                        @$weds_TO_DEDUCT_LATE_hours += $weds_totalhours * $weds_hour_rate;//minutes to deduct
                        @$weds_TO_DEDUCT_LATE_minutes += $weds_totalMinutes * $weds_minute_rate;//minutes to deduct
                        @$WED_TO_DEDUCT_LATE =  @$weds_TO_DEDUCT_LATE_hours +  @$weds_TO_DEDUCT_LATE_minutes;
                    } else {
                        $WED_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Wednesday

            else if($day_of_week === 'Thursday'){
                if($thurs_total_work === '00:00:00'){
                    $Thurs_TO_DEDUCT_LATE = 0;
                }else{
                    $thurs_emp_dailyRate = $EmpDrate;
                    $thurs_emp_OtRate = $EmpOTrate;
                    $thurs_total_work_hours = (int)substr($thurs_total_work, 0, 2);
                    @$thurs_hour_rate =  $thurs_emp_dailyRate / $thurs_total_work_hours;
                    $thurs_minute_rate = $thurs_hour_rate / 60; 
                    
                    if($date_att['late'] !== null){
                        $thurs_timeString = $date_att['late'];

                        $thurs_time = DateTime::createFromFormat('H:i:s', $thurs_timeString);

                        //For latee
                        $thurs_lateH = $thurs_time->format('H');
                        $thurs_lateM = $thurs_time->format('i');
                        $thurs_totalMinutes = intval($thurs_lateM);
                        $thurs_totalhours = intval($thurs_lateH);

                        @$thurs_TO_DEDUCT_LATE_hours += $thurs_totalhours * $thurs_hour_rate;//minutes to deduct
                        @$thurs_TO_DEDUCT_LATE_minutes += $thurs_totalMinutes * $thurs_minute_rate;//minutes to deduct
                        @$Thurs_TO_DEDUCT_LATE =  @$thurs_TO_DEDUCT_LATE_hours +  @$thurs_TO_DEDUCT_LATE_minutes;
                    } else {
                        $Thurs_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Thursday

            else if ($day_of_week === 'Friday') {
                if ($fri_total_work === '00:00:00') {
                    $Fri_TO_DEDUCT_LATE = 0;
                } else {
                    $fri_emp_dailyRate = $EmpDrate;
                    $fri_emp_OtRate = $EmpOTrate;
                    $fri_total_work_hours = (int)substr($fri_total_work, 0, 2);
                    @$fri_hour_rate =  $fri_emp_dailyRate / $fri_total_work_hours;
                    $fri_minute_rate = $fri_hour_rate / 60;
            
                    if ($date_att['late'] !== null) {
                        $fri_timeString = $date_att['late'];
            
                        $fri_time = DateTime::createFromFormat('H:i:s', $fri_timeString); // Convert time string
            
                        // For latee
                        $fri_lateH = $fri_time->format('H');
                        $fri_lateM = $fri_time->format('i');
                        $fri_totalMinutes = intval($fri_lateM);
                        $fri_totalhours = intval($fri_lateH);
            
                        @$fri_TO_DEDUCT_LATE_hours += $fri_totalhours * $fri_hour_rate;
                        @$fri_TO_DEDUCT_LATE_minutes += $fri_totalMinutes * $fri_minute_rate;
                        @$Fri_TO_DEDUCT_LATE = @$fri_TO_DEDUCT_LATE_hours + @$fri_TO_DEDUCT_LATE_minutes;
                    } else {
                        $Fri_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Friday
            
            
            else if($day_of_week === 'Saturday'){
                if($sat_total_work === '00:00:00'){
                    $SAT_TO_DEDUCT_LATE = 0;
                }else{
                    $sat_emp_dailyRate = $EmpDrate;
                    $sat_emp_OtRate = $EmpOTrate;
                    $sat_total_work_hours = (int)substr($sat_total_work, 0, 2);
                    @$sat_hour_rate =  $sat_emp_dailyRate / $sat_total_work_hours;
                    $sat_minute_rate = $sat_hour_rate / 60; 

                    if ($date_att['late'] !== null) {
                        $sat_timeString = $date_att['late'];

                        $sat_time = DateTime::createFromFormat('H:i:s', $sat_timeString);// Convert time string to DateTime object
                        //For latee
                        $sat_lateH = $sat_time->format('H');
                        $sat_lateM = $sat_time->format('i');
                        $sat_totalMinutes = intval($sat_lateM);
                        $sat_totalhours = intval($sat_lateH);

                        @$sat_TO_DEDUCT_LATE_hours += $sat_totalhours * $sat_hour_rate;
                        @$sat_TO_DEDUCT_LATE_minutes += $sat_totalMinutes * $sat_minute_rate;
                        @$SAT_TO_DEDUCT_LATE =  @$sat_TO_DEDUCT_LATE_hours +  @$sat_TO_DEDUCT_LATE_minutes;
                    } else {
                        $SAT_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Saturday

            else if($day_of_week === 'Sunday'){
                if($sun_total_work === '00:00:00'){
                    $Sun_TO_DEDUCT_LATE = 0;
                }else{                                                  
                    $sun_emp_dailyRate = $EmpDrate;
                    $sun_emp_OtRate = $EmpOTrate;
                    $sun_total_work_hours = (int)substr($sun_total_work, 0, 2);
                    @$sun_hour_rate =  $sun_emp_dailyRate / $sun_total_work_hours;
                    $sun_minute_rate = $sun_hour_rate / 60; 

                    if ($date_att['late'] !== null) {
                        $sun_timeString = $date_att['late'];

                        $sun_time = DateTime::createFromFormat('H:i:s', $sun_timeString);// Convert time string to DateTime object

                        //For latee
                        $sun_lateH = $sun_time->format('H');
                        $sun_lateM = $sun_time->format('i');
                        $sun_totalMinutes = intval($sun_lateM);
                        $sun_totalhours = intval($sun_lateH);
                        
                        @$sun_TO_DEDUCT_LATE_hours += $sun_totalhours * $sun_hour_rate;
                        @$sun_TO_DEDUCT_LATE_minutes += $sun_totalMinutes * $sun_minute_rate;
                        @$Sun_TO_DEDUCT_LATE =  @$sun_TO_DEDUCT_LATE_hours +  @$sun_TO_DEDUCT_LATE_minutes;
                    } else {
                        $Sun_TO_DEDUCT_LATE = 0;
                    }
                }
            }//Sunday
        }
        $LateTotalDeduction = $MONDAY_TO_DEDUCT_LATE + $Tue_TO_DEDUCT_LATE + $WED_TO_DEDUCT_LATE + $Thurs_TO_DEDUCT_LATE + $Fri_TO_DEDUCT_LATE + $SAT_TO_DEDUCT_LATE + $Sun_TO_DEDUCT_LATE;
    }else{
        $LateTotalDeduction = 0; 
    }
    
} else {
    echo "No found.";
}
?>