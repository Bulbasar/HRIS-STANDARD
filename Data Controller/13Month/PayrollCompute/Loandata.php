<?php
$LoanRequest = "SELECT payroll_loan_tb.*, employee_tb.empid
FROM payroll_loan_tb
INNER JOIN employee_tb ON employee_tb.empid = payroll_loan_tb.empid
WHERE employee_tb.empid = '$EmployeeID'";

$result = $conn->query($LoanRequest);
if($result->num_rows > 0){
$loanRow = $result->fetch_assoc();
    $LoanType = $loanRow['loan_type'];
    $AmountPay = $loanRow['payable_amount'];
    $Amortization = $loanRow['amortization'];
    $Balance = $loanRow['col_BAL_amount'];
    $Applied = $loanRow['applied_cutoff'];
    $LoanStatus = $loanRow['loan_status'];
    $LoanDate = $loanRow['loan_date'];
}else{
    $LoanType = "";
    $AmountPay = "";
    $Amortization = "";
    $Balance = "";
    $Applied = "";
    $LoanStatus = "";
    $LoanDate = "";
}
?>