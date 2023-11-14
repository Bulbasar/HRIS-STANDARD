<?php
// insert_data.php
include 'config.php';
$inputData = json_decode(file_get_contents("php://input"), true);

foreach ($inputData as $Employeeslip) {
    $CutoffId = $Employeeslip['cutoffId'];
    $PayRules = $Employeeslip['Payrules'];
    $Empid = $Employeeslip['EmployeeId'];
    $Frequent = $Employeeslip['Frequent'];
    $Cutoffmonth = $Employeeslip['Monthcutoff'];
    $CutoffstartDate = $Employeeslip['Startcutoff'];
    $CutoffendDate = $Employeeslip['Endcutoff'];
    $Cutoffnumber = $Employeeslip['Numbercutoff'];
    $WorkingDays = $Employeeslip['Workingdays'];
    $Workinghours = $Employeeslip['Workinghours'];
    $Basicpayslip = $Employeeslip['Basicpayslip'];
    $OvertimeHours = $Employeeslip['HoursOT'];
    $OvertimePay = $Employeeslip['PayOT'];
    $TransportAllowance = $Employeeslip['Transport'];
    $MealAllowance = $Employeeslip['Food'];
    $Internetallowance = $Employeeslip['Internet'];
    $newAllowance = $Employeeslip['newAllowance'];
    $TotalAllowances = $Employeeslip['totalAllowance'];
    $LeavewPay = $Employeeslip['leavePay'];
    $HolidayPays = $Employeeslip['holidayPaying'];
    $TotalEarnings = $Employeeslip['Totalearn'];
    $Absentnumber = $Employeeslip['Absentcount'];
    $Absentdeductions = $Employeeslip['Deductabsent'];
    $SSScontri = $Employeeslip['SSScontribute'];
    $Philhealthcontri = $Employeeslip['philcontribute'];
    $Tincontri = $Employeeslip['tincontribute'];
    $Pagibigcontri = $Employeeslip['pagibigContribute'];
    $newGovern = $Employeeslip['othercontribute'];
    $TotalGovernment = $Employeeslip['totalcontribute'];
    $Latecount = $Employeeslip['latetotal'];
    $Latedeductions = $Employeeslip['latedeductions'];
    $Undertimehours = $Employeeslip['Undertimehours'];
    $Undertimedeductions = $Employeeslip['UTdeductions'];
    $LWOPnumber = $Employeeslip['Lwopcount'];
    $LWOPdeductions = $Employeeslip['DeductionLWOP'];
    $DeductionTotal = $Employeeslip['totalDeduction'];
    $Netpayslip = $Employeeslip['Totalnetpay'];
    $ThirteenmonthsPay = $Employeeslip['ThirteenPay'];

    $checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$Empid' AND `cutoff_startdate` = '$CutoffstartDate' AND `cutoff_enddate` = '$CutoffendDate'";
    $slipRun = mysqli_query($conn, $checkSlip);

    if(mysqli_num_rows($slipRun) > 0){
        $response[] = array("status" => "error", "message" => "There's already existing data");
    }else{
        $insertQuery = "INSERT INTO payslip_report_tb(`cutoff_ID`, `pay_rule`, `empid`, `col_frequency`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `transpo_allow`, `meal_allow`, `net_allowance`, `add_allow`, `allowances`, `number_leave`, `holiday_pay`, `total_earnings`, `absence`, `absence_deduction`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `totalGovern_tb`, `total_late`, `tardiness_deduct`, `ut_time`, `undertime_deduct`, `number_lwop`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$CutoffId', '$PayRules', '$Empid', '$Frequent', '$Cutoffmonth', '$CutoffstartDate', '$CutoffendDate', '$Cutoffnumber', '$WorkingDays', '$Workinghours', '$Basicpayslip', '$OvertimeHours', '$OvertimePay', '$TransportAllowance', '$MealAllowance', '$Internetallowance', '$newAllowance', '$TotalAllowances', '$LeavewPay', '$HolidayPays', '$TotalEarnings', '$Absentnumber', '$Absentdeductions', '$SSScontri', '$Philhealthcontri', '$Tincontri', '$Pagibigcontri', '$newGovern', '$TotalGovernment', '$Latecount', '$Latedeductions', '$Undertimehours', '$Undertimedeductions', '$LWOPnumber', '$LWOPdeductions', '$DeductionTotal', '$Netpayslip')";

        $result = mysqli_query($conn, $insertQuery);
        
        if ($result) {
            // Success
            $response[] = array("status" => "success", "message" => "Data inserted for Employee ID");
        } else {
            // Error
            $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID");
        }
    }

    $checkThirteen = "SELECT * FROM thirteenmonth_salary_tb WHERE `empid` = '$Empid' AND `start_date` = '$CutoffstartDate' AND `end_date` = '$CutoffendDate'";
    $runThirteen = mysqli_query($conn, $checkThirteen);

    if(mysqli_num_rows($runThirteen) > 0){
        $response[] = array("status" => "error", "message" => "There's already existing data");
    } else {
        $inserthirteen = "INSERT INTO thirteenmonth_salary_tb (`empid`, `month_thirteen`, `start_date`, `end_date`, `total_salary`)
        VALUES ('$Empid', '$Cutoffmonth', '$CutoffstartDate', '$CutoffendDate', '$ThirteenmonthsPay')";
        $result_thirteen = mysqli_query($conn, $inserthirteen);

        if($result_thirteen) {
             // Success
             $response[] = array("status" => "success", "message" => "Data inserted for Employee ID");
        } else {
            // Error
            $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID");
        }
    }
}

echo json_encode($response);

?>
