<?php
//JUST BACK UP DONT REMIND 07/03/23
$sql_att_all1 = "SELECT
                *
            FROM 
                `attendances` 
            WHERE 
                (`status` = 'Present' 
            OR 
                `status` = 'On-Leave') 
            AND 
                `empid` = '$EmployeeID'
            AND 
                `date` 
            BETWEEN  
                '$str_date' 
            AND  
                '$end_date'";
            
            $result = $conn->query($sql_att_all1);
                                                                 
                if ($result->num_rows > 0) {

                    $att_array = array(); // Array to store the attendance

                    while ($row_att_all = $result->fetch_assoc()) {
                        $date_att = $row_att_all['date'];
                        $att_time_in = $row_att_all['time_in'];
                        $att_time_out = $row_att_all['time_out'];

                        $att_array[] = array('date_att' => $date_att);
                    }

                    $double_pay_holiday = 0;
                    $totalOT_pay_holiday = 0;
                    $totalOT_pay_holiday_restday = 0;
                    $double_pay_holiday_restday = 0;

                    foreach($att_array as $att_holiday_arrays){
                        $holiday_array = $att_holiday_arrays['date_att'];
                    
                        $result_holiday = mysqli_query($conn, " SELECT
                            *
                        FROM 
                            `holiday_tb` 
                        WHERE date_holiday =  '$holiday_array' AND `holiday_type` = 'Special Non-Working Holiday'");

                        if(mysqli_num_rows($result_holiday) > 0) {
                            $row_holiday = mysqli_fetch_assoc($result_holiday);

                            $valid_holiday = $row_holiday['date_holiday'];//holiday dates


                            $result_company_settings = mysqli_query($conn, " SELECT
                                *
                            FROM 
                                `settings_tb` 
                            ORDER BY `_datetime` DESC
                            LIMIT 1
                            
                            ");

                            $row_company_settings = mysqli_fetch_assoc($result_company_settings);

                            
                            $validation_eligible_holiday = '';

                            
                            $date_before = new DateTime($valid_holiday);
                            $date_before->modify('-1 day');
                            $date_before = $date_before->format('Y-m-d');
                            
                            $date_after = new DateTime($valid_holiday);
                            $date_after->modify('+1 day');
                            $date_after = $date_after->format('Y-m-d');






                            include 'holiday_validation.php'; // para sa validation if day before, day after and day before or after






                            

                            //-----------------------START COMPUTATION FOR HOLIDAY PAY IF  $validation_eligible_holiday = 'YES'--------------------

                           if($validation_eligible_holiday === 'YES'){
                                    //select lahat ng date sa employee na may holiday
                                    $result_valid_holiday = mysqli_query($conn, " SELECT
                                        *
                                    FROM 
                                        `attendances` 
                                    WHERE `empid` =  '$EmployeeID' AND `date` = '$valid_holiday'");

                                    $row_emp_holiday_att = mysqli_fetch_assoc($result_valid_holiday);

                                    $emp_holiday_timeIN =  $row_emp_holiday_att['time_in']; //holiday date attedance timein
                                    $emp_holiday_timeOUT = $row_emp_holiday_att['time_out']; //holiday date attedance timeout


                                    if($emp_holiday_timeIN != '00:00:00' && $emp_holiday_timeOUT != '00:00:00'){ //if pumasok ang employee sa holiday
                                            $daily_rate = $row_emp['drate'];
                                           

                                    //-------------------------Para sa nag WORKED ang employee sa holdiday AT RESTDAY NIYA----------------------

                                    $day_of_validHoliday = date('l', strtotime($valid_holiday)); //convert the each date to day
                                    // echo $date . " = " . $day_of_week ."<br> <br>";

                                    if($day_of_validHoliday === 'Monday')
                                        {

                                            // -----------------------BREAK MONDAY START----------------------------//
                                            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == '')
                                            {
                                                                                              
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------



                                                     

                                            }
                                            else{

                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                       
                                        // -----------------------BREAK MONDAY START----------------------------//

                                        // -----------------------BREAK Tuesday START----------------------------//

                                    else if($day_of_validHoliday === 'Tuesday')
                                        {

                                            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == '')
                                            {
                                              
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else{

                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }

                                           
                                        // -----------------------BREAK Tuesday END----------------------------//

                                        // -----------------------BREAK WEDNESDAY START----------------------------//
                                    else if($day_of_validHoliday === 'Wednesday')
                                        {

                                            if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == '')
                                            {
                                                //if restday  at pumasok siya
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            
                                            }
                                            else
                                            {
                                               //if HINDI restday
                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                               //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                               $result_holiday_OT = mysqli_query($conn, " SELECT
                                                   *
                                               FROM 
                                                   `overtime_tb` 
                                               WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
   
                                               if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                   $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                   
   
                                                   $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                   $OT_hour = $time_OT_con->format('H');
                                                   $OT_totalHour = intval($OT_hour);
   
                                                   $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
   
                                               }
   
                                       //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                            
                                        }
                                              

                                                
                                        // -----------------------BREAK WEDNESDAY END----------------------------//

                                        // -----------------------BREAK THURSDAY START----------------------------//

                                    else if($day_of_validHoliday === 'Thursday')
                                        {

                                            if($row_Sched['thurs_timeout'] === NULL || $row_Sched['thurs_timeout'] === '')
                                            {                                                            
                                                //IF restday at pumasok
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else 
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                 //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                 $result_holiday_OT = mysqli_query($conn, " SELECT
                                                     *
                                                 FROM 
                                                     `overtime_tb` 
                                                 WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
     
                                                 if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                     $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                     
     
                                                     $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                     $OT_hour = $time_OT_con->format('H');
                                                     $OT_totalHour = intval($OT_hour);
     
                                                     $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
     
                                                 }
     
                                         //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                            
                                        // -----------------------BREAK THURSDAY END----------------------------//


                                        // -----------------------BREAK FRIDAY START----------------------------//

                                    else if($day_of_validHoliday === 'Friday')
                                        {

                                            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == '')
                                            {
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                        


                                        // -----------------------BREAK FRIDAY END----------------------------//


                                        // -----------------------BREAK Saturday START----------------------------//

                                    else if($day_of_validHoliday === 'Saturday')
                                        {

                                            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == '')
                                            {
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                        //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                 //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                 $result_holiday_OT = mysqli_query($conn, " SELECT
                                                     *
                                                 FROM 
                                                     `overtime_tb` 
                                                 WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
     
                                                 if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                     $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                     
     
                                                     $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                     $OT_hour = $time_OT_con->format('H');
                                                     $OT_totalHour = intval($OT_hour);
     
                                                     $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
     
                                                 }
     
                                         //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }

                                        }
                                       

                                        // -----------------------BREAK Saturday END----------------------------//

                                        // -----------------------BREAK SUNDAY START----------------------------//
                                    else if($day_of_validHoliday === 'Sunday')
                                        {

                                            if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == '')
                                            {

                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowances) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowances;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                    //if HINDI restday
                                                    $double_pay_holiday += ($daily_rate * 1.3) + $allowances; //if holiday and worked

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                    $result_holiday_OT = mysqli_query($conn, " SELECT
                                                        *
                                                    FROM 
                                                        `overtime_tb` 
                                                    WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
        
                                                    if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                        $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                        
        
                                                        $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                        $OT_hour = $time_OT_con->format('H');
                                                        $OT_totalHour = intval($OT_hour);
        
                                                        $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
        
                                                    }
        
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                       

                                        // -----------------------BREAK SUNDAY END----------------------------//

                                    //-------------------------Para sa nag WORKED ang employee sa holdiday AT RESTDAY NIYA END----------------------




                                    }// IF PUMASOK AT HINDI ABSENT

                           }// END OF IF STATEMENT OF "$validation_eligible_holiday === 'YES'"

                            


                    //-----------------------END COMPUTATION FOR HOLIDAY PAY IF  $validation_eligible_holiday = 'YES'--------------------

                        }

                    } // end FOr each
                } //end $sql_att_all
                //--------------------------------------Special HOLIDAY END -------------------------------------------------


?>