<?php
 
    include '../../config.php';

    //employee query
    // $empSql = "SELECT * FROM employee_tb";
    // $empResult = mysqli_query($conn, $empSql);
    // $empRow = mysqli_fetch_assoc($empResult);

    // $empid = $empRow['empid']; //employee query end 

    // $schedSql = "SELECT * FROM empschedule_tb";
    // $schedResult = mysqli_query($conn, $schedSql);
    
    // if(mysqli_num_rows($schedResult) > 0){
    //     $sched = array();

    //     while($schedRow = mysqli_fetch_assoc($schedResult)){
    //         $schedEmpid = $schedRow['empid'];

    //         $sched[] = array('schedEmpid' => $schedEmpid);
    //     }

    //     foreach($sched as $schedule){
    //         $schedEmpid = $schedule['schedEmpid'];


    //         $globalSql = "SELECT * FROM attendance_time_in
    //         INNER JOIN attendance_time_out ON attendance_time_in.time_in_personId";
    //         $globalResult = mysqli_query($conn, $globalSql);
            
    //         if(mysqli_num_rows($globalResult) > 0){
    //             $globalAttendance = array();
        
    //             while($globalRow = mysqli_fetch_assoc($globalResult)){
    //                 $time_in = $globalRow['time_in'];
    //                 $time_out = $globalRow['time_out'];
        
    //                 $date_timein = $globalRow['date_time_in'];
    //                 $date_timeout = $globalRow['date_time_out'];
        
    //                 $timein_empid = $globalRow['time_in_personId'];
    //                 $timeout_empid = $globalRow['time_out_personId'];
        
    //                 $globalAttendance[] = array('time_in' => $time_in,
    //                 'time_out' => $time_out,
    //                 'date_timein' => $date_timein,
    //                 'date_timeout' => $date_timeout,
    //                 'timein_empid' => $timein_empid,
    //                 'timeout_empid' => $timeout_empid);
    //             }
        
    //             foreach($globalAttendance as $globalAttendances){
    //                 $timein_empid = $globalAttendances['timein_empid'];
    //                 $timeout_empid = $globalAttendances['timeout_empid'];
    //                 $time_in = $globalAttendances['time_in'];
    //                 $time_out = $globalAttendances['time_out'];
    //                 $date_timein = $globalAttendances['date_timein'];
    //                 $date_timeout = $globalAttendances['date_timeout'];
        
    //                 if ($timein_empid == $schedEmpid && $timeout_empid == $schedEmpid) {
    //                     echo "haha";
    //                 } elseif ($timein_empid == $schedEmpid) {
    //                     echo "hoho";
    //                 }
                    
    //             }//end ng globalAttendance num_rows
        
    //         }//end ng globalAttendance num_rows
        
            
    //     }//end ng empschedule num_rows
    // }//end ng empschedule num_rows

    
    $currentTimestamp = time();
    $currentDate = date('Y-m-d', $currentTimestamp); 

    //time in query
    $timeInSql = "SELECT * FROM attendance_time_in  WHERE date_time_in = '$currentDate'";
    $timeInResult = mysqli_query($conn, $timeInSql);

    if(mysqli_num_rows($timeInResult) > 0){
        $time_in_array = array();
        
        while($timeInRow = mysqli_fetch_assoc($timeInResult)){
            $time_in_date = $timeInRow['date_time_in'];
            $time_in = $timeInRow['time_in'];
            $time_in_empid = $timeInRow['time_in_personId'];

            // echo $time_in_empid;

            $time_in_array[] = array('time_in_empid' => $time_in_empid,
                                     'time_in' => $time_in,
                                     'time_in_date' => $time_in_date );
        }

        foreach($time_in_array as $attendance_in_array){
            $empid = $attendance_in_array['time_in_empid'];
            $date = $attendance_in_array['time_in_date'];
            $bio_timein = $attendance_in_array['time_in'];

            $sql = "SELECT * FROM empschedule_tb WHERE empid = $empid";
            $resulta = mysqli_query($conn, $sql);
            if(mysqli_num_rows($resulta) > 0){
                $row1 = mysqli_fetch_assoc($resulta);

                
                $stmt = "SELECT 
                DATE_SUB(DATE(NOW()), INTERVAL WEEKDAY(NOW()) DAY) AS monday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (1 - WEEKDAY(NOW())) DAY) AS tuesday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (2 - WEEKDAY(NOW())) DAY) AS wednesday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (3 - WEEKDAY(NOW())) DAY) AS thursday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (4 - WEEKDAY(NOW())) DAY) AS friday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (5 - WEEKDAY(NOW())) DAY) AS saturday_date,
                DATE_ADD(DATE(NOW()), INTERVAL (6 - WEEKDAY(NOW())) DAY) AS sunday_date,
                mon_timein,
                mon_timeout,
                tues_timein,
                tues_timeout,
                wed_timein,
                wed_timeout,
                thurs_timein,
                thurs_timeout,
                fri_timein,
                fri_timeout,
                sat_timein,
                sat_timeout,
                sun_timein,
                sun_timeout,
                grace_period,
                sched_ot
            FROM schedule_tb
            WHERE schedule_name = '".$row1['schedule_name']."'";
          

            } else{
                // echo '<script> alert("Employee has no schedule!"); </script>';
                // header("Location: ../../attendance.php?noSchedule");
                // exit;
                echo "No schedule <br>"; 
           
            }
            $result = mysqli_query($conn, $stmt);
            while($time = mysqli_fetch_assoc($result)){

                $grace_period = $time['grace_period'];  
                
                $sched_ot = $time['sched_ot'];

                $monday = date('l', strtotime(strtr($time['monday_date'], '/', '-')));
                

                $monday_timein = $time['mon_timein'];
                $monday_timeout = $time['mon_timeout'];

                $tuesday = date('l', strtotime(strtr($time['tuesday_date'], '/', '-'))); 
              
                

                $tuesday_timein = $time['tues_timein'];
                $tuesday_timeout = $time['tues_timeout'];

                $wednesday = date('l', strtotime(strtr($time['wednesday_date'], '/', '-')));  
               

                $wednesday_timein = $time['wed_timein'];
                $wednesday_timeout = $time['wed_timeout'];

                $thursday = date('l', strtotime(strtr($time['thursday_date'], '/', '-')));   
              

                $thursday_timein = $time['thurs_timein'];
                $thursday_timeout = $time['thurs_timeout'];

                $friday = date('l', strtotime(strtr($time['friday_date'], '/', '-')));     
              

                $friday_timein = $time['fri_timein'];
                $friday_timeout = $time['fri_timeout'];

                $saturday = date('l', strtotime(strtr($time['saturday_date'], '/', '-')));    
              

                $saturday_timein = $time['sat_timein'];
                $saturday_timeout = $time['sat_timeout'];

                $sunday = date('l', strtotime(strtr($time['sunday_date'], '/', '-')));    
              

                $sunday_timein = $time['sun_timein'];
                $sunday_timeout = $time['sun_timeout'];

                $currentTimestamp = time();
                $currentDate = date('Y-m-d', $currentTimestamp); 

                // Get the current day of the week
                $currentDayOfWeek = date('l', $currentTimestamp);
             
                if($date !== $currentDate){
                    // echo '<script>alert("Error: Unable to insert a past or future date.")</script>';
                    // echo "<script>window.location.href = '../../attendance?wrongDate';</script>";
                    echo "wrong date <br>";
                    exit;
                }else{

                    if($currentDayOfWeek == $monday){
                         //grace period calculation
                        //  echo "it is monday";
                         $convert_timein = strtotime($monday_timein);

                         $convert_timein += $grace_period * 60; 
 
                         $grace_period_total = date("H:i:s", $convert_timein);
 
                         // echo $grace_period_total;
 
                         if($bio_timein > $grace_period_total){
                             $late = (new DateTime($bio_timein))->diff(new DateTime($monday_timein))->format('%H:%I:%S');
                            //  echo "your late <br> ";
                            //  echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
 
 
                             date_default_timezone_set("Asia/Manila");
                             $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                 if($current_time >= '12:00:00'){
                                     $late = date("H:i:s", strtotime($late) - 3600); 
                                 }
 
                            //  echo "late when 12:00 === ",$late;
                         }else{
                             $late = '00:00:00';
                             
                         }
 
                             $early_out = '00:00:00';
                             $overtime = '00:00:00';
                             $total_work = '00:00:00';
                             $total_rest = '00:00:00';
                             $time_out = '00:00:00';    
                             $status = 'Present';

                             
                             
                             echo "<br>", $early_out , "<br>",
                                 $overtime , "<br>",
                                 $total_work , "<br>",
                                 $total_rest , "<br>",
                                 $late, "<br>" , $time_in, "<br>", $empid, "<br> <br>";

                    }elseif($currentDayOfWeek == $tuesday){
                        $convert_timein = strtotime($tuesday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($tuesday_timein))->format('%H:%I:%S');
                            echo "your late <br> ";
                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present';
                            
                            
                            echo "<br>", $early_out , "<br>",
                                $overtime , "<br>",
                                $total_work , "<br>",
                                $total_rest , "<br>";


                    }elseif($currentDayOfWeek == $wednesday){
                        $convert_timein = strtotime($wednesday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($wednesday_timein))->format('%H:%I:%S');
                            echo "your late <br> ";
                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present';   
                            
                            
                            echo "<br>", $early_out , "<br>",
                                $overtime , "<br>",
                                $total_work , "<br>",
                                $total_rest , "<br>";

                    }elseif($currentDayOfWeek == $thursday){

                        //grace period calculation
                        $convert_timein = strtotime($thursday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;   

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($thursday_timein))->format('%H:%I:%S');
                            echo "your late <br> ";
                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present';  
                            
                            
                        // echo "<br> this is time in ", $time_in , "<br>";
                        // echo "this is time out ", $time_out , "<br>";
                        // echo "this is late ", $late , "<br>";
                        // echo "this is total work ", $total_work , "<br>";
                        // echo "this is overtime ", $overtime , "<br>";
                        // echo "this is early out ", $early_out , "<br>";
                        // echo "this is total rest ", $total_rest , "<br>";
                        // echo "this is status ", $status , "<br>";
                        // echo "this is empid ", $empid , "<br>";
                        // echo "this is date ", $date , "<br>";

                                
                     
                        

                    }elseif($currentDayOfWeek == $friday){
                        $convert_timein = strtotime($friday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($friday_timein))->format('%H:%I:%S');
                            // echo "<br> <br>your late <br> ";
                            // echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            // echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present';
                            
                            
                            // echo "<br>", $early_out , "<br>",
                            //     $overtime , "<br>",
                            //     $total_work , "<br>",
                            //     $total_rest , "<br>";


                    }elseif($currentDayOfWeek == $saturday){
                        $convert_timein = strtotime($saturday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($saturday_timein))->format('%H:%I:%S');
                            echo "your late <br> ";
                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present'; 
                            
                            
                            echo "<br>", $early_out , "<br>",
                                $overtime , "<br>",
                                $total_work , "<br>",
                                $total_rest , "<br>";

                    }elseif($currentDayOfWeek == $sunday){
                        $convert_timein = strtotime($sunday_timein);

                        $convert_timein += $grace_period * 60; 

                        $grace_period_total = date("H:i:s", $convert_timein);

                        // echo $grace_period_total;

                        if($bio_timein > $grace_period_total){
                            $late = (new DateTime($bio_timein))->diff(new DateTime($sunday_timein))->format('%H:%I:%S');
                            echo "your late <br> ";
                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";


                            date_default_timezone_set("Asia/Manila");
                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                if($current_time >= '12:00:00'){
                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                }

                            echo "late when 12:00 === ",$late;
                        }else{
                            $late = '00:00:00';
                            
                        }

                        $early_out = '00:00:00';
                        $overtime = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '00:00:00';
                        $time_out = '00:00:00';    
                        $status = 'Present';   
                            
                            
                            echo "<br>", "this is early out", $early_out , "<br>",
                                "this is ot " ,$overtime , "<br>",
                                "this is total work ", $total_work , "<br>",
                                "this is total rest ",$total_rest , "<br>",
                                "this is time_in ",$time_in, "<br>";
                                

                    }
                }

                $validSql = "SELECT * FROM attendances WHERE `empid` = '$empid' AND `date` = '$date' ";
                $validResult = mysqli_query($conn,$validSql);

                

                if(mysqli_num_rows($validResult) > 0 ){
                   
    
                $currentTimestamp = time();
                $currentDate = date('Y-m-d', $currentTimestamp); 

                $sql = "SELECT * FROM attendances WHERE `date` = '$currentDate'";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result) > 0){
                    $attendance = array();
                    while($row = mysqli_fetch_assoc($result)){
                        $status = $row['status'];
                        $empid = $row['empid'];
                        $date = $row['date'];
                        $att_timein = $row['time_in'];
                        $att_timeout = $row['time_out'];


                        $attendance[] = array('status' => $status,
                                            'empid' => $empid,
                                            'date' => $date,
                                            'att_timeout' => $att_timeout,
                                            'att_timein' => $att_timein);
                    }
                        foreach($attendance as $att_aray){
                            $empid = $att_aray['empid'];
                            $status = $att_aray['status'];
                            $date = $att_aray['date'];
                            $att_timein = $att_aray['att_timeout'];
                            $att_timeout = $att_aray['att_timein'];

                            if($status == 'Absent' && $date == $currentDate && $att_timein == '00:00:00' && $att_timeout == '00:00:00'){
                                $currentTimestamp = time();
                                $currentDate = date('Y-m-d', $currentTimestamp); 
                            
                                //time in query
                                $timeInSql = "SELECT * FROM attendance_time_in  WHERE date_time_in = '$currentDate'";
                                $timeInResult = mysqli_query($conn, $timeInSql);
                            
                                if(mysqli_num_rows($timeInResult) > 0){
                                    $time_in_array = array();
                                    
                                    while($timeInRow = mysqli_fetch_assoc($timeInResult)){
                                        $time_in_date = $timeInRow['date_time_in'];
                                        $time_in = $timeInRow['time_in'];
                                        $time_in_empid = $timeInRow['time_in_personId'];
                            
                                        // echo $time_in_empid;
                            
                                        $time_in_array[] = array('time_in_empid' => $time_in_empid,
                                                                'time_in' => $time_in,
                                                                'time_in_date' => $time_in_date );
                                    }
                
                        foreach($time_in_array as $attendance_in_array){
                            $empid = $attendance_in_array['time_in_empid'];
                            $date = $attendance_in_array['time_in_date'];
                            $bio_timein = $attendance_in_array['time_in'];
                
                            $sql = "SELECT * FROM empschedule_tb WHERE empid = $empid";
                            $resulta = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($resulta) > 0){
                                $row1 = mysqli_fetch_assoc($resulta);
                
                                
                                $stmt = "SELECT 
                                DATE_SUB(DATE(NOW()), INTERVAL WEEKDAY(NOW()) DAY) AS monday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (1 - WEEKDAY(NOW())) DAY) AS tuesday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (2 - WEEKDAY(NOW())) DAY) AS wednesday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (3 - WEEKDAY(NOW())) DAY) AS thursday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (4 - WEEKDAY(NOW())) DAY) AS friday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (5 - WEEKDAY(NOW())) DAY) AS saturday_date,
                                DATE_ADD(DATE(NOW()), INTERVAL (6 - WEEKDAY(NOW())) DAY) AS sunday_date,
                                mon_timein,
                                mon_timeout,
                                tues_timein,
                                tues_timeout,
                                wed_timein,
                                wed_timeout,
                                thurs_timein,
                                thurs_timeout,
                                fri_timein,
                                fri_timeout,
                                sat_timein,
                                sat_timeout,
                                sun_timein,
                                sun_timeout,
                                grace_period,
                                sched_ot
                            FROM schedule_tb
                            WHERE schedule_name = '".$row1['schedule_name']."'";
                          
                
                            } else{
                                // echo '<script> alert("Employee has no schedule!"); </script>';
                                // header("Location: ../../attendance.php?noSchedule");
                                // exit;
                                echo "No schedule <br>"; 
                           
                            }
                            $result = mysqli_query($conn, $stmt);
                            while($time = mysqli_fetch_assoc($result)){
                
                                $grace_period = $time['grace_period'];  
                                
                                $sched_ot = $time['sched_ot'];
                
                                $monday = date('l', strtotime(strtr($time['monday_date'], '/', '-')));
                                
                
                                $monday_timein = $time['mon_timein'];
                                $monday_timeout = $time['mon_timeout'];
                
                                $tuesday = date('l', strtotime(strtr($time['tuesday_date'], '/', '-'))); 
                              
                                
                
                                $tuesday_timein = $time['tues_timein'];
                                $tuesday_timeout = $time['tues_timeout'];
                
                                $wednesday = date('l', strtotime(strtr($time['wednesday_date'], '/', '-')));  
                               
                
                                $wednesday_timein = $time['wed_timein'];
                                $wednesday_timeout = $time['wed_timeout'];
                
                                $thursday = date('l', strtotime(strtr($time['thursday_date'], '/', '-')));   
                              
                
                                $thursday_timein = $time['thurs_timein'];
                                $thursday_timeout = $time['thurs_timeout'];
                
                                $friday = date('l', strtotime(strtr($time['friday_date'], '/', '-')));     
                              
                
                                $friday_timein = $time['fri_timein'];
                                $friday_timeout = $time['fri_timeout'];
                
                                $saturday = date('l', strtotime(strtr($time['saturday_date'], '/', '-')));    
                              
                
                                $saturday_timein = $time['sat_timein'];
                                $saturday_timeout = $time['sat_timeout'];
                
                                $sunday = date('l', strtotime(strtr($time['sunday_date'], '/', '-')));    
                              
                
                                $sunday_timein = $time['sun_timein'];
                                $sunday_timeout = $time['sun_timeout'];
                
                                $currentTimestamp = time();
                                $currentDate = date('Y-m-d', $currentTimestamp); 
                
                                // Get the current day of the week
                                $currentDayOfWeek = date('l', $currentTimestamp);
                             
                                if($date !== $currentDate){
                                    // echo '<script>alert("Error: Unable to insert a past or future date.")</script>';
                                    // echo "<script>window.location.href = '../../attendance?wrongDate';</script>";
                                    echo "wrong date <br>";
                                    exit;
                                }else{
                
                                    if($currentDayOfWeek == $monday){
                                         //grace period calculation
                                        //  echo "it is monday";
                                         $convert_timein = strtotime($monday_timein);
                
                                         $convert_timein += $grace_period * 60; 
                 
                                         $grace_period_total = date("H:i:s", $convert_timein);
                 
                                         // echo $grace_period_total;
                 
                                         if($bio_timein > $grace_period_total){
                                             $late = (new DateTime($bio_timein))->diff(new DateTime($monday_timein))->format('%H:%I:%S');
                                            //  echo "your late <br> ";
                                            //  echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                 
                 
                                             date_default_timezone_set("Asia/Manila");
                                             $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                 if($current_time >= '12:00:00'){
                                                     $late = date("H:i:s", strtotime($late) - 3600); 
                                                 }
                 
                                            //  echo "late when 12:00 === ",$late;
                                         }else{
                                             $late = '00:00:00';
                                             
                                         }
                 
                                             $early_out = '00:00:00';
                                             $overtime = '00:00:00';
                                             $total_work = '00:00:00';
                                             $total_rest = '00:00:00';
                                             $time_out = '00:00:00';    
                                             $status = 'Present';
                
                                             
                                             
                                             echo "<br>", $early_out , "<br>",
                                                 $overtime , "<br>",
                                                 $total_work , "<br>",
                                                 $total_rest , "<br>",
                                                 $late, "<br>" , $time_in, "<br>", $empid, "<br> <br>";
                
                                    }elseif($currentDayOfWeek == $tuesday){
                                        $convert_timein = strtotime($tuesday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($tuesday_timein))->format('%H:%I:%S');
                                            echo "your late <br> ";
                                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present';
                                            
                                            
                                            echo "<br>", $early_out , "<br>",
                                                $overtime , "<br>",
                                                $total_work , "<br>",
                                                $total_rest , "<br>";
                
                
                                    }elseif($currentDayOfWeek == $wednesday){
                                        $convert_timein = strtotime($wednesday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($wednesday_timein))->format('%H:%I:%S');
                                            echo "your late <br> ";
                                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present';   
                                            
                                            
                                            echo "<br>", $early_out , "<br>",
                                                $overtime , "<br>",
                                                $total_work , "<br>",
                                                $total_rest , "<br>";
                
                                    }elseif($currentDayOfWeek == $thursday){
                
                                        //grace period calculation
                                        $convert_timein = strtotime($thursday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($thursday_timein))->format('%H:%I:%S');
                                            echo "your late <br> ";
                                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present';  
                                            
                                            
                                        echo "<br> this is time in ", $time_in , "<br>";
                                        echo "this is time out ", $time_out , "<br>";
                                        echo "this is late ", $late , "<br>";
                                        echo "this is total work ", $total_work , "<br>";
                                        echo "this is overtime ", $overtime , "<br>";
                                        echo "this is early out ", $early_out , "<br>";
                                        echo "this is total rest ", $total_rest , "<br>";
                                        echo "this is status ", $status , "<br>";
                                        echo "this is empid ", $empid , "<br>";
                                        echo "this is date ", $date , "<br>";

                                                
                                     
                                        
                
                                    }elseif($currentDayOfWeek == $friday){
                                        $convert_timein = strtotime($friday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($friday_timein))->format('%H:%I:%S');
                                            // echo "<br> <br>your late <br> ";
                                            // echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            // echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present';
                                            
                                            
                                            // echo "<br>", $early_out , "<br>",
                                            //     $overtime , "<br>",
                                            //     $total_work , "<br>",
                                            //     $total_rest , "<br>";
                
                
                                    }elseif($currentDayOfWeek == $saturday){
                                        $convert_timein = strtotime($saturday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($saturday_timein))->format('%H:%I:%S');
                                            echo "your late <br> ";
                                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present'; 
                                            
                                            
                                            echo "<br>", $early_out , "<br>",
                                                $overtime , "<br>",
                                                $total_work , "<br>",
                                                $total_rest , "<br>";
                
                                    }elseif($currentDayOfWeek == $sunday){
                                        $convert_timein = strtotime($sunday_timein);
                
                                        $convert_timein += $grace_period * 60; 
                
                                        $grace_period_total = date("H:i:s", $convert_timein);
                
                                        // echo $grace_period_total;
                
                                        if($bio_timein > $grace_period_total){
                                            $late = (new DateTime($bio_timein))->diff(new DateTime($sunday_timein))->format('%H:%I:%S');
                                            echo "your late <br> ";
                                            echo "this is the late when the late is not yet at 12:00 === ", $late ,"<br>";
                
                
                                            date_default_timezone_set("Asia/Manila");
                                            $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                                if($current_time >= '12:00:00'){
                                                    $late = date("H:i:s", strtotime($late) - 3600); 
                                                }
                
                                            echo "late when 12:00 === ",$late;
                                        }else{
                                            $late = '00:00:00';
                                            
                                        }
                
                                        $early_out = '00:00:00';
                                        $overtime = '00:00:00';
                                        $total_work = '00:00:00';
                                        $total_rest = '00:00:00';
                                        $time_out = '00:00:00';    
                                        $status = 'Present';   
                                            
                                            
                                            echo "<br>", "this is early out", $early_out , "<br>",
                                                "this is ot " ,$overtime , "<br>",
                                                "this is total work ", $total_work , "<br>",
                                                "this is total rest ",$total_rest , "<br>",
                                                "this is time_in ",$time_in, "<br>";
                                                
                
                                    }
                                }
                    
                                $update = "UPDATE attendances SET `time_in` = '$time_in', `status` = '$status', `late` = '$late' WHERE `empid` = '$empid' AND `date` = '$date' ";

                                if($conn->query($update) == TRUE){
                                    echo "update!";
                                }else{
                                    echo "Error update " . $conn->error;
                                }

                          
                            }
                        }
                    }//eng ng time in

                }else{ //start ng else sa if time in lang
                    
                            $sql = "SELECT * FROM attendance_time_in
                            INNER JOIN attendance_time_out ON attendance_time_in.time_in_personId = attendance_time_out.time_out_personId WHERE date_time_in = '$currentDate' AND date_time_out = '$currentDate' ";

                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) > 0 ){

                        $attendance = array();

                        while($row = mysqli_fetch_assoc($result)){
                        $time_in = $row['time_in'];
                        $time_out = $row['time_out'];

                        $date_timein = $row['date_time_in'];
                        $date_timeout = $row['date_time_out'];

                        $empid = $row['time_in_personId'];

                        $attendance[] = array('time_in' => $time_in,
                                            'time_out' => $time_out,
                                            'date_timein' => $date_timein,
                                            'date_timeout' => $date_timeout,
                                            'empid' => $empid);
                        }

                        foreach($attendance as $attendances){
                        $empid = $attendances['empid'];
                        $time_out = $attendances['time_out'];
                        $time_in = $attendances['time_in'];
                        $date_timein = $attendances['date_timein'];
                        $date_timeout = $attendances['date_timeout'];



                        // if($date_timein == $currentDate && $date_timeout == $currentDate)
                        // echo $date_timein ,"<br>", $date_timeout , "<br>";




                        $sql = "SELECT * FROM empschedule_tb WHERE empid = $empid";
                        $resulta = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($resulta) > 0){
                        $row1 = mysqli_fetch_assoc($resulta);

                        
                        $stmt = "SELECT 
                        DATE_SUB(DATE(NOW()), INTERVAL WEEKDAY(NOW()) DAY) AS monday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (1 - WEEKDAY(NOW())) DAY) AS tuesday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (2 - WEEKDAY(NOW())) DAY) AS wednesday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (3 - WEEKDAY(NOW())) DAY) AS thursday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (4 - WEEKDAY(NOW())) DAY) AS friday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (5 - WEEKDAY(NOW())) DAY) AS saturday_date,
                        DATE_ADD(DATE(NOW()), INTERVAL (6 - WEEKDAY(NOW())) DAY) AS sunday_date,
                        mon_timein,
                        mon_timeout,
                        tues_timein,
                        tues_timeout,
                        wed_timein,
                        wed_timeout,
                        thurs_timein,
                        thurs_timeout,
                        fri_timein,
                        fri_timeout,
                        sat_timein,
                        sat_timeout,
                        sun_timein,
                        sun_timeout,
                        grace_period,
                        sched_ot
                        FROM schedule_tb
                        WHERE schedule_name = '".$row1['schedule_name']."'";


                        } else{
                        // echo '<script> alert("Employee has no schedule!"); </script>';
                        // header("Location: ../../attendance.php?noSchedule");
                        // exit;
                        echo "No schedule <br>"; 

                        }
                        $result = mysqli_query($conn, $stmt);
                        while($time = mysqli_fetch_assoc($result)){

                        $grace_period = $time['grace_period'];  
                        
                        $sched_ot = $time['sched_ot'];

                        $monday = date('l', strtotime(strtr($time['monday_date'], '/', '-')));
                        

                        $monday_timein = $time['mon_timein'];
                        $monday_timeout = $time['mon_timeout'];

                        $tuesday = date('l', strtotime(strtr($time['tuesday_date'], '/', '-'))); 
                        
                        

                        $tuesday_timein = $time['tues_timein'];
                        $tuesday_timeout = $time['tues_timeout'];

                        $wednesday = date('l', strtotime(strtr($time['wednesday_date'], '/', '-')));  
                        

                        $wednesday_timein = $time['wed_timein'];
                        $wednesday_timeout = $time['wed_timeout'];

                        $thursday = date('l', strtotime(strtr($time['thursday_date'], '/', '-')));   
                        

                        $thursday_timein = $time['thurs_timein'];
                        $thursday_timeout = $time['thurs_timeout'];

                        $friday = date('l', strtotime(strtr($time['friday_date'], '/', '-')));     
                        

                        $friday_timein = $time['fri_timein'];
                        $friday_timeout = $time['fri_timeout'];

                        $saturday = date('l', strtotime(strtr($time['saturday_date'], '/', '-')));    
                        

                        $saturday_timein = $time['sat_timein'];
                        $saturday_timeout = $time['sat_timeout'];

                        $sunday = date('l', strtotime(strtr($time['sunday_date'], '/', '-')));    
                        

                        $sunday_timein = $time['sun_timein'];
                        $sunday_timeout = $time['sun_timeout'];

                        $currentTimestamp = time();
                        $currentDate = date('Y-m-d', $currentTimestamp); 

                        // Get the current day of the week
                        $currentDayOfWeek = date('l', $currentTimestamp);


                            if($currentDayOfWeek == $monday){
                                // echo "<br> <br> it is monday";
                                    // late function
                                    $convert_timein = strtotime($monday_timein);

                                    $convert_timein += $grace_period * 60; 

                                    $grace_period_total = date("H:i:s", $convert_timein);

                                    // echo $grace_period_total;

                                    if($time_in > $grace_period_total){
                                        $late = (new DateTime($time_in))->diff(new DateTime($monday_timein))->format('%H:%I:%S');
                                        // echo $late ,"<br>";


                                        date_default_timezone_set("Asia/Manila");
                                        $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                            if($time_in >= '12:00:00'){
                                                $late = date("H:i:s", strtotime($late) - 3600);

                                            }

                                        // echo "late when 12:00 === ",$late;
                                    }else{
                                        $late = '00:00:00';
                                    } //late end

                                    //early out function
                                    if($monday_timeout > $time_out){
                                        $early_out = (new DateTime($time_out))->diff(new DateTime($monday_timeout))->format('%H:%I:%S');
                                    }else{
                                        $early_out = '00:00:00';
                                    } //early out end

                                    
                                    //total work function
                                    if($time_out){
                                        $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                        if($late == '00:00:00'){
                                            $total_work = (new DateTime($monday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                        }

                                        date_default_timezone_set("Asia/Manila");
                                        $current_time = date("H:i:s"); 
                                        if($time_out > '01:00:00'){
                                            $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                        }

                                    }else{
                                        $total_work = '00:00:00';
                                    } //total work end

                                


                                    //overtime function
                                    $convert_timeout = strtotime($monday_timeout);

                                    $convert_timeout += $sched_ot * 60; 

                                    $sched_ot_total = date("H:i:s", $convert_timeout);

                                    if($time_out > $monday_timeout){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($monday_timeout))->format('%H:%I:%S');   

                                        if($time_out > $sched_ot_total){
                                            $overtime = (new DateTime($time_out))->diff(new DateTime($monday_timeout))->format('%H:%I:%S');
                                            
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                            
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                        //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                                $diff = $totalwork_convert->diff($late_convert);

                                                $total_work = $diff->format('%H:%i:%s');

                                                $total_work = sprintf(
                                                    "%02d:%02d:%02d",
                                                    $diff->h,
                                                    $diff->i,
                                                    $diff->s
                                                );

                                            
                                        }                    
                                        }else{
                                            $overtime = '00:00:00';
                                        }


                                    }else{
                                        $overtime = '00:00:00';
                                    } //overtime end

                                    $total_rest = '00:00:00';
                                    $status = 'Present';
                                    
                                //  echo "<br> this is early out " ,$early_out ;
                                //  echo "<br> this is late ", $late;
                                //  echo "<br> this is total work ", $total_work;
                                //  echo "<br> this is overtime ", $overtime;
                                //  echo "<br> this is time in ", $time_in;
                                //  echo "<br> this is time out ", $time_out;
                                //  echo "<br> this is total rest ", $total_rest;
                                //  echo "<br> this is status ", $status;


                            }elseif($currentDayOfWeek == $tuesday){
                                // echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($tuesday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($tuesday_timein))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($tuesday_timeout > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($tuesday_timeout))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($tuesday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); 
                                    if($time_out > '01:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                    }

                                }else{
                                    $total_work = '00:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($tuesday_timeout);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $tuesday_timeout){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($tuesday_timeout))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($tuesday_timeout))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';
                                
                                // echo "<br> this is early out " ,$early_out ;
                                // echo "<br> this is late ", $late;
                                // echo "<br> this is total work ", $total_work;
                                // echo "<br> this is overtime ", $overtime;
                                // echo "<br> this is time in ", $time_in;
                                // echo "<br> this is time out ", $time_out;
                                // echo "<br> this is total rest ", $total_rest;
                                // echo "<br> this is status ", $status;
                            }elseif($currentDayOfWeek == $wednesday){
                                // echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($wednesday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($wednesday_timein))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($wednesday_timeout > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($wednesday_timeout))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($wednesday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); 
                                    if($time_out > '01:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                    }

                                }else{
                                    $total_work = '00:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($wednesday_timeout);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $wednesday_timeout){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($wednesday_timeout))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($wednesday_timeout))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';
                                
                                // echo "<br> this is early out " ,$early_out ;
                                // echo "<br> this is late ", $late;
                                // echo "<br> this is total work ", $total_work;
                                // echo "<br> this is overtime ", $overtime;
                                // echo "<br> this is time in ", $time_in;
                                // echo "<br> this is time out ", $time_out;
                                // echo "<br> this is total rest ", $total_rest;
                                // echo "<br> this is status ", $status;
                            }elseif($currentDayOfWeek == $thursday){
                                // echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($wednesday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($wednesday_timeout))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($thursday_timeout > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($thursday_timeout))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($thursday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                        
                                        // echo $total_work;
                                        
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s");
                                     
                                    if($time_out > '12:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                        
                                        
                                    }

                                }else{
                                    $total_work = '10:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($thursday_timeout);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $thursday_timeout){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($thursday_timeout))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($thursday_timeout))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';
                                
                                echo "<br> this is early out " ,$early_out ;
                                echo "<br> this is late ", $late;
                                echo "<br> this is total work ", $total_work;
                                echo "<br> this is overtime ", $overtime;
                                echo "<br> this is time in ", $time_in;
                                echo "<br> this is time out ", $time_out;
                                echo "<br> this is total rest ", $total_rest;
                                echo "<br> this is status ", $status ,"<br><br>";

                            }elseif($currentDayOfWeek == $friday){
                                echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($friday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($friday_timein))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($friday_timein > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($friday_timein))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($friday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); 
                                    if($time_out > '01:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                    }

                                }else{
                                    $total_work = '00:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($friday_timein);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $friday_timein){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($friday_timein))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($friday_timein))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';
                                
                            

                                    
                            }elseif($currentDayOfWeek == $saturday){
                                echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($saturday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($saturday_timein))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($saturday_timeout > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($saturday_timeout))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($saturday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); 
                                    if($time_out > '01:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                    }

                                }else{
                                    $total_work = '00:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($saturday_timeout);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $saturday_timeout){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($saturday_timeout))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($saturday_timeout))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';
                                
                            }elseif($currentDayOfWeek == $sunday){
                                echo "<br> <br> it is monday";
                                // late function
                                $convert_timein = strtotime($sunday_timein);

                                $convert_timein += $grace_period * 60; 

                                $grace_period_total = date("H:i:s", $convert_timein);

                                // echo $grace_period_total;

                                if($time_in > $grace_period_total){
                                    $late = (new DateTime($time_in))->diff(new DateTime($sunday_timein))->format('%H:%I:%S');
                                    // echo $late ,"<br>";


                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); // This format will give you hours, minutes, and seconds
                                        if($time_in >= '12:00:00'){
                                            $late = date("H:i:s", strtotime($late) - 3600);

                                        }

                                    // echo "late when 12:00 === ",$late;
                                }else{
                                    $late = '00:00:00';
                                } //late end

                                //early out function
                                if($sunday_timeout > $time_out){
                                    $early_out = (new DateTime($time_out))->diff(new DateTime($sunday_timeout))->format('%H:%I:%S');
                                }else{
                                    $early_out = '00:00:00';
                                } //early out end

                                
                                //total work function
                                if($time_out){
                                    $total_work = (new DateTime($time_in))->diff(new DateTime($time_out))->format('%H:%I:%S');

                                    if($late == '00:00:00'){
                                        $total_work = (new DateTime($sunday_timein))->diff(new DateTime($time_out))->format('%H:%I:%S');
                                    }

                                    date_default_timezone_set("Asia/Manila");
                                    $current_time = date("H:i:s"); 
                                    if($time_out > '01:00:00'){
                                        $total_work = date("H:i:s", strtotime($total_work) - 3600); 
                                    }

                                }else{
                                    $total_work = '00:00:00';
                                } //total work end

                                


                                //overtime function
                                $convert_timeout = strtotime($sunday_timeout);

                                $convert_timeout += $sched_ot * 60; 

                                $sched_ot_total = date("H:i:s", $convert_timeout);

                                if($time_out > $sunday_timeout){
                                    $overtime = (new DateTime($time_out))->diff(new DateTime($sunday_timeout))->format('%H:%I:%S');   

                                    if($time_out > $sched_ot_total){
                                        $overtime = (new DateTime($time_out))->diff(new DateTime($sunday_timeout))->format('%H:%I:%S');
                                        
                                        $overtime_convert = new DateTime($overtime);
                                        $totalwork_convert = new DateTime($total_work);
                                        
                                        $results = $totalwork_convert->add(new DateInterval('PT' . $overtime_convert->format('H') . 'H' . $overtime_convert->format('i') . 'M'));

                                        $total_work =  $results->format('H:i:s');

                                    //    echo $total_work;

                                        if($time_in > $grace_period_total ){
                                            $late_convert = new DateTime($late);
                                            $totalwork_convert = new DateTime($total_work);

                                            $diff = $totalwork_convert->diff($late_convert);

                                            $total_work = $diff->format('%H:%i:%s');

                                            $total_work = sprintf(
                                                "%02d:%02d:%02d",
                                                $diff->h,
                                                $diff->i,
                                                $diff->s
                                            );

                                            
                                        }                    
                                    }else{
                                        $overtime = '00:00:00';
                                    }


                                }else{
                                    $overtime = '00:00:00';
                                } //overtime end

                                $total_rest = '00:00:00';
                                $status = 'Present';

                            }

                            // include '../../config.php';
                            // echo $empid;
                                //insert & update
                            $existingSql = "SELECT * FROM attendances WHERE `empid` = '$empid' AND `date` = '$date' ";
                            $existingResult = mysqli_query($conn, $existingSql);

                            if(mysqli_num_rows($existingResult) > 0){
                                $updateSql = "UPDATE attendances SET `time_in` = '$time_in', `time_out` = '$time_out', `late` = '$late', `early_out` = '$early_out', `overtime` = '$overtime', `total_work` = '$total_work', `total_rest` = '$total_rest' WHERE `empid` = '$empid' AND `date` = '$date' " ;

                                if(mysqli_query($conn, $updateSql)){
                                    // header("Location: ../../attendance.php");
                                }else{
                                    echo "Error Inserting data " .mysqli_error($conn);
                                }
                            }
                        
                        }
                    }
                } //end ng time in and out

                }
            }

    }
                }else{ //insert pag walang data

                    $insert = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`)
                    VALUES ('$status', '$empid', '$date', '$time_in', '$time_out', '$late', '$early_out' , '$overtime', '$total_work', '$total_rest') ";

                        if($conn->query($insert) == TRUE){
                            echo "inserted";
                        }else{
                            echo "Error update " . $conn->error;
                        }
                        
                }
          
            }
        }
    }//eng ng time in

    

    mysqli_close($conn);


?>