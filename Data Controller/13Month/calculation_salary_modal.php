<?php
$getatt = "SELECT MIN(`date`) AS min_date, MAX(`date`) AS max_date FROM attendances WHERE `empid` = '$EmployeeID' AND `date` BETWEEN '$str_date' AND '$end_date'";
$resultatt = mysqli_query($conn, $getatt);

if(mysqli_num_rows($resultatt) > 0){
    $rowatt = $resultatt->fetch_assoc();
    $min_date = $rowatt['min_date'];
    $max_date = $rowatt['max_date'];
}

$start_date = new DateTime($min_date);
$end_dates = new DateTime($max_date);
$end_dates->modify('+1 day');
$interval = new DateInterval('P1D');
$daterange = new DatePeriod($start_date, $interval, $end_dates);

$monthly = array();
$total_present_count = 0;
$total_absent_count = 0;
$total_monthly_salary = 0;
$total_absent_salary = 0;
$lateCount = 0;
$MONDAY_TO_DEDUCT_LATE = 0;
$Tue_TO_DEDUCT_LATE = 0;
$WED_TO_DEDUCT_LATE = 0;
$Thurs_TO_DEDUCT_LATE = 0;
$Fri_TO_DEDUCT_LATE = 0;
$SAT_TO_DEDUCT_LATE = 0;
$Sun_TO_DEDUCT_LATE = 0;
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
$overall_salary = 0;
foreach ($daterange as $date) {
    $month = $date->format('m');
    $monthly[$month][] = $date->format('Y-m-d');
}

$months_array = array();
foreach ($monthly as $month => $dates) {
    $month_name = date('F', mktime(0, 0, 0, $month, 10));

    $count_query = "SELECT COUNT(*) as present_count FROM attendances WHERE `empid` = '$EmployeeID' AND `date` IN ('" . implode("','", $dates) . "') AND (`status` = 'Present' OR `status` = 'On-Leave')";
    $result_count = mysqli_query($conn, $count_query);
    $count_row = $result_count->fetch_assoc();
    $present_count = $count_row['present_count'];

    $total_present_count += $present_count;

    $count_query_absent = "SELECT COUNT(*) as absent_count FROM attendances WHERE `empid` = '$EmployeeID' AND `date` IN ('" . implode("','", $dates) . "') AND (`status` = 'Absent' OR `status` = 'LWOP')";
    $result_count_absent = mysqli_query($conn, $count_query_absent);
    $count_row_absent = $result_count_absent->fetch_assoc();
    $absent_count = $count_row_absent['absent_count'];

    $total_absent_count += $absent_count;
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
    }
    //-------------------Path sa code ng deduction para sa late-------------------\\
    include 'Data Controller/13Month/Deduction_Late.php';
    //-------------------Path sa code ng deduction para sa late-------------------\\
    //-------------------Path sa code ng total sa working hours at late-------------------\\
    include 'Data Controller/13Month/Totalworkhours-late.php';
    //-------------------Path sa code ng total sa working hours at late-------------------\\ 

    //-------------------Path sa code ng deduction para sa UT-------------------\\
    include 'Data Controller/13Month/UT-Computation.php';
    //-------------------Path sa code ng deduction para sa UT-------------------\\
    

        if($EmpPayRule === 'Fixed Salary'){
            $total_present_salary = $EmpSalary;
            $total_absent_salary = $absent_count * $EmpDrate;
            $total_deductions = $total_absent_salary + $LateTotalDeduction + $UTtotaldeduction;

            $overall_salary = $total_present_salary - $total_deductions;
        } else if ($EmpPayRule === 'Daily Paid'){
            $total_present_salary = $present_count * $EmpDrate;
            $total_absent_salary = $absent_count * $EmpDrate;
            $total_deductions = $total_absent_salary + $LateTotalDeduction + $UTtotaldeduction;

            $overall_salary = $total_present_salary - $total_deductions;
        }
        $months_array[$month_name] = array(
            'dates' => $dates,
            'present_count' => $present_count,
            'absent_count' => $absent_count,
            'late_count' => $lateCount,
            'deductions' => array(
                'Monday' => $MONDAY_TO_DEDUCT_LATE,
                'Tuesday' => $Tue_TO_DEDUCT_LATE,
                'Wednesday' => $WED_TO_DEDUCT_LATE,
                'Thursday' => $Thurs_TO_DEDUCT_LATE,
                'Friday' => $Fri_TO_DEDUCT_LATE,
                'Saturday' => $SAT_TO_DEDUCT_LATE,
                'Sunday' => $Sun_TO_DEDUCT_LATE,
                'total_deductions' => $LateTotalDeduction
            ),
            'overall_salary' => $overall_salary
        );
    // echo "Month: " . $month_name . "<br>";
    // echo "Dates: " . implode(", ", $dates) . "<br>";
    // echo "Present Count: " . $present_count . "<br><br>";
    // echo "Absent Count: " . $absent_count . "<br><br>";
}

?>