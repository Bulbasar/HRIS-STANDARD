<?php
include '../../config.php';

require('fpdf/fpdf.php'); // Include the fpdf library

if (isset($_POST['cutoffID']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $cutoffID = $_POST['cutoffID'];
                            $startDate = $_POST['startDate'];
                            $endDate = $_POST['endDate'];

                            $Getprb = "SELECT payslip_report_tb.id,
                            payslip_report_tb.cutoff_ID,
                            payslip_report_tb.pay_rule,
                            payslip_report_tb.empid,
                            payslip_report_tb.col_frequency,
                            payslip_report_tb.cutoff_startdate, 
                            payslip_report_tb.cutoff_enddate, 
                            payslip_report_tb.working_days, 
                            payslip_report_tb.basic_hours, 
                            payslip_report_tb.basic_amount_pay, 
                            payslip_report_tb.overtime_hours, 
                            payslip_report_tb.overtime_amount, 
                            payslip_report_tb.transpo_allow, 
                            payslip_report_tb.meal_allow, 
                            payslip_report_tb.net_allowance, 
                            payslip_report_tb.add_allow, 
                            payslip_report_tb.allowances, 
                            payslip_report_tb.number_leave, 
                            payslip_report_tb.paid_leaves, 
                            payslip_report_tb.holiday_pay, 
                            payslip_report_tb.total_earnings, 
                            payslip_report_tb.absence, 
                            payslip_report_tb.absence_deduction, 
                            payslip_report_tb.sss_contri, 
                            payslip_report_tb.philhealth_contri, 
                            payslip_report_tb.tin_contri, 
                            payslip_report_tb.pagibig_contri, 
                            payslip_report_tb.other_contri, 
                            payslip_report_tb.totalGovern_tb,
                            payslip_report_tb.total_late, 
                            payslip_report_tb.tardiness_deduct, 
                            payslip_report_tb.ut_time, 
                            payslip_report_tb.undertime_deduct, 
                            payslip_report_tb.number_lwop, 
                            payslip_report_tb.lwop_deduct, 
                            payslip_report_tb.total_deduction, 
                            payslip_report_tb.net_pay,
                            payslip_report_tb.date_time,
                            employee_tb.empid,
                            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name FROM payslip_report_tb INNER JOIN
                            employee_tb ON employee_tb.empid = payslip_report_tb.empid WHERE cutoff_ID = '$cutoffID' AND cutoff_startdate = '$startDate' AND cutoff_enddate = '$endDate'";
                            $query_run = mysqli_query($conn, $Getprb);

                    while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <div class="report-container d-flex flex-row">
                                <div class="table-responsive" id="table-responsiveness" style="width: 500px;">
                                    <table class="table table-bordered">
                                        <thead style="background-color: #cecece">
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                        </thead>
                                            <tr>
                                                <td style="font-weight: 400"><?php echo $row['empid']?></td>
                                                <td style="font-weight: 400"><?php echo $row['full_name']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400">Salary Total</td>
                                                <td style="font-weight: 400"><?php echo $row['total_earnings']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400; color: red;">Salary Total Deduction</td>
                                                <td style="font-weight: 400; color: red;"><?php echo $row['total_deduction']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400">Benefit Sharing Deducted</td>
                                                <td style="font-weight: 400; color: red;"><?php echo $row['totalGovern_tb']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-style: bold; font-weight: 400">Salary Final Total: </td>
                                                <td style="font-weight: 400"><?php echo $row['net_pay']?></td>
                                            </tr>
                                    </table>
                                </div>

                        <div class="table-responsive" id="table-responsiveness">
                                <table class="table table-bordered" style="width: 200%; overflow-x: auto;">
                                <thead style="background-color: #cecece">
                                        <th>Total Days</th>
                                        <th style="display: none;">Total Hours</th>
                                        <th style="display: none;">Overtime Hours</th>
                                        <th>Overtime Pay</th>
                                        <th><?php echo $newTranspoLabel; ?></th>
                                        <th><?php echo $newMealLabel; ?></th>
                                        <th><?php echo $newInternetLabel; ?></th>
                                        <th>Other</th>
                                        <th style="display: none;">Allowances</th>
                                        <th style="display: none;">Number of Leave</th>
                                        <th>Leave Pay</th>
                                        <th>Holiday Pay</th>
                                        <th style="display: none;">Absent</th>
                                        <th>Absent Deduction</th>
                                        <th>Late</th>
                                        <th>Late Deduction</th>
                                        <th>Undertime</th>
                                        <th>Undertime Deduction</th>
                                        <th style="display: none;">LWOP</th>
                                        <th>LWOP Deduction</th>
                                        <th>SSS</th>
                                        <th>Philhealth</th>
                                        <th>TIN</th>
                                        <th>Pag-Ibig</th>
                                        <th>Other Government</th>
                                </thead>     
                                    <tbody>
                                        <tr>
                                        <td style="font-weight: 400;"> <?php echo $row['working_days']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['basic_hours']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['overtime_hours']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['overtime_amount']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['transpo_allow']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['meal_allow']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['net_allowance']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['add_allow']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['allowances']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['number_leave']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['paid_leaves']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['holiday_pay']?></td>
                                        <td style="font-weight: 400; color: red; display: none;"> <?php echo $row['absence']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['absence_deduction']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['total_late']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['tardiness_deduct']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['ut_time']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['undertime_deduct']?></td>
                                        <td style="font-weight: 400; color: red; display: none;"> <?php echo $row['number_lwop']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['lwop_deduct']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['sss_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['philhealth_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['tin_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['pagibig_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['other_contri']?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }

    // Create a PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add content to the PDF (modify this as per your data)
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Employee ID: ' . $row['empid'], 0, 1);
    $pdf->Cell(0, 10, 'Name: ' . $row['full_name'], 0, 1);
    // Add more data here

    // Output the PDF to the browser
    $pdf->Output();
    exit;
}
?>
