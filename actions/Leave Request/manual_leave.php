<?php
include '../../config.php';

if (isset($_POST['yesCorrect'])) {
    $empidArray = $_POST['empid'][0];
    $empids = explode(",", $empidArray);

    $TypeofLeave = $_POST['name_LeaveT'];
    $LeavePeriod = $_POST['name_LeaveP'];

    $Departmentselected = $_POST['department'];
    $StartDate = $_POST['name_STRdate'];
    $EndDate = $_POST['name_ENDdate'];

    foreach ($empids as $EmployeeId) {

        
        $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE `empid` = '$EmployeeId'");
        if(mysqli_num_rows($result_emp_sched) > 0) {
        $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
        $schedID = $row_emp_sched['schedule_name'];

        $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$schedID'");
        $row_sched_tb = mysqli_fetch_assoc($result_Sched);
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

                            $start_date = new DateTime($StartDate);
                            $end_date = new DateTime($EndDate);
                            $interval = new DateInterval('P1D'); // 1 day interval
                            $daterange = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // Include end date
                            $leaveDays = iterator_count($daterange);

                            $leaveDates = [];
                            foreach ($daterange as $date) {
                                $leaveDates[] = $date->format('l');
                                $leaveDates[] = $date->format('Y-m-d');
                            }
                            
                            if ($TypeofLeave === 'Vacation Leave' || $TypeofLeave === 'Sick Leave' || $TypeofLeave === 'Bereavement Leave') {
                                if($LeavePeriod === 'Full Day'){
                                    $CheckCredits = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE `col_empID` = '$EmployeeId'");
                                
                                    if ($CheckCredits->num_rows > 0) {
                                        $rowCredits = $CheckCredits->fetch_assoc();
                                        if ($TypeofLeave === 'Vacation Leave') {
                                            $LeaveCredits = $rowCredits['col_vctionCrdt'];
                                        } elseif ($TypeofLeave === 'Sick Leave') {
                                            $LeaveCredits = $rowCredits['col_sickCrdt'];
                                        } elseif ($TypeofLeave === 'Bereavement Leave') {
                                            $LeaveCredits = $rowCredits['col_brvmntCrdt'];
                                        }
                                    } else {
                                        $LeaveCredits = 0; // Kung walang credits, itakda ito sa 0.
                                    }

                                    $time_in = "00:00:00";
                                    $time_out = "00:00:00";
                                    $late = "00:00:00";
                                    $undertime = "00:00:00";
                                    $overtime = "00:00:00";
                                    $total_work = "00:00:00";
                                    $total_rest = "00:00:00";
                            

                            $LeaveDaysOnLeave = min($leaveDays, $LeaveCredits);
                            $LeaveDaysLWOP = max(0, $leaveDays - $LeaveDaysOnLeave);

                            // Loop para sa mga araw at iset ang $PayMethod batay sa bilang ng leave credits.
                            for ($i = 0; $i < $leaveDays; $i++) { // Baguhin ang pagsisimula ng $i mula 1 to 0
                                if ($i < $LeaveDaysOnLeave) {
                                    $PayMethod = 'On-Leave';
                                    
                                    // Dito ay maaari mong idagdag ang code para bawasan ang mga credits.
                                    // Kunin ang kasalukuyang credits mula sa leaveinfo_tb
                                    $GetCreditsQuery = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE `col_empID` = '$EmployeeId'");
                                    if ($GetCreditsQuery->num_rows > 0) {
                                        $rowCredits = $GetCreditsQuery->fetch_assoc();
                                        if ($TypeofLeave === 'Vacation Leave') {
                                            $CurrentCredits = $rowCredits['col_vctionCrdt'];
                                        } elseif ($TypeofLeave === 'Sick Leave') {
                                            $CurrentCredits = $rowCredits['col_sickCrdt'];
                                        } elseif ($TypeofLeave === 'Bereavement Leave') {
                                            $CurrentCredits = $rowCredits['col_brvmntCrdt'];
                                        }
                                    } else {
                                        $CurrentCredits = 0; // Kung wala kang nakuhang credits, itakda ito sa 0.
                                    }
                            
                                    // I-compute ang bagong halaga ng credits pagkatapos ng leave deduction.
                                    $NewCredits = max(0, $CurrentCredits - $leaveDays); // Bawas ng 1 kada araw
                                    // I-update ang leaveinfo_tb table para sa `col_empID` na may kasamang mga bagong credits.
                                    $UpdateCreditsQuery = "";
                                    if ($TypeofLeave === 'Vacation Leave') {
                                        $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_vctionCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                    } elseif ($TypeofLeave === 'Sick Leave') {
                                        $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_sickCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                    } elseif ($TypeofLeave === 'Bereavement Leave') {
                                        $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_brvmntCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                    }
                                    mysqli_query($conn, $UpdateCreditsQuery);
                                } else {
                                    $PayMethod = 'LWOP';
                                }
                            
                                // Gamitin ang $leaveDates[$i] para sa date
                                    $leaveDate = $leaveDates[$i];
                                    $attendanceCheck = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `date` = '$leaveDate'");
                                    if(mysqli_num_rows($attendanceCheck) > 0){
                                        $attendanceUP = mysqli_query ($conn, "UPDATE attendances SET `status` = '$PayMethod', `date` = '$leaveDate',  `time_in` = '$time_in', `time_out` = '$time_out', `late` = '$late', `early_out` = '$undertime', `overtime` = '$overtime', `total_work` = '$total_work', `total_rest` = '$total_rest' WHERE `empid` = '$EmployeeId' AND `date` = '$leaveDate'");
                                        header("Location: ../../leaveReq.php?msg=Manual leave success.");
                                    }else{
                                    $attendanceQuery = mysqli_query($conn, "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) VALUES ('$PayMethod', '$EmployeeId', '$leaveDate', '$time_in', '$time_out', '$late', '$undertime', '$overtime', '$total_work', '$total_rest')"); 
                                    header("Location: ../../leaveReq.php?msg=Success");
                                    }
                                   }
                                }else if($LeavePeriod === 'Half Day'){
                                    // Gumawa ako ng variable na naglalaman ng parehong date
                                    $DateRange = $StartDate;

                                    if ($StartDate !== $EndDate) {
                                        // Kung ang Start Date at End Date ay magkaiba, idagdag ang End Date sa format
                                        $DateRange .= ' - ' . $EndDate;
                                    }

                                    $day_of_week = date('l', strtotime($DateRange));

                                    if($day_of_week === 'Monday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
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
                                    }//Monday
                                    else if($day_of_week === 'Tuesday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_tuesday_timein);
                                        $scheduled_timeout = new DateTime($col_tuesday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_tuesday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Tuesday
                                    else if($day_of_week === 'Wednesday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_wednesday_timein);
                                        $scheduled_timeout = new DateTime($col_wednesday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_wednesday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Wednesday
                
                                    else if($day_of_week === 'Thursday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_thursday_timein);
                                        $scheduled_timeout = new DateTime($col_thursday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_thursday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Thursday
                
                                    else if($day_of_week === 'Friday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_friday_timein);
                                        $scheduled_timeout = new DateTime($col_friday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_friday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Friday
                
                                    else if($day_of_week === 'Saturday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_saturday_timein);
                                        $scheduled_timeout = new DateTime($col_saturday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_saturday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Saturday
                                        
                                    else if($day_of_week === 'Sunday'){
                                        $time_in = $_POST['firsttime'];
                                        $time_out = $_POST['secondtime'];
                
                                        $time_in_datetime = new DateTime($time_in);
                                        $time_out_datetime = new DateTime($time_out);
                
                                        $scheduled_timein = new DateTime($col_sunday_timein);
                                        $scheduled_timeout = new DateTime($col_sunday_timeout);
                
                                        $lunchbreak_start_converted = new DateTime('12:00:00');
                                        $lunchbreak_end_converted = new DateTime('13:00:00');
                
                                        $grace_period_total = new DateTime($col_sunday_timein);
                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0;
                
                                        if ($grace_period_minutes > 0) {
                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                            $grace_period_total->add($grace_period_interval);
                                        }
                
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
                                    }//Sunday
                                        $total_rest = "00:00:00";
                
                                       
                                            //Kunin ang kasalukuyang credits mula sa leaveinfo_tb
                                            $GetCreditsQuery = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE `col_empID` = '$EmployeeId'");
                                            if ($GetCreditsQuery->num_rows > 0) {
                                                $rowCredits = $GetCreditsQuery->fetch_assoc();
                                                if ($TypeofLeave === 'Vacation Leave') {
                                                    $CurrentCredits = $rowCredits['col_vctionCrdt'];
                                                } elseif ($TypeofLeave === 'Sick Leave') {
                                                    $CurrentCredits = $rowCredits['col_sickCrdt'];
                                                } elseif ($TypeofLeave === 'Bereavement Leave') {
                                                    $CurrentCredits = $rowCredits['col_brvmntCrdt'];
                                                }
                                            } else {
                                                $CurrentCredits = 0; // Kung wala kang nakuhang credits, itakda ito sa 0.
                                            }
                
                                            // I-compute ang bagong halaga ng credits pagkatapos ng leave deduction.
                                            $NewCredits = max(0, $CurrentCredits - 0.5); // Bawas ng 1 kada araw
                                            // I-update ang leaveinfo_tb table para sa `col_empID` na may kasamang mga bagong credits.
                                            $UpdateCreditsQuery = "";
                                            if ($TypeofLeave === 'Vacation Leave') {
                                                $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_vctionCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                            } elseif ($TypeofLeave === 'Sick Leave') {
                                                $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_sickCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                            } elseif ($TypeofLeave === 'Bereavement Leave') {
                                                $UpdateCreditsQuery = "UPDATE leaveinfo_tb SET `col_brvmntCrdt` = $NewCredits WHERE `col_empID` = '$EmployeeId'";
                                            }
                                            mysqli_query($conn, $UpdateCreditsQuery);
                                    
                                        $attendanceCheck = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `date` BETWEEN '$StartDate' AND '$EndDate'");
                                        if(mysqli_num_rows($attendanceCheck) > 0){
                                            $attendanceUP = mysqli_query ($conn, "UPDATE attendances SET `status` = 'Present', `date` = '$StartDate',  `time_in` = '$time_in', `time_out` = '$time_out', `late` = '$late', `early_out` = '$undertime', `overtime` = '$overtime', `total_work` = '$total_work', `total_rest` = '$total_rest' WHERE `empid` = '$EmployeeId' AND BETWEEN '$StartDate' AND '$EndDate'");
                                            header("Location: ../../leaveReq.php?msg=Manual leave success.");
                                        }else{
                                        $attendanceQuery = mysqli_query($conn, "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) VALUES ('Present', '$EmployeeId', '$StartDate', '$time_in', '$time_out', '$late', '$undertime', '$overtime', '$total_work', '$total_rest')"); 
                                        header("Location: ../../leaveReq.php?msg=Success");
                                        }
                                }
                            } else {
                                $PayMethod = 'Unknown Leave Type';
                            }
                            
                        

                        



                


        } //emp schedule
    }//foreach empid
}



?>