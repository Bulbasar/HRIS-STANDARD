<?php
$totalAbsentLWOP = 0;
//Total Absence
$AbsentDeduction = 0;
$AbsentCount = mysqli_query($conn, "SELECT COUNT(`status`) AS DailyAbsent
FROM
    attendances
WHERE
empid = '$EmployeeID' AND `status` = 'Absent'
AND `date` BETWEEN '$str_date' AND '$end_date'");
if($AbsentCount->num_rows > 0){
    $AbsentRow = $AbsentCount->fetch_assoc();
      $TotalAbsent = $AbsentRow['DailyAbsent'];
}else{
    $TotalAbsent = 0;
}
$AbsentDeduction = $DailyRates * $TotalAbsent; //Para sa modal na deduction

//Total LWOP
$LWOPDeduction = 0;
$LWOPCount = mysqli_query($conn, "SELECT COUNT(`status`) AS DailyLWOP
FROM
    attendances
WHERE
empid = '$EmployeeID' AND `status` = 'LWOP'
AND `date` BETWEEN '$str_date' AND '$end_date'");
if($LWOPCount->num_rows > 0){
    $LWOPRow = $LWOPCount->fetch_assoc();
      $TotalLWOP = $LWOPRow['DailyLWOP'];
}else{
    $TotalLWOP = 0;
}
$LWOPDeduction = $DailyRates * $TotalLWOP; //Para sa modal na deduction

$totalAbsentLWOP = $TotalAbsent + $TotalLWOP; //Total ng absent at lwop para magamit ko sa pagdeduct sa allowance


//Total Present at on leave para sa table
$attendanceDaily = mysqli_query($conn, "SELECT COUNT(`status`) AS Dailyworks
FROM
    attendances
WHERE
empid = '$EmployeeID' AND(`status` = 'Present' OR `status` = 'On-Leave') 
AND `date` BETWEEN '$str_date' AND '$end_date'");
if($attendanceDaily->num_rows > 0){
    $rowPresent = $attendanceDaily->fetch_assoc();
       $Totaldailyworks = $rowPresent['Dailyworks'];
}else{
     $Totaldailyworks = 0;
}


$TotalOnLeavePresent = 0;
$LeavewithPay = 0;
//Table for number of On-Leave only
$LeaveDaily = mysqli_query($conn, "SELECT COUNT(`status`) AS DailyOnLeave
FROM
    attendances
WHERE
empid = '$EmployeeID' AND `status` = 'On-Leave' 
AND `date` BETWEEN '$str_date' AND '$end_date'");

if($LeaveDaily->num_rows > 0){
    $rowOnLeave = $LeaveDaily->fetch_assoc();
       $TotalLeavePaid = $rowOnLeave['DailyOnLeave'];
}else{
     $TotalLeavePaid = 0;
}
$LeavewithPay = $DailyRates * $TotalLeavePaid;


//Total Present Only
$PresentDaily = mysqli_query($conn, "SELECT COUNT(`status`) AS DailyPresent
FROM
    attendances
WHERE
empid = '$EmployeeID' AND `status` = 'Present' 
AND `date` BETWEEN '$str_date' AND '$end_date'");
if($PresentDaily->num_rows > 0){
    $Presentrow = $PresentDaily->fetch_assoc();
       $TotalPresent = $Presentrow['DailyPresent'];
}else{
     $TotalPresent = 0;
}

$TotalOnLeavePresent = $TotalLeavePaid + $TotalPresent; //Pinagsamang On-Leave at Present sa attendance na nasa cutoff
?>