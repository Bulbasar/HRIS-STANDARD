<?php
        @$UTtotaldeduction = 0;
        @$mon_TO_DEDUCT_UT = 0;
        @$monday_UT_hours_deduction = 0;
        @$monday_UT_minute_deduction = 0;
        @$tues_TO_DEDUCT_UT = 0; 
        @$tuesday_UT_hours_deduction = 0;
        @$tuesday_UT_minute_deduction = 0;
        @$weds_TO_DEDUCT_UT = 0; 
        @$wednesday_UT_hours_deduction = 0;
        @$wednesday_UT_minute_deduction = 0;
        @$thurs_TO_DEDUCT_UT = 0;
        @$thursday_UT_hours_deduction = 0;
        @$thursday_UT_minute_deduction = 0;
        @$fri_TO_DEDUCT_UT = 0;
        @$friday_UT_hours_deduction = 0;
        @$friday_UT_minute_deduction = 0;
        @$sat_TO_DEDUCT_UT = 0;
        @$saturday_UT_hours_deduction = 0;
        @$saturday_UT_minute_deduction = 0;
        @$sun_TO_DEDUCT_UT = 0;
        @$sunday_UT_hours_deduction = 0;
        @$sunday_UT_minute_deduction = 0;
        $sql_UT = "SELECT * FROM `undertime_tb` WHERE `empid` = '$EmployeeID' AND `status` = 'Approved' AND `date` BETWEEN '$str_date' AND '$end_date'";
        $result = mysqli_query($conn, $sql_UT);
        
        if (mysqli_num_rows($result) > 0) {
            $UTarray = array(); // Array to store the OT
            while ($row_UT = $result->fetch_assoc()) {
                $dateUT = $row_UT['date'];
                $timeUT = $row_UT['total_undertime'];
                
                $UTarray[] = array('UT_hours' => $timeUT, 'UT_day' => $dateUT);
            }  


            foreach($UTarray as $UTday){

                $day_of_week_UT = date('l', strtotime($UTday['UT_day']));
                if($day_of_week_UT === 'Monday'){
                    $Mon_total_work_hour = (int)substr($MOn_total_work, 0, 2);
                    $mon_hour_rate =  $EmpDrate / $Mon_total_work_hour;
                    $mon_minute_rate = $mon_hour_rate / 60; 

                    $mondayUT_string = $UTday['UT_hours'];

                    $mon_time_UT = DateTime::createFromFormat('H:i:s', $mondayUT_string);
            
                    $mon_UT_Hour = $mon_time_UT->format('H');
                    $mon_UT_Minutes = $mon_time_UT->format('i');
                    $mon_totalMinutes = intval($mon_UT_Minutes);
                    $mon_totalhours = intval($mon_UT_Hour);

                    @$monday_UT_hours_deduction += $mon_totalhours * $mon_hour_rate;
                    @$monday_UT_minute_deduction += $mon_totalMinutes * $mon_minute_rate;
                    @$mon_TO_DEDUCT_UT = @$monday_UT_hours_deduction + @$monday_UT_minute_deduction;

                }else if($day_of_week_UT === 'Tuesday'){
                    $tue_total_work_hour = (int)substr($Tue_total_work, 0, 2);
                    $tue_hour_rate =  $EmpDrate / $tue_total_work_hour;
                    $tue_minute_rate = $tue_hour_rate / 60; 

                    $tuesdayUT_string = $UTday['UT_hours'];

                    $tues_time_UT = DateTime::createFromFormat('H:i:s', $tuesdayUT_string);

                    $tues_UT_Hour = $tues_time_UT->format('H');
                    $tues_UT_Minutes = $tues_time_UT->format('i');
                    $UT_tues_totalmin = intval($tues_UT_Minutes);
                    $UT_tues_totalHour = intval($tues_UT_Hour);

                    @$tuesday_UT_hours_deduction += $UT_tues_totalHour * $tue_hour_rate;
                    @$tuesday_UT_minute_deduction += $UT_tues_totalmin * $tue_minute_rate;
                    @$tues_TO_DEDUCT_UT = @$tuesday_UT_hours_deduction + @$tuesday_UT_minute_deduction;

                }else if($day_of_week_UT === 'Wednesday'){
                    $weds_total_work_hour = (int)substr($wed_total_work, 0, 2);
                    $weds_hour_rate =  $EmpDrate / $weds_total_work_hour;
                    $weds_minute_rate = $weds_hour_rate / 60; 

                    $wednesdayUT_string = $UTday['UT_hours'];

                    $wed_time_UT = DateTime::createFromFormat('H:i:s', $wednesdayUT_string);

                    $wed_UT_Hour = $wed_time_UT->format('H');
                    $wed_UT_Minutes = $wed_time_UT->format('i');
                    $UT_wed_totalmin = intval($wed_UT_Minutes);
                    $UT_wed_totalHour = intval($wed_UT_Hour);
                    
                    @$wednesday_UT_hours_deduction += $UT_wed_totalHour * $weds_hour_rate;
                    @$wednesday_UT_minute_deduction += $UT_wed_totalmin * $weds_minute_rate;                                                                        
                    @$weds_TO_DEDUCT_UT = @$wednesday_UT_hours_deduction + @$wednesday_UT_minute_deduction;
                    
                }else if($day_of_week_UT === 'Thursday'){
                    $thurs_total_work_hour = (int)substr($thurs_total_work, 0, 2);
                    $thurs_hour_rate =  $EmpDrate / $thurs_total_work_hour; 
                    $thurs_minute_rate = $thurs_hour_rate / 60; 

                    $thursdayUT_string = $UTday['UT_hours'];

                    $thurs_time_UT = DateTime::createFromFormat('H:i:s', $thursdayUT_string);

                    $thurs_UT_hour = $thurs_time_UT->format('H');
                    $thurs_UT_minutes = $thurs_time_UT->format('i');
                    $UT_thurs_totalmin = intval($thurs_UT_minutes);
                    $UT_thurs_totalHour = intval($thurs_UT_hour);

                    @$thursday_UT_hours_deduction += $UT_thurs_totalHour * $thurs_hour_rate;
                    @$thursday_UT_minute_deduction += $UT_thurs_totalmin * $thurs_minute_rate; 
                    @$thurs_TO_DEDUCT_UT = @$thursday_UT_hours_deduction + @$thursday_UT_minute_deduction;

                } else if($day_of_week_UT === 'Friday'){
                    $fri_total_work_hour = (int)substr($fri_total_work, 0, 2);
                    $fri_hour_rate =  $EmpDrate / $fri_total_work_hour;
                    $fri_minute_rate = $fri_hour_rate / 60; 

                    $fridayUT_string = $UTday['UT_hours'];

                    $Friday_time_UT = DateTime::createFromFormat('H:i:s', $fridayUT_string);

                    $fri_UT_hour = $Friday_time_UT->format('H');
                    $fri_UT_minutes = $Friday_time_UT->format('i');
                    $UT_fri_totalmin = intval($fri_UT_minutes);
                    $UT_fri_totalHour = intval($fri_UT_hour);

                    @$friday_UT_hours_deduction += $UT_fri_totalHour * $fri_hour_rate;
                    @$friday_UT_minute_deduction += $UT_fri_totalmin * $fri_minute_rate; 
                    @$fri_TO_DEDUCT_UT = @$friday_UT_hours_deduction + @$friday_UT_minute_deduction;

                } else if($day_of_week_UT === 'Saturday'){
                    $sat_total_work_hour = (int)substr($sat_total_work, 0, 2);
                    $sat_hour_rate =  $EmpDrate / $sat_total_work_hour;
                    $sat_minute_rate = $sat_hour_rate / 60; 

                    $saturdayUT_string = $UTday['UT_hours'];

                    $Saturday_time_UT = DateTime::createFromFormat('H:i:s', $saturdayUT_string);

                    $sat_UT_hour = $Saturday_time_UT->format('H');
                    $sat_UT_minutes = $Saturday_time_UT->format('i');
                    $UT_sat_totalmin = intval($sat_UT_minutes);
                    $UT_sat_totalHour = intval($sat_UT_hour);

                    @$saturday_UT_hours_deduction += $UT_sat_totalHour * $sat_hour_rate;
                    @$saturday_UT_minute_deduction += $UT_sat_totalmin * $sat_minute_rate;
                    @$sat_TO_DEDUCT_UT = @$saturday_UT_hours_deduction + @$saturday_UT_minute_deduction;
                }else if($day_of_week_UT === 'Sunday'){
                    $sun_total_work_hour = (int)substr($sun_total_work, 0, 2);
                    $sun_hour_rate =  $EmpDrate / $sun_total_work_hour;
                    $sun_minute_rate = $sun_hour_rate / 60; 

                    $sundayUT_string = $UTday['UT_hours'];

                    $Sunday_time_UT = DateTime::createFromFormat('H:i:s', $sundayUT_string);

                    $UT_sunday_hour = $Sunday_time_UT->format('H');
                    $UT_sunday_min = $Sunday_time_UT->format('i');
                    $UT_sun_totalHour = intval($UT_friday_hour);
                    $UT_sun_totalmin = intval($UT_friday_min);

                    @$sunday_UT_hours_deduction += $UT_sunday_hour * $sun_hour_rate;
                    @$sunday_UT_minute_deduction += $UT_sun_totalmin * $sun_minute_rate;
                    @$sun_TO_DEDUCT_UT = @$sunday_UT_hours_deduction + @$sunday_UT_minute_deduction;
                }
            }
            // echo $EmployeeID . ' - ' . $mon_TO_DEDUCT_UT .'<br>';
            @$UTtotaldeduction = @$mon_TO_DEDUCT_UT + @$tues_TO_DEDUCT_UT + @$weds_TO_DEDUCT_UT + @$thurs_TO_DEDUCT_UT + @$fri_TO_DEDUCT_UT + @$sat_TO_DEDUCT_UT + @$sun_TO_DEDUCT_UT;
        } 
    
        else {
            $attendanceUT = "SELECT * FROM attendances WHERE empid = '$EmployeeID' AND `date` BETWEEN '$str_date' AND '$end_date' AND early_out != '00:00:00'";
            $attendanceUTresult = mysqli_query($conn, $attendanceUT);

            if($attendanceUTresult->num_rows > 0){
                $EarlyOut = array();

                while($row_attendance = $attendanceUTresult->fetch_assoc()){
                    $attendanceDateUT = $row_attendance['date'];
                    $early = $row_attendance['early_out'];
                    $EarlyOut[] = array('dateEarly' => $attendanceDateUT, 'early_out' => $early);
                }

                foreach($EarlyOut as $dateattUT){
                    $day_of_week_UT = date('l', strtotime($dateattUT['dateEarly']));//convert the each date to day
                    if($day_of_week_UT === 'Monday'){
                        $mon_emp_dailyRate = $EmpDrate;
                        $mon_emp_OtRate = $EmpOTrate;
        
                        $Mon_total_work_hours = (int)substr($MOn_total_work, 0, 2);
                        $mon_hour_rate =  $EmpDrate / $Mon_total_work_hours;
                        $mon_minute_rate = $mon_hour_rate / 60; 

                        $mon_timeString = $dateattUT['early_out'];
                        $time_UT_monday = DateTime::createFromFormat('H:i:s', $mon_timeString);

                        $UT_monday_hour = $time_UT_monday->format('H');
                        $UT_monday_min = $time_UT_monday->format('i');
                        $UT_mon_totalHour = intval($UT_monday_hour);
                        $UT_mon_totalmin = intval($UT_monday_min);

                        @$monday_UT_hours_deduction += $UT_mon_totalHour * $mon_hour_rate;
                        @$monday_UT_minute_deduction += $UT_mon_totalmin * $mon_minute_rate;
                        @$mon_TO_DEDUCT_UT = @$monday_UT_hours_deduction + @$monday_UT_minute_deduction;

                    }else if($day_of_week_UT === 'Tuesday'){
                        $tue_total_work_hour = (int)substr($Tue_total_work, 0, 2);
                        $tue_hour_rate =  $EmpDrate / $tue_total_work_hour;
                        $tue_minute_rate = $tue_hour_rate / 60; 

                        $tuesdayUT_string = $dateattUT['early_out'];

                        $tues_time_UT = DateTime::createFromFormat('H:i:s', $tuesdayUT_string);

                        $tues_UT_Hour = $tues_time_UT->format('H');
                        $tues_UT_Minutes = $tues_time_UT->format('i');
                        $UT_tues_totalmin = intval($tues_UT_Minutes);
                        $UT_tues_totalHour = intval($tues_UT_Hour);

                        @$tuesday_UT_hours_deduction += $UT_tues_totalHour * $tue_hour_rate;
                        @$tuesday_UT_minute_deduction += $UT_tues_totalmin * $tue_minute_rate;
                        @$tues_TO_DEDUCT_UT = @$tuesday_UT_hours_deduction + @$tuesday_UT_minute_deduction;

                    }else if($day_of_week_UT === 'Wednesday'){
                        $weds_total_work_hour = (int)substr($wed_total_work, 0, 2);
                        $weds_hour_rate =  $EmpDrate / $weds_total_work_hour;
                        $weds_minute_rate = $weds_hour_rate / 60; 

                        $wednesdayUT_string = $dateattUT['early_out'];

                        $wed_time_UT = DateTime::createFromFormat('H:i:s', $wednesdayUT_string);

                        $wed_UT_Hour = $wed_time_UT->format('H');
                        $wed_UT_Minutes = $wed_time_UT->format('i');
                        $UT_wed_totalmin = intval($wed_UT_Minutes);
                        $UT_wed_totalHour = intval($wed_UT_Hour);
                        
                        @$wednesday_UT_hours_deduction += $UT_wed_totalHour * $weds_hour_rate;
                        @$wednesday_UT_minute_deduction += $UT_wed_totalmin * $weds_minute_rate;                                                                        
                        @$weds_TO_DEDUCT_UT = @$wednesday_UT_hours_deduction + @$wednesday_UT_minute_deduction;
                        
                    }else if($day_of_week_UT === 'Thursday'){
                        $thurs_total_work_hour = (int)substr($thurs_total_work, 0, 2);
                        $thurs_hour_rate =  $EmpDrate / $thurs_total_work_hour; 
                        $thurs_minute_rate = $thurs_hour_rate / 60; 

                        $thursdayUT_string = $dateattUT['early_out'];

                        $thurs_time_UT = DateTime::createFromFormat('H:i:s', $thursdayUT_string);

                        $thurs_UT_hour = $thurs_time_UT->format('H');
                        $thurs_UT_minutes = $thurs_time_UT->format('i');
                        $UT_thurs_totalmin = intval($thurs_UT_minutes);
                        $UT_thurs_totalHour = intval($thurs_UT_hour);

                        @$thursday_UT_hours_deduction += $UT_thurs_totalHour * $thurs_hour_rate;
                        @$thursday_UT_minute_deduction += $UT_thurs_totalmin * $thurs_minute_rate; 
                        @$thurs_TO_DEDUCT_UT = @$thursday_UT_hours_deduction + @$thursday_UT_minute_deduction;

                    }else if($day_of_week_UT === 'Friday'){
                        $fri_total_work_hour = (int)substr($fri_total_work, 0, 2);
                        $fri_hour_rate =  $EmpDrate / $fri_total_work_hour;
                        $fri_minute_rate = $fri_hour_rate / 60; 

                        $fridayUT_string = $dateattUT['early_out'];

                        $Friday_time_UT = DateTime::createFromFormat('H:i:s', $fridayUT_string);

                        $fri_UT_hour = $Friday_time_UT->format('H');
                        $fri_UT_minutes = $Friday_time_UT->format('i');
                        $UT_fri_totalmin = intval($fri_UT_minutes);
                        $UT_fri_totalHour = intval($fri_UT_hour);

                        @$friday_UT_hours_deduction += $UT_fri_totalHour * $fri_hour_rate;
                        @$friday_UT_minute_deduction += $UT_fri_totalmin * $fri_minute_rate; 
                        @$fri_TO_DEDUCT_UT = @$friday_UT_hours_deduction + @$friday_UT_minute_deduction;

                    }else if($day_of_week_UT === 'Saturday'){
                        $sat_total_work_hour = (int)substr($sat_total_work, 0, 2);
                        $sat_hour_rate =  $EmpDrate / $sat_total_work_hour;
                        $sat_minute_rate = $sat_hour_rate / 60; 

                        $saturdayUT_string = $dateattUT['early_out'];

                        $Saturday_time_UT = DateTime::createFromFormat('H:i:s', $saturdayUT_string);

                        $sat_UT_hour = $Saturday_time_UT->format('H');
                        $sat_UT_minutes = $Saturday_time_UT->format('i');
                        $UT_sat_totalmin = intval($sat_UT_minutes);
                        $UT_sat_totalHour = intval($sat_UT_hour);

                        @$saturday_UT_hours_deduction += $UT_sat_totalHour * $sat_hour_rate;
                        @$saturday_UT_minute_deduction += $UT_sat_totalmin * $sat_minute_rate;
                        @$sat_TO_DEDUCT_UT = @$saturday_UT_hours_deduction + @$saturday_UT_minute_deduction;
                    }else if($day_of_week_UT === 'Sunday'){
                        $sun_total_work_hour = (int)substr($sun_total_work, 0, 2);
                        $sun_hour_rate =  $EmpDrate / $sun_total_work_hour;
                        $sun_minute_rate = $sun_hour_rate / 60; 

                        $sundayUT_string = $dateattUT['early_out'];

                        $Sunday_time_UT = DateTime::createFromFormat('H:i:s', $sundayUT_string);

                        $UT_sunday_hour = $Sunday_time_UT->format('H');
                        $UT_sunday_min = $Sunday_time_UT->format('i');
                        $UT_sun_totalHour = intval($UT_friday_hour);
                        $UT_sun_totalmin = intval($UT_friday_min);

                        @$sunday_UT_hours_deduction += $UT_sunday_hour * $sun_hour_rate;
                        @$sunday_UT_minute_deduction += $UT_sun_totalmin * $sun_minute_rate;
                        @$sun_TO_DEDUCT_UT = @$sunday_UT_hours_deduction + @$sunday_UT_minute_deduction;
                    }
                }
                @$UTtotaldeduction = @$mon_TO_DEDUCT_UT + @$tues_TO_DEDUCT_UT + @$weds_TO_DEDUCT_UT + @$thurs_TO_DEDUCT_UT + @$fri_TO_DEDUCT_UT + @$sat_TO_DEDUCT_UT + @$sun_TO_DEDUCT_UT;
                
            }else{
                @$UTtotaldeduction = 0;
            }
        } 



        //Undertime minutes and hours
        $ApprovedUT = mysqli_query($conn, " SELECT
        IFNULL(
            CONCAT(
                FLOOR(SUM(TIME_TO_SEC(total_undertime)) / 3600), 'H:',
                FLOOR((SUM(TIME_TO_SEC(total_undertime)) % 3600) / 60), 'M'
            ),
            '0H:0M'
        ) AS TotalhoursUndertime
        FROM 
            `undertime_tb` 
        WHERE 
            `empid` = '$EmployeeID' 
            AND `date` BETWEEN '$str_date' AND '$end_date' 
            AND `status` = 'Approved'");
        
        if ($ApprovedUT->num_rows > 0) {
            $UTrow = $ApprovedUT->fetch_assoc();
            $UndertimeHours = $UTrow['TotalhoursUndertime'];
        } else {
            $AttUT = mysqli_query($conn, "SELECT
                CONCAT(
                    FLOOR(SUM(TIME_TO_SEC(early_out)) / 3600),
                    'H:',
                    FLOOR((SUM(TIME_TO_SEC(early_out)) % 3600) / 60),
                    'M'
                ) AS AttTotalUndertime
                FROM attendances 
                WHERE empid = '$EmployeeID' AND `date` BETWEEN '$str_date' AND '$end_date'");
            
            if ($AttUT->num_rows > 0) {
                $rowUT = $AttUT->fetch_assoc();
                $UndertimeHours = $rowUT['AttTotalUndertime'];
            } else {
                $UndertimeHours = '0H:0M';
            }
        }
        
    

?>