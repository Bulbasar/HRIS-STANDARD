<?php
            $OtQuery = "SELECT * FROM `overtime_tb` WHERE `empid` = '$EmployeeID' AND `status` = 'Approved' AND `work_schedule` BETWEEN '$str_date' AND '$end_date'";
            $Otresult = mysqli_query($conn, $OtQuery);

            if($Otresult->num_rows > 0){
                $OTArray = array(); // Array to store the OT

                while($row_OT = $Otresult->fetch_assoc()){
                    $OT_hours = $row_OT['total_ot'];

                    $OT_day = $row_OT['work_schedule'];

                    $OTArray[] = array('OT_hours' => $OT_hours, 'OT_day' => $OT_day);
                }
                $time_OT_TOTAL = 0;
                foreach ($OTArray as $OT_data) {
                    $Dates_OT = $OT_data['OT_day'];

                    $OtHolidayQuery = "SELECT * FROM holiday_tb WHERE date_holiday = '$Dates_OT'";
                    $OtHolidayResult = mysqli_query($conn, $OtHolidayQuery);

                    if($OtHolidayResult->num_rows <= 0){
                        $time_OT = DateTime::createFromFormat('H:i:s', $OT_data['OT_hours']);
                        //for hour OT
                        $time_OT_hour = $time_OT->format('H'); 
                        $time_OT_hour = intval($time_OT_hour);
                        $time_OT_hour_rate = $EmpOTrate * $time_OT_hour;

                        // for minute OT
                        $time_OT_mins = $time_OT->format('i');                                         
                        $time_OT_mins = intval($time_OT_mins);
                        $time_OT_minute_rate = $EmpOTrate / 60;
                        $time_OT_minute_rate = $time_OT_minute_rate * $time_OT_mins;

                        // added all the converted time from OT hours and mins
                        @$time_OT_TOTAL += $time_OT_hour_rate + $time_OT_minute_rate;
                        @$time_OT_TOTAL = number_format($time_OT_TOTAL, 2);
                    }
                }
                @$time_OT_TOTAL;//total of Overtime for cutoff
            } else {
                $time_OT_TOTAL = 0;
            }


            //Query sa pagcount ng overtime na naapproved
            $Othours = mysqli_query($conn, "SELECT
            IFNULL(
                CONCAT(
                    FLOOR(
                        SUM(TIME_TO_SEC(total_ot)) / 3600
                    ),
                    'H:',
                    FLOOR(
                        (
                            SUM(TIME_TO_SEC(total_ot)) % 3600
                        ) / 60
                    ),
                    'M'
                ),
                '0H:0M'
            ) AS TotalOvertimeHours
            FROM
                overtime_tb
            WHERE
                empid = '$EmployeeID' AND work_schedule BETWEEN '$str_date' AND '$end_date' AND `status` = 'Approved'");

            $rowOThours = $Othours->fetch_assoc();
            $OTtime = $rowOThours['TotalOvertimeHours'];

?>