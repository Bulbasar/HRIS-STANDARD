<?php
        @$UTtotaldeduction = 0;
        @$monUTDeduction = 0;
        @$mondayUTHours = 0;
        @$mondayUTMinute = 0;
        @$tuesUTDeduction = 0; 
        @$tuesUTHours = 0;
        @$tuesUTMinute = 0;
        @$wedUTDeduction = 0; 
        @$wedUTHours = 0;
        @$wedUTMinute = 0;
        @$thursUTDeduction = 0;
        @$thursUTHours = 0;
        @$thursUTMinute = 0;
        @$friUTDeduction = 0;
        @$friUTHours = 0;
        @$friUTMinute = 0;
        @$satUTDeduction = 0;
        @$satUTHours = 0;
        @$satUTMinutes = 0;
        @$sunUTDeduction = 0;
        @$sunUTHours = 0;
        @$sunUTMinutes = 0;
        
        $sql_UT = "SELECT * FROM `undertime_tb` WHERE `empid` = '$EmployeeID' AND `status` = 'Approved' AND `date` BETWEEN '$str_date' AND '$end_date'";
        $result = mysqli_query($conn, $sql_UT);
        
        if (mysqli_num_rows($result) > 0) {
            $UTarrays = array(); // Array to store the OT
            while ($row_UTs = $result->fetch_assoc()) {
                $DateUndertime = $row_UTs['date'];
                $timeUndertime = $row_UTs['total_undertime'];
                
                $UTarrays[] = array('UT_hours' => $timeUndertime, 'UT_day' => $DateUndertime);
            }  


            foreach($UTarrays as $UTdays){

                $day_of_week_UT = date('l', strtotime($UTdays['UT_day']));
                if($day_of_week_UT === 'Monday'){
                    $Mon_total_work_hour = (int)substr($MOn_total_works, 0, 2);
                    $mon_hourRate =  $EmpDrate / $Mon_total_work_hour;
                    $mon_minuteRate = $mon_hourRate / 60; 

                    $mondayString = $UTdays['UT_hours'];

                    $monTime_UT = DateTime::createFromFormat('H:i:s', $mondayString);
            
                    $monUT_Hour = $monTime_UT->format('H');
                    $monUT_Minutes = $monTime_UT->format('i');
                    $montotalMinutes = intval($monUT_Minutes);
                    $montotalhours = intval($monUT_Hour);

                    @$mondayUTHours += $montotalhours * $mon_hourRate;
                    @$mondayUTMinute += $montotalMinutes * $mon_minuteRate;
                    @$monUTDeduction = @$mondayUTHours + @$mondayUTMinute;

                } else if($day_of_week_UT === 'Tuesday'){
                    $tue_total_work_hour = (int)substr($Tue_total_works, 0, 2);
                    $tue_hourRate =  $EmpDrate / $tue_total_work_hour;
                    $tue_minuteRate = $tue_hourRate / 60; 

                    $tuesdayString = $UTday['UT_hours'];

                    $tuesTime_UT = DateTime::createFromFormat('H:i:s', $tuesdayString);

                    $tueUT_Hour = $tuesTime_UT->format('H');
                    $tuesUT_Minutes = $tuesTime_UT->format('i');
                    $tuestotalMinutes = intval($tuesUT_Minutes);
                    $tuestotalHours = intval($tueUT_Hour);

                    @$tuesUTHours += $tuestotalHours * $tue_hourRate;
                    @$tuesUTMinute += $tuestotalMinutes * $tue_minuteRate;
                    @$tuesUTDeduction = @$tuesUTHours + @$tuesUTMinute;

                } else if($day_of_week_UT === 'Wednesday'){
                    $weds_total_work_hour = (int)substr($wed_total_works, 0, 2);
                    $weds_hourRate =  $EmpDrate / $weds_total_work_hour;
                    $weds_minuteRate = $weds_hourRate / 60; 

                    $wednesdayString = $UTday['UT_hours'];

                    $wedTime_UT = DateTime::createFromFormat('H:i:s', $wednesdayString);

                    $wedUT_Hour = $wedTime_UT->format('H');
                    $wedUT_Minutes = $wedTime_UT->format('i');
                    $wedtotalMinutes = intval($wedUT_Minutes);
                    $wedtotalHours = intval($wedUT_Hour);
                    
                    @$wedUTHours += $wedtotalHours * $weds_hourRate;
                    @$wedUTMinute += $wedtotalMinutes * $weds_minuteRate;                                                                        
                    @$wedUTDeduction = @$wedUTHours + @$wedUTMinute;
                    
                } else if($day_of_week_UT === 'Thursday'){
                    $thurs_total_work_hour = (int)substr($thurs_total_works, 0, 2);
                    $thurs_hourRate =  $EmpDrate / $thurs_total_work_hour; 
                    $thurs_minute_rate = $thurs_hourRate / 60; 

                    $thursdayString = $UTday['UT_hours'];

                    $thursTime_UT = DateTime::createFromFormat('H:i:s', $thursdayString);

                    $thursUT_hour = $thursTime_UT->format('H');
                    $thursUT_minutes = $thursTime_UT->format('i');
                    $thurstotalMinutes = intval($thursUT_minutes);
                    $thurstotalHours = intval($thursUT_hour);

                    @$thursUTHours += $thurstotalHours * $thurs_hourRate;
                    @$thursUTMinute += $thurstotalMinutes * $thurs_minute_rate; 
                    @$thursUTDeduction = @$thursUTHours + @$thursUTMinute;

                } else if($day_of_week_UT === 'Friday'){
                    $fri_total_work_hour = (int)substr($fri_total_works, 0, 2);
                    $fri_hourRate =  $EmpDrate / $fri_total_work_hour;
                    $fri_minuteRate = $fri_hourRate / 60; 

                    $fridayString = $UTday['UT_hours'];

                    $FridayTime_UT = DateTime::createFromFormat('H:i:s', $fridayString);

                    $friUT_hour = $FridayTime_UT->format('H');
                    $friUT_minutes = $FridayTime_UT->format('i');
                    $fritotalMinutes = intval($friUT_minutes);
                    $fritotalHours = intval($friUT_hour);

                    @$friUTHours += $fritotalHours * $fri_hourRate;
                    @$friUTMinute += $fritotalMinutes * $fri_minuteRate; 
                    @$friUTDeduction = @$friUTHours + @$friUTMinute;

                } else if($day_of_week_UT === 'Saturday'){
                    $sat_total_work_hour = (int)substr($sat_total_works, 0, 2);
                    $sat_hourRate =  $EmpDrate / $sat_total_work_hour;
                    $sat_minuteRate = $sat_hourRate / 60; 

                    $saturdayString = $UTday['UT_hours'];

                    $SaturdayTime_UT = DateTime::createFromFormat('H:i:s', $saturdayString);

                    $satUT_hour = $SaturdayTime_UT->format('H');
                    $satUT_minutes = $SaturdayTime_UT->format('i');
                    $sattotalMinutes = intval($satUT_minutes);
                    $sattotalHours = intval($satUT_hour);

                    @$satUTHours += $sattotalHours * $sat_hourRate;
                    @$satUTMinutes += $sattotalMinutes * $sat_minuteRate;
                    @$satUTDeduction = @$satUTHours + @$satUTMinutes;
                } else if($day_of_week_UT === 'Sunday'){
                    $sun_total_work_hour = (int)substr($sun_total_works, 0, 2);
                    $sun_hourRate =  $EmpDrate / $sun_total_work_hour;
                    $sun_minuteRate = $sun_hourRate / 60; 

                    $sundayString = $UTday['UT_hours'];

                    $SundayTime_UT = DateTime::createFromFormat('H:i:s', $sundayString);

                    $UTsunday_hour = $SundayTime_UT->format('H');
                    $UTsunday_min = $SundayTime_UT->format('i');
                    $suntotalMinutes = intval($UTsunday_min);
                    $suntotalHours = intval($UTsunday_hour);

                    @$sunUTHours += $suntotalMinutes * $sun_hourRate;
                    @$sunUTMinutes += $suntotalHours * $sun_minuteRate;
                    @$sunUTDeduction = @$sunUTHours + @$sunUTMinutes;
                }
            }
            // echo $EmployeeID . ' - ' . $mon_TO_DEDUCT_UT .'<br>';
            @$UTtotaldeduction = $monUTDeduction + $tuesUTDeduction + $wedUTDeduction + $thursUTDeduction + $friUTDeduction + $satUTDeduction + $sunUTDeduction;
        } 
    
        // else {
        //     $attendanceUT = "SELECT * FROM attendances WHERE empid = '$EmployeeID' AND `date` BETWEEN '$str_date' AND '$end_date' AND early_out != '00:00:00'";
        //     $attendanceUTresult = mysqli_query($conn, $attendanceUT);

        //     if($attendanceUTresult->num_rows > 0){
        //         $EarlyOut = array();

        //         while($row_attendance = $attendanceUTresult->fetch_assoc()){
        //             $attendanceDateUT = $row_attendance['date'];
        //             $early = $row_attendance['early_out'];
        //             $EarlyOut[] = array('dateEarly' => $attendanceDateUT, 'early_out' => $early);
        //         }

        //         foreach($EarlyOut as $dateattUT){
        //             $day_of_week_UT = date('l', strtotime($dateattUT['dateEarly']));//convert the each date to day
        //             if($day_of_week_UT === 'Monday'){
        //                 $Mon_total_work_hour = (int)substr($MOn_total_works, 0, 2);
        //                 $mon_hourRate =  $EmpDrate / $Mon_total_work_hour;
        //                 $mon_minuteRate = $mon_hourRate / 60; 
    
        //                 $mondayString = $UTdays['UT_hours'];
    
        //                 $monTime_UT = DateTime::createFromFormat('H:i:s', $mondayString);
                
        //                 $monUT_Hour = $monTime_UT->format('H');
        //                 $monUT_Minutes = $monTime_UT->format('i');
        //                 $montotalMinutes = intval($monUT_Minutes);
        //                 $montotalhours = intval($monUT_Hour);
    
        //                 @$mondayUTHours += $montotalhours * $mon_hourRate;
        //                 @$mondayUTMinute += $montotalMinutes * $mon_minuteRate;
        //                 @$monUTDeduction = @$mondayUTHours + @$mondayUTMinute;
    
        //             } else if($day_of_week_UT === 'Tuesday'){
        //                 $tue_total_work_hour = (int)substr($Tue_total_works, 0, 2);
        //                 $tue_hourRate =  $EmpDrate / $tue_total_work_hour;
        //                 $tue_minuteRate = $tue_hourRate / 60; 
    
        //                 $tuesdayString = $UTday['UT_hours'];
    
        //                 $tuesTime_UT = DateTime::createFromFormat('H:i:s', $tuesdayString);
    
        //                 $tueUT_Hour = $tuesTime_UT->format('H');
        //                 $tuesUT_Minutes = $tuesTime_UT->format('i');
        //                 $tuestotalMinutes = intval($tuesUT_Minutes);
        //                 $tuestotalHours = intval($tueUT_Hour);
    
        //                 @$tuesUTHours += $tuestotalHours * $tue_hourRate;
        //                 @$tuesUTMinute += $tuestotalMinutes * $tue_minuteRate;
        //                 @$tuesUTDeduction = @$tuesUTHours + @$tuesUTMinute;
    
        //             } else if($day_of_week_UT === 'Wednesday'){
        //                 $weds_total_work_hour = (int)substr($wed_total_works, 0, 2);
        //                 $weds_hourRate =  $EmpDrate / $weds_total_work_hour;
        //                 $weds_minuteRate = $weds_hourRate / 60; 
    
        //                 $wednesdayString = $UTday['UT_hours'];
    
        //                 $wedTime_UT = DateTime::createFromFormat('H:i:s', $wednesdayString);
    
        //                 $wedUT_Hour = $wedTime_UT->format('H');
        //                 $wedUT_Minutes = $wedTime_UT->format('i');
        //                 $wedtotalMinutes = intval($wedUT_Minutes);
        //                 $wedtotalHours = intval($wedUT_Hour);
                        
        //                 @$wedUTHours += $wedtotalHours * $weds_hourRate;
        //                 @$wedUTMinute += $wedtotalMinutes * $weds_minuteRate;                                                                        
        //                 @$wedUTDeduction = @$wedUTHours + @$wedUTMinute;
                        
        //             } else if($day_of_week_UT === 'Thursday'){
        //                 $thurs_total_work_hour = (int)substr($thurs_total_works, 0, 2);
        //                 $thurs_hourRate =  $EmpDrate / $thurs_total_work_hour; 
        //                 $thurs_minute_rate = $thurs_hourRate / 60; 
    
        //                 $thursdayString = $UTday['UT_hours'];
    
        //                 $thursTime_UT = DateTime::createFromFormat('H:i:s', $thursdayString);
    
        //                 $thursUT_hour = $thursTime_UT->format('H');
        //                 $thursUT_minutes = $thursTime_UT->format('i');
        //                 $thurstotalMinutes = intval($thursUT_minutes);
        //                 $thurstotalHours = intval($thursUT_hour);
    
        //                 @$thursUTHours += $thurstotalHours * $thurs_hourRate;
        //                 @$thursUTMinute += $thurstotalMinutes * $thurs_minute_rate; 
        //                 @$thursUTDeduction = @$thursUTHours + @$thursUTMinute;
    
        //             } else if($day_of_week_UT === 'Friday'){
        //                 $fri_total_work_hour = (int)substr($fri_total_works, 0, 2);
        //                 $fri_hourRate =  $EmpDrate / $fri_total_work_hour;
        //                 $fri_minuteRate = $fri_hourRate / 60; 
    
        //                 $fridayString = $UTday['UT_hours'];
    
        //                 $FridayTime_UT = DateTime::createFromFormat('H:i:s', $fridayString);
    
        //                 $friUT_hour = $FridayTime_UT->format('H');
        //                 $friUT_minutes = $FridayTime_UT->format('i');
        //                 $fritotalMinutes = intval($friUT_minutes);
        //                 $fritotalHours = intval($friUT_hour);
    
        //                 @$friUTHours += $fritotalHours * $fri_hourRate;
        //                 @$friUTMinute += $fritotalMinutes * $fri_minuteRate; 
        //                 @$friUTDeduction = @$friUTHours + @$friUTMinute;
    
        //             } else if($day_of_week_UT === 'Saturday'){
        //                 $sat_total_work_hour = (int)substr($sat_total_works, 0, 2);
        //                 $sat_hourRate =  $EmpDrate / $sat_total_work_hour;
        //                 $sat_minuteRate = $sat_hourRate / 60; 
    
        //                 $saturdayString = $UTday['UT_hours'];
    
        //                 $SaturdayTime_UT = DateTime::createFromFormat('H:i:s', $saturdayString);
    
        //                 $satUT_hour = $SaturdayTime_UT->format('H');
        //                 $satUT_minutes = $SaturdayTime_UT->format('i');
        //                 $sattotalMinutes = intval($satUT_minutes);
        //                 $sattotalHours = intval($satUT_hour);
    
        //                 @$satUTHours += $sattotalHours * $sat_hourRate;
        //                 @$satUTMinutes += $sattotalMinutes * $sat_minuteRate;
        //                 @$satUTDeduction = @$satUTHours + @$satUTMinutes;
        //             } else if($day_of_week_UT === 'Sunday'){
        //                 $sun_total_work_hour = (int)substr($sun_total_works, 0, 2);
        //                 $sun_hourRate =  $EmpDrate / $sun_total_work_hour;
        //                 $sun_minuteRate = $sun_hourRate / 60; 
    
        //                 $sundayString = $UTday['UT_hours'];
    
        //                 $SundayTime_UT = DateTime::createFromFormat('H:i:s', $sundayString);
    
        //                 $UTsunday_hour = $SundayTime_UT->format('H');
        //                 $UTsunday_min = $SundayTime_UT->format('i');
        //                 $suntotalMinutes = intval($UTsunday_min);
        //                 $suntotalHours = intval($UTsunday_hour);
    
        //                 @$sunUTHours += $suntotalMinutes * $sun_hourRate;
        //                 @$sunUTMinutes += $suntotalHours * $sun_minuteRate;
        //                 @$sunUTDeduction = @$sunUTHours + @$sunUTMinutes;
        //             }
        //         }
        //         @$UTtotaldeduction = $monUTDeduction + $tuesUTDeduction + $wedUTDeduction + $thursUTDeduction + $friUTDeduction + $satUTDeduction + $sunUTDeduction;
                
        //     } else{
        //         @$UTtotaldeduction = 0;
        //     }
        // } 



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