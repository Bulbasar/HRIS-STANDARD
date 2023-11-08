<?php
include 'config.php';

$inputData = json_decode(file_get_contents("php://input"), true);
$cutoffId = $inputData['table_cutoff_id'];
$Payrule = $inputData['table_payrule_id'];
$employeeID = $inputData['table_employee_id'];
$table_frequency = $inputData['table_frequency_id'];
$monthcutoff = $inputData['table_cutoffmonth_id'];
$cutoffStart = $inputData['table_cutoffstart_id'];
$cutoffEnd = $inputData['table_cutoffend_id'];
$table_cutoffnum = $inputData['table_cutoffnumber_id'];
$empworkdays = $inputData['table_workingdays_id'];
$basictotalwork = $inputData['table_workinghours_id'];
$basicpay = $inputData['table_basictotalamount_id'];
$othours = $inputData['table_overtimehours_id'];
$otamount = $inputData['table_overtimepay_id'];
$transportation = $inputData['table_transport_id'];
$mealsallow = $inputData['table_meal_id'];
$Internetallow = $inputData['table_internet_id'];
$addallowance = $inputData['table_newallowance_id'];
$allowance = $inputData['table_totalallowance_id'];
$leaveNumber = $inputData['table_leavenumber_id'];
$paidleave = $inputData['table_leavepay_id'];
$paidHoliday = $inputData['table_holidaypay_id'];
$emptotalEarn = $inputData['table_totalEarn_id'];
$totalAbsent = $inputData['table_absentnumber_id'];
$absenceDeduction = $inputData['table_absentdeduct_id'];
$ssscut = $inputData['table_sssamount_id'];
$philcut = $inputData['table_philamount_id'];
$tincut = $inputData['table_tinamount_id'];
$pagibigcut = $inputData['table_pagibigamount_id'];
$othercut = $inputData['table_otherGovern_id'];
$totalGovernment = $inputData['table_totalGovernment_id'];
$numberLate = $inputData['table_totallate_id'];
$latecut = $inputData['table_latededuction_id'];
$undertimeHours = $inputData['table_undertimehours_id'];
$Utcut = $inputData['table_undertimededuction_id'];
$lwopNumber = $inputData['table_lwopcount_id'];
$lwopcut = $inputData['table_lwopdeduction_id'];
$totaldeduct = $inputData['table_totaldeduction_id'];
$Netpayslip = $inputData['table_Netpayslip_id'];

$ThirteenPay = $inputData['table_thirteenMonth_id'];


$checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$employeeID' AND `cutoff_startdate` = '$cutoffStart' AND `cutoff_enddate` = '$cutoffEnd'";
$slipRun = mysqli_query($conn, $checkSlip);

if(mysqli_num_rows($slipRun) > 0){
    $response[] = array("status" => "error", "message" => "There's already existing data");
}else{
    $insertQuery ="INSERT INTO payslip_report_tb(`cutoff_ID`, `pay_rule`, `empid`, `col_frequency`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `transpo_allow`, `meal_allow`, `net_allowance`, `add_allow`, `allowances`, `number_leave`, `holiday_pay`, `total_earnings`, `absence`, `absence_deduction`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `totalGovern_tb`, `total_late`, `tardiness_deduct`, `ut_time`, `undertime_deduct`, `number_lwop`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$cutoffId', '$Payrule', '$employeeID', '$table_frequency', '$monthcutoff', '$cutoffStart', '$cutoffEnd', '$table_cutoffnum', '$empworkdays', '$basictotalwork', '$basicpay', '$othours', '$otamount', '$transportation', '$mealsallow', '$Internetallow', '$addallowance', '$allowance', '$leaveNumber', '$paidHoliday', '$emptotalEarn', '$totalAbsent', '$absenceDeduction', '$ssscut', '$philcut', '$tincut', '$pagibigcut', '$othercut', '$totalGovernment', '$numberLate', '$latecut', '$undertimeHours', '$Utcut', '$lwopNumber', '$lwopcut', '$totaldeduct', '$Netpayslip')";

    $result = mysqli_query($conn, $insertQuery);
    
    if ($result) {
        // Success
        $response[] = array("status" => "success", "message" => "Data inserted for Employee ID");
    } else {
        // Error
        $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID");
    }
}

$checkthirteen = "SELECT * FROM thirteenmonth_salary_tb WHERE `empid` = '$employeeID' AND `start_date` = '$cutoffStart' AND `end_date` = '$cutoffEnd'";
$thirteenRun = mysqli_query($conn, $checkthirteen);

if(mysqli_num_rows($thirteenRun) > 0){
    $response[] = array("status" => "error", "message" => "There's already existing data");
}else{
    $inserThirteen = "INSERT INTO thirteenmonth_salary_tb(`empid`, `month_thirteen`, `start_date`, `end_date`, `total_salary`)
    VALUES('$employeeID', '$monthcutoff', '$cutoffStart', '$cutoffEnd', '$ThirteenPay')";
    $resInsert = mysqli_query($conn, $inserThirteen);

    if($resInsert){
        //success
        $response[] = array("status" => "success", "message" => "Data inserted for Employee ID");
    } else {
        //error
        $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID");
    }
}
echo json_encode($response);

?>