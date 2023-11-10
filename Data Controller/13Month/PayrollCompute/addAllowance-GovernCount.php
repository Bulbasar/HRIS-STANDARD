<?php
//total per allowance and total per government
$allowanceGovernEmp = mysqli_query($conn, "SELECT
employee_tb.emptranspo + employee_tb.empmeal + employee_tb.empinternet AS TotalStandardAllowance,
employee_tb.sss_amount + employee_tb.tin_amount + employee_tb.pagibig_amount + employee_tb.philhealth_amount AS TotalGovernStandard
    FROM
        employee_tb
    WHERE empid = '$EmployeeID'");

if($allowanceGovernEmp){
    $allowanceRow = $allowanceGovernEmp->fetch_assoc();
    $TotalAllowanceStandard = $allowanceRow['TotalStandardAllowance'];
    $TotalGovernStandard = $allowanceRow['TotalGovernStandard'];
} else {
    $TotalAllowanceStandard = 0;
    $TotalGovernStandard = 0;
}


//Add allowance and add government
$addAllowanceGovern = mysqli_query($conn, "SELECT
employee_tb.empid,
SUM(
    allowancededuct_tb.allowance_amount
) AS TotaladdAllowance,
SUM(
    governdeduct_tb.govern_amount
) AS TotaladdGovern
FROM employee_tb
INNER JOIN allowancededuct_tb ON employee_tb.empid = allowancededuct_tb.id_emp
INNER JOIN governdeduct_tb ON employee_tb.empid = governdeduct_tb.id_emp
WHERE empid = '$EmployeeID'");

if($addAllowanceGovern->num_rows > 0){
    $addAllowRow = $addAllowanceGovern->fetch_assoc();
    $TotaladdAllowance = $addAllowRow['TotaladdAllowance'];
    $TotaladdGovern = $addAllowRow['TotaladdGovern'];
} else {
    $TotaladdAllowance = 0;
    $TotaladdGovern = 0;
}
?>