<?php
include '../../config.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
    'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
        // Open uploaded CSV file with read-only mode
        $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Get row data for each employee
                $status = $line[0];
                $empid = $line[1];
                $date = $line[2];
                $time_in = $line[3];
                $time_out = $line[4];
                $late = '';
                $early_out = '';
                $overtime = '';
                $total_work = '';
                $total_rest = '';

                $CheckEmp = "SELECT * FROM employee_tb WHERE `empid` = '$empid'";
                $runEmp = mysqli_query($conn, $CheckEmp);
                if(mysqli_num_rows($runEmp) === 0){
                    echo "<script>window.location.href = '../../dtRecords?No employee found';</script>";
                    exit;
                }else{
                $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$empid'");
                if(mysqli_num_rows($result_emp_sched) > 0) {
                $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                $schedID = $row_emp_sched['schedule_name'];
    
                $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                    if(mysqli_num_rows($result_sched_tb) > 0) {
                        $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                        $sched_name =  $row_sched_tb['schedule_name'];
                        $col_monday_timein =  $row_sched_tb['mon_timein'];
                        $col_tuesday_timein =  $row_sched_tb['tues_timein'];
                        $col_wednesday_timein =  $row_sched_tb['wed_timein'];
                        $col_thursday_timein =  $row_sched_tb['thurs_timein'];
                        $col_friday_timein =  $row_sched_tb['fri_timein'];
                        $col_saturday_timein =  $row_sched_tb['sat_timein'];
                        $col_sunday_timein =  $row_sched_tb['sun_timein'];
                        $col_monday_timeout =  $row_sched_tb['mon_timeout'];
                        $col_tuesday_timeout =  $row_sched_tb['tues_timeout'];
                        $col_wednesday_timeout =  $row_sched_tb['wed_timeout'];
                        $col_thursday_timeout =  $row_sched_tb['thurs_timeout'];
                        $col_friday_timeout =  $row_sched_tb['fri_timeout'];
                        $col_saturday_timeout =  $row_sched_tb['sat_timeout'];
                        $col_sunday_timeout =  $row_sched_tb['sun_timeout'];
                        $col_grace_period = $row_sched_tb['grace_period'];

                        $day_of_week = date('l', strtotime($date)); // get the day of the week using the "l" format specifier 

                         if($day_of_week === 'Monday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_monday_timein);
                            $scheduled_timeout = new DateTime($col_monday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_monday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_monday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_monday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_monday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_monday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_monday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                            }
                        } //Close bracket Monday

                        else if($day_of_week === 'Tuesday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_tuesday_timein);
                            $scheduled_timeout = new DateTime($col_tuesday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_tuesday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_tuesday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_tuesday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_tuesday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_tuesday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_tuesday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                            }
                        } //Close bracket Tuesday

                        else if($day_of_week === 'Wednesday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_wednesday_timein);
                            $scheduled_timeout = new DateTime($col_wednesday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_wednesday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_wednesday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_wednesday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_wednesday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_wednesday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_wednesday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                            }
                        } //Close bracket Wednesday


                        else if($day_of_week === 'Thursday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_thursday_timein);
                            $scheduled_timeout = new DateTime($col_thursday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_thursday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_thursday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_thursday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_thursday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_thursday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_thursday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                            }
                        } //Close bracket Thursday

                        else if($day_of_week === 'Friday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_friday_timein);
                            $scheduled_timeout = new DateTime($col_friday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_friday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_friday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_friday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_friday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_friday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_friday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                            }
                        } //Close bracket Friday


                        else if($day_of_week === 'Saturday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_saturday_timein);
                            $scheduled_timeout = new DateTime($col_saturday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_saturday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_saturday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_saturday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_saturday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_saturday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_saturday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                             }
                        } //Close bracket Saturday


                        else if($day_of_week === 'Sunday'){
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);

                            $scheduled_timein = new DateTime($col_sunday_timein);
                            $scheduled_timeout = new DateTime($col_sunday_timeout);

                            $lunchbreak_start_converted = new DateTime('12:00:00');
                            $lunchbreak_end_converted = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_sunday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }
                            
                            if (empty($time_in) && !empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && empty($time_out)) {
                                continue; // Skip this record
                            } else if (!empty($time_in) && !empty($time_out)) {
                                if ($time_in_datetime < $lunchbreak_start_converted) {
                                    if ($time_in_datetime <= $grace_period_total) {
                                        $late = '00:00:00';
                                        $addtime_in = $scheduled_timein;
                                    } else {
                                        $late = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                        $addtime_in = $time_in_datetime;
                                    }
                                } else {
                                    // Subtract 1 hour from late
                                    $lates = $time_in_datetime->diff($scheduled_timein)->format('%H:%I:%S');
                                    $late_datetime = new DateTime($lates);
                                    $late_datetime->sub(new DateInterval('PT1H'));
                                    $late = $late_datetime->format('H:i:s');
                                }

                                if ($time_out < $col_sunday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_time = new DateTime($col_sunday_timeout);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $undertime = $interval->format('%h:%i:%s');
                                }else{
                                    $undertime = "00:00:00";
                                }

                                if ($time_out > $col_sunday_timeout) {
                                    $time_out_datetime = new DateTime($time_out);
                                    $scheduled_timeout = new DateTime( $col_sunday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                } else {
                                    $overtime = '00:00:00';
                                }

                                if($time_out_datetime > $lunchbreak_start_converted){
                                    if($time_out != $col_sunday_timeout){
                                        $addtime_out = $time_out_datetime;
                                    }else{
                                        $addtime_out = $scheduled_timeout;
                                    }
                                }else{
                                    $addtime_out = $time_out_datetime;
                                }
                                    if($time_in_datetime < $lunchbreak_start_converted && $time_out_datetime > $lunchbreak_start_converted){
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }else{
                                        $total_works = $addtime_out->diff($addtime_in)->format('%H:%I:%S');
                                        //Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work = $total_work_datetime->format('H:i:s');
                                    }
                             }
                        } //Close bracket Sunday

                    }else{
                        echo "<script>
                        alert('No Schedule found');
                        window.location.href = '../../dtRecords';
                        </script>";
                        exit;
                    } 
                }else{
                    echo "<script>
                    alert('No Schedule');
                    window.location.href = '../../dtRecords';
                    </script>";
                    exit;
                }
            }       
                    $prevQuery = "SELECT * FROM attendances WHERE `empid` = '$empid' AND `date` = '$date'";
                    $prevResult = $conn->query($prevQuery);

                    if ($prevResult->num_rows > 0) {
                        // Update the existing record
                        $query = "UPDATE attendances SET status = '$status',
                            time_in = '$time_in', time_out = '$time_out', late = '$late', 
                            early_out = '$undertime', overtime = '$overtime', total_work = '$total_work' 
                            WHERE empid = '$empid' AND date = '$date'";
                    } else {
                        // Insert a new record
                        $query = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) 
                            VALUES ('$status', '$empid', '$date', '$time_in', '$time_out', '$late', '$undertime', '$overtime', '$total_work')";
                    }

                    $conn->query($query);
           }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
 }

// Redirect to the listing page
header("Location: ../../dtRecords.php".$qstring);