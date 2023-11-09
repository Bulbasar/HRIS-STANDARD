<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php"); 
} else {
    // Check if the user's role is not "admin"
    if($_SESSION['role'] != 'admin'){
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    }
}
include 'config.php';

// NiRetrieve ko ang decode JSON data galing empListForm.php para mapalitan din ang label ng allowance
$data = json_decode(file_get_contents('php://input'), true);

// Update session variables with the new labels (if they are set)
if (isset($data['newTranspoLabel'])) {
    $_SESSION['newTranspoLabel'] = $data['newTranspoLabel'];
}
if (isset($data['newMealLabel'])) {
    $_SESSION['newMealLabel'] = $data['newMealLabel'];
}
if (isset($data['newInternetLabel'])) {
    $_SESSION['newInternetLabel'] = $data['newInternetLabel'];
}

// Define default labels or use session data
$newTranspoLabel = isset($_SESSION['newTranspoLabel']) ? $_SESSION['newTranspoLabel'] : '';
$newMealLabel = isset($_SESSION['newMealLabel']) ? $_SESSION['newMealLabel'] : '';
$newInternetLabel = isset($_SESSION['newInternetLabel']) ? $_SESSION['newInternetLabel'] : '';
//End ng ajax para label
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">

    <!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
<link rel="stylesheet" href="skydash/themify-icons.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
<link rel="stylesheet" href="skydash/vendor.bundle.base.css">

<link rel="stylesheet" href="skydash/style.css">

<script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>


<link rel="stylesheet" href="css/try.css">


    <link rel="stylesheet" href="css/gnrate_payroll.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtRecordsResponsives.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <title>Payroll</title>
</head>
<body>
<header>
    <?php
        include 'header.php';
    ?>
</header>

<style>
/* Style the tabs */
.tab {
  display: flex;
  flex-direction: row;
}

.first button,
.second button,
.third button {
  background-color: inherit;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
}

/* Apply background color to active tab button */
.first.active button,
.second.active button,
.third.active button {
  background-color: #ccc;
}

/* Change color on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create a container for the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border-top: none;
}

.pagination{
        margin-right: 74px !important;
        
    }
    .sorting_asc{
        color: black !important;
    }

    .pagination li a{
        color: #c37700;
    }

        .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a {
        z-index: 3;
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
</style>

 <!-- Modal -->
 <div class="modal fade" id="Payrollbootstrap" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Payroll</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>   
            <div class="modal-body">
            <!-- <h4 id="employeeName"></h4> -->
            <input type="hidden" id="checktable" name="EmployeeID">
            <div class="tab">
                <div class="first active">
                    <button class="tablinks" id="tabbutton" onclick="openTab(event, 'Table1')">Payslip Details</button>
                </div>

                <div class="second">
                    <button class="tablinks" onclick="openTab(event, 'Table2')">Deduction</button>
                </div>

                <div class="third">
                    <button class="tablinks" onclick="openTab(event, 'Table3')">Loan Details</button>
                </div>
            </div>

                    <div id="Table1" class="tabcontent" style="display: block;">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Pay Rule</th>
                                    <th id="salarytag">Salary Rate</th>
                                    <th>Actual Working Days</th>
                                    <th>Overtime</th>
                                    <th>Overtime Pay</th>
                                    <th>Holiday Pay</th>
                                    <th>Leave Pay</th>
                                    <th><?php echo $newTranspoLabel; ?></th>
                                    <th><?php echo $newMealLabel; ?></th> 
                                    <th><?php echo $newInternetLabel; ?></th>
                                    <th>Other Allowances</th>
                                    <th>Basic Total Pay</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td style="font-weight: 400;" id="rulesPay"></td>
                                    <td style="font-weight: 400;" id="SalaryRate"></td>
                                    <td style="font-weight: 400;" id="acDays"></td>
                                    <td style="font-weight: 400;" id="ot_shours"></td>
                                    <td style="font-weight: 400;" id="overtime"></td>
                                    <td style="font-weight: 400;" id="holiPay"></td>
                                    <td style="font-weight: 400;" id="leavePay"></td>
                                    <td style="font-weight: 400;" id="transport"></td>
                                    <td style="font-weight: 400;" id="meal"></td>
                                    <td style="font-weight: 400;" id="internet"></td>
                                    <td style="font-weight: 400;" id="other"></td>
                                    <td style="font-weight: 400;" id="addtotal"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="Table2" class="tabcontent">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Absent</th>
                                    <th>Absent Deduction</th>
                                    <th>Late</th>
                                    <th>Late Deduction</th>
                                    <th>Undertime</th> 
                                    <th>Undertime Deduction</th>
                                    <th>LWOP</th>
                                    <th>LWOP Deduction</th>                                
                                    <th>SSS</th> 
                                    <th>Philhealth</th>
                                    <th>Pagibig</th>
                                    <th>Tin</th>
                                    <th>Other</th>
                                    <th>Total Deduction</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td style="font-weight: 400; color: red;" id="absence"></td>
                                    <td style="font-weight: 400; color: red;" id="absencededucts"></td>
                                    <td style="font-weight: 400; color: red;" id="late"></td>
                                    <td style="font-weight: 400; color: red;" id="lateDeduct"></td>
                                    <td style="font-weight: 400; color: red;" id="undertime"></td>
                                    <td style="font-weight: 400; color: red;" id="utDeduct"></td>
                                    <td style="font-weight: 400; color: red;" id="lwopnumber"></td>
                                    <td style="font-weight: 400; color: red;" id="lwopkaltas"></td>
                                    <td style="font-weight: 400; color: red;" id="sss"></td>
                                    <td style="font-weight: 400; color: red;" id="philhealth"></td>
                                    <td style="font-weight: 400; color: red;" id="pagibig"></td>
                                    <td style="font-weight: 400; color: red;" id="tin"></td>
                                    <td style="font-weight: 400; color: red;" id="otherContributions"></td>
                                    <td style="font-weight: 400; color: red;" id="total_Deductions"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="Table3" class="tabcontent">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Payable Amount</th>
                                    <th>Amortization</th>
                                    <th>Balance Amount</th>
                                    <th>Applied Cut Off</th>
                                    <th>Loan Status</th>
                                    <th>Loan Date</th>
                                    <th>Time Stamp</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td id="loantype"></td>
                                    <td id="payable"></td>
                                    <td id="amortization"></td>
                                    <td id="balance"></td>
                                    <td id="applied"></td>
                                    <td id="loanstatus"></td>
                                    <td id="loandate"></td>
                                    <td id="timestamp"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
            </div> <!---modal body--->
        </div>
    </div>
</div> <!---Modal End--->


<!------------------------------------------------- Header ------------------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <p style="font-size: 25px; padding: 10px">Generate Payroll</p>
                    </div>
                    <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printAllButton">
                            View Payslip
                        </button>
                    </div>
                </div>
<!------------------------------------------------- End Of Header -------------------------------------------> 


<!---------------------------------------- Modal for view multiple payslip ------------------------------------->
<div class="modal fade" id="printAllButton" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="content_modal">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Payslip</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="generate_all_payslip.php" method="post">
      <div class="modal-body">
       <h4>Employee's Payslip</h4>
       <input type="hidden" name="name_btnview" value="<?php echo isset($_POST['name_btnview']) ? $_POST['name_btnview'] : ''; ?>">
      </div>
      <div class="modal-footer">
        <button type="submit" name="printAll" class="btn btn-primary" id="printAllButton">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!---------------------------------------- Modal for view multiple payslip ------------------------------------->


                            <div class="table-responsive mt-4" id="table-responsiveness">
                               <table id="order-listing" class="table">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Working Days</th>
                                            <th>Month</th>
                                            <th>Year</th> 
                                            <th>Cut Off Start</th>
                                            <th>Cut Off End</th>
                                            <th>Cut Off Number</th>
                                            <th style="display: none;">Pay Rule</th>
                                            <th style="display: none;">Salary Rate</th>
                                            <th style="display: none;">Actual Working Days</th>
                                            <th style="display: none;">Overtime</th>
                                            <th style="display: none;">Overtime Pay</th>
                                            <th style="display: none;">Holiday Pay</th>
                                            <th style="display: none;">Leave Pay</th>
                                            <th style="display: none;">Transportation</th>
                                            <th style="display: none;">Meal</th> 
                                            <th style="display: none;">Internet</th>
                                            <th style="display: none;">Other Allowances</th>
                                            <th style="display: none;">Basic Total Pay</th>
                                            <th style="display: none;">Absent</th>
                                            <th style="display: none;">Absent Deduction</th>
                                            <th style="display: none;">Late</th>
                                            <th style="display: none;">Late Deduction</th>
                                            <th style="display: none;">Undertime</th> 
                                            <th style="display: none;">Undertime Deduction</th>
                                            <th style="display: none;">LWOP</th>
                                            <th style="display: none;">LWOP Deduction</th>                                
                                            <th style="display: none;">SSS</th> 
                                            <th style="display: none;">Philhealth</th>
                                            <th style="display: none;">Pagibig</th>
                                            <th style="display: none;">Tin</th>
                                            <th style="display: none;">Other</th>
                                            <th style="display: none;">Total Deduction</th>
                                            <th style="display: none;">Loan Type</th>
                                            <th style="display: none;">Payable Amount</th>
                                            <th style="display: none;">Amortization</th>
                                            <th style="display: none;">Balance Amount</th>
                                            <th style="display: none;">Applied Cut Off</th>
                                            <th style="display: none;">Loan Status</th>
                                            <th style="display: none;">Loan Date</th>
                                            <th style="display: none;">CutOff ID</th>
                                            <th style="display: none;">Employee Status</th>
                                            <th style="display: none;">Basic Payslip</th>
                                            <th style="display: none;">Frequency</th>
                                            <th style="display: none;">Total Allowances</th>
                                            <th style="display: none;">Total Government</th>
                                            <th style="display: none;">Net Pay</th>
                                            <th style="display: none;">Total Working hours</th>
                                            <th style="display: none;">Total Leave</th>
                                            <th style="display: none;">13 Month Pay</th>
                                            <th>View Details</th>
                                            <th>Print</th>
                                        </tr>
                                    </thead>
                                            <?php 
                                                include 'config.php';
                                                if(isset($_POST['name_btnview'])){
                                                    $cutOffID = $_POST['name_btnview'];
                                                    
                                                    $Getcutoff = "SELECT * FROM cutoff_tb WHERE `col_ID` = '$cutOffID'";
                                                    $Getrun = mysqli_query($conn, $Getcutoff);
                                                    $Cutoffrow = mysqli_fetch_assoc($Getrun);
                                                    $cutoffType = $Cutoffrow['col_type'];
                                                    $cutoffMonth = $Cutoffrow['col_month'];
                                                    $cutoffYear = $Cutoffrow['col_year'];
                                                    $cutoffNumber = $Cutoffrow['col_cutOffNum'];
                                                    $str_date = $Cutoffrow['col_startDate'];
                                                    $end_date = $Cutoffrow['col_endDate'];
                                                    $Frequency = $Cutoffrow['col_frequency'];

                                                    $CheckEmpid = "SELECT * FROM empcutoff_tb WHERE `cutOff_ID` = '$cutOffID'";
                                                    $runEmpid = mysqli_query($conn, $CheckEmpid);
                                                    while($row = mysqli_fetch_assoc($runEmpid)){
                                                        $EmployeeID = $row['emp_ID'];
                                                        
                                                        $payruleQuery = "SELECT 
                                                        payrule_tb.id,
                                                        payrule_tb.rule_name,
                                                        employee_tb.empid,
                                                        employee_tb.payrules
                                                        FROM employee_tb INNER JOIN payrule_tb ON employee_tb.payrules = payrule_tb.rule_name WHERE empid = '$EmployeeID'";
                                                        $payruleResult = mysqli_query($conn, $payruleQuery);
                                                        if($payruleResult){
                                                            $payrow = $payruleResult->fetch_assoc();
                                                            $payrules = $payrow['payrules']; 
                                                        }else{
                                                            echo "No Employee Found";
                                                        }


                                                        $empschedQuery = mysqli_query($conn, "SELECT * FROM empschedule_tb WHERE `empid` = '$EmployeeID'");
                                                        if($empschedQuery->num_rows > 0){
                                                            $empschedRow = $empschedQuery->fetch_assoc();
                                                            $schedulename = $empschedRow['schedule_name'];

                                                            $schedQuery = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedulename'");
                                                            if($schedQuery->num_rows > 0){
                                                                $row_Sched = $schedQuery->fetch_assoc();
                                                            }else{
                                                                echo "No results found schedule.";
                                                            }
                                                        }else{
                                                            echo "No results found.";
                                                        }

                                                        // -----------------------SCHED MONDAY START----------------------------//
                                                        if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){                         
                                                            $MON_timeIN = '00:00:00';
                                                            $MON_timeOUT = '00:00:00'; 

                                                            // convert ang time na nakastring sa DateTime
                                                            $MondaytimeIN = new DateTime($MON_timeIN);
                                                            $MondaytimeOUT = new DateTime($MON_timeOUT);
                                                            
                                                            $timeDifference = $MondaytimeOUT->diff($MondaytimeIN);
                                                            $MOn_total_work = $timeDifference->format('%H:%I:%S');

                                                            // // naconvert ko ang value ng $totalSeconds from time to integer
                                                            // $totalSeconds = $timeDifference->s + ($timeDifference->i * 60) + ($timeDifference->h * 3600);
                                                            // $MOn_total_work = (int)$totalSeconds;
                                                            
                                                        }else{
                                                            $MON_timeIN = $row_Sched['mon_timein'];
                                                            $MON_timeOUT = $row_Sched['mon_timeout'];
                
                                                            // Create a DateTime object from the string
                                                            $mon_timeIN_object = DateTime::createFromFormat('H:i', $MON_timeIN);
                                                            $mon_timeIN_formatted = $mon_timeIN_object->format('H:i'); 
                                                            list($mon_hours, $mon_minutes) = explode(':', $mon_timeIN_formatted);
                
                                                            // Convert hours and minutes to total minutes
                                                            $mon_total_minutes_timein = $mon_hours + $mon_minutes;
                
                                                            $mon_timeout_object = DateTime::createFromFormat('H:i', $MON_timeOUT);
                                                            $mon_timeout_formatted = $mon_timeout_object->format('H:i'); 
                                                            list($mon_hourss, $mon_minutess) = explode(':', $mon_timeout_formatted);
                
                                                            // Convert hours and minutes to total minutes
                                                            $mon_total_minutes_timeout = $mon_hourss + $mon_minutess;
                
                                                            $mon_total_minutes_timein = intval($mon_total_minutes_timein);
                                                            $mon_total_minutes_timeout = intval($mon_total_minutes_timeout);
                                                               
                                                            if($mon_total_minutes_timeout > $mon_total_minutes_timein){
                                                                $MOn_total_work = ($mon_total_minutes_timeout - $mon_total_minutes_timein) - 1;
                                                            }else{
                                                                $MOn_total_work = ($mon_total_minutes_timein - $mon_total_minutes_timeout) - 1;
                                                            }
                                                        }

                                                        // -----------------------SCHED TUESDAY START----------------------------//
                                                        if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                            $tue_timeIN = '00:00:00';
                                                            $tue_timeout = '00:00:00';

                                                            $TuesdaytimeIN = new DateTime($tue_timeIN);
                                                            $TuesdaytimeOUT = new DateTime($tue_timeout);
                                                            
                                                            $timeDifference = $TuesdaytimeOUT->diff($TuesdaytimeIN);
                                                            $Tue_total_work = $timeDifference->format('%H:%I:%S');
                                                        }else{
                                                            $tue_timeIN = $row_Sched['tues_timein'];
                                                            $tue_timeout = $row_Sched['tues_timeout'];
                                                            
                                                            $tue_timeIN_object = DateTime::createFromFormat('H:i', $tue_timeIN);
                                                            $tue_timeIN_formatted = $tue_timeIN_object->format('H:i'); 
                                                            list($tue_hours, $tue_minutes) = explode(':', $tue_timeIN_formatted);
            
                                                            // Convert hours and minutes to total minutes
                                                            $tue_total_minutes_timein = $tue_hours + $tue_minutes;
            
                                                            $tue_timeout_object = DateTime::createFromFormat('H:i', $tue_timeout);
                                                            $tue_timeout_formatted = $tue_timeout_object->format('H:i'); 
                                                            list($tue_hourss, $tue_minutess) = explode(':', $tue_timeout_formatted);
            
                                                            // Convert hours and minutes to total minutes
                                                            $tue_total_minutes_timeout = $tue_hourss + $tue_minutess;
            
                                                            $tue_total_minutes_timein = intval($tue_total_minutes_timein);
                                                            $tue_total_minutes_timeout = intval($tue_total_minutes_timeout);
            
                                                            if($tue_total_minutes_timeout > $tue_total_minutes_timein){
                                                                $Tue_total_work = ($tue_total_minutes_timeout - $tue_total_minutes_timein) - 1;
                                                            }else{
                                                                $Tue_total_work = ($tue_total_minutes_timein - $tue_total_minutes_timeout) - 1;
                                                            }
                                                        }
                                                        
                                                        // -----------------------SCHED WEDNESDAY START----------------------------//
                                                        if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                                                            $wed_timeIN = '00:00:00';
                                                            $wed_timeout = '00:00:00';

                                                            $WednesdaytimeIN = new DateTime($wed_timeIN);
                                                            $WednesdaytimeOUT = new DateTime($wed_timeout);

                                                            $timeDifference = $WednesdaytimeOUT->diff($WednesdaytimeIN);
                                                            $wed_total_work = $timeDifference->format('%H:%I:%S');
                                                        }else{
                                                            $wed_timeIN = $row_Sched['wed_timein'];
                                                            $wed_timeout = $row_Sched['wed_timeout'];
                
                                                            $weds_timeIN_object = DateTime::createFromFormat('H:i', $wed_timeIN);
                                                            $weds_timeIN_formatted = $weds_timeIN_object->format('H:i'); 
                                                            list($weds_hours, $weds_minutes) = explode(':', $weds_timeIN_formatted);
                
                                                            // Convert hours and minutes to total minutes
                                                            $weds_total_minutes_timein = $weds_hours + $weds_minutes;
                
                                                            $weds_timeout_object = DateTime::createFromFormat('H:i', $wed_timeout);
                                                            $weds_timeout_formatted = $weds_timeout_object->format('H:i'); 
                                                            list($weds_hourss, $weds_minutess) = explode(':', $weds_timeout_formatted);
                
                                                            // Convert hours and minutes to total minutes
                                                            $weds_total_minutes_timeout = $weds_hourss + $weds_minutess;
                
                                                            $weds_total_minutes_timein = intval($weds_total_minutes_timein);
                                                            $weds_total_minutes_timeout = intval($weds_total_minutes_timeout);
                
                                                            $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;
                
                                                            if($weds_total_minutes_timeout > $weds_total_minutes_timein){
                                                                $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;
                                                            }else{
                                                                $wed_total_work = ($weds_total_minutes_timein - $weds_total_minutes_timeout) - 1;
                                                            }
                                                        }

                                                        // -----------------------SCHED THURSDAY START----------------------------//
                                                        if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                                       
                                                            $thurs_timeIN = '00:00:00';
                                                            $thurs_timeout = '00:00:00';

                                                            $ThursdaytimeIN = new DateTime($thurs_timeIN);
                                                            $ThursdaytimeOUT = new DateTime($thurs_timeout);

                                                            $timeDifference = $ThursdaytimeOUT->diff($ThursdaytimeIN);
                                                            
                                                            $thurs_total_work = $timeDifference->format('%H:%I:%S');                                     
                                                        }else{
                                                            $thurs_timeIN = $row_Sched['thurs_timein'];
                                                            $thurs_timeout = $row_Sched['thurs_timeout'];
                                                            
                                                            // Create a DateTime object from the string
                                                            $thurs_timeIN_object = DateTime::createFromFormat('H:i', $thurs_timeIN);
                                                            $thurs_timeIN_formatted = $thurs_timeIN_object->format('H:i'); 
                                                            list($thurs_hours, $thurs_minutes) = explode(':', $thurs_timeIN_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $thurs_total_minutes_timein = $thurs_hours + $thurs_minutes;


                                                            $thurs_timeout_object = DateTime::createFromFormat('H:i', $thurs_timeout);
                                                            $thurs_timeout_formatted = $thurs_timeout_object->format('H:i'); 
                                                            list($thurs_hourss, $thurs_minutess) = explode(':', $thurs_timeout_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $thurs_total_minutes_timeout = $thurs_hourss + $thurs_minutess;

                                                            $thurs_total_minutes_timein = intval($thurs_total_minutes_timein);
                                                            $thurs_total_minutes_timeout = intval($thurs_total_minutes_timeout);

                                                            if($thurs_total_minutes_timeout > $thurs_total_minutes_timein){
                                                                $thurs_total_work = ($thurs_total_minutes_timeout - $thurs_total_minutes_timein) - 1;
                                                            }else{
                                                                $thurs_total_work = ($thurs_total_minutes_timein - $thurs_total_minutes_timeout) - 1;
                                                            }      
                                                        }

                                                        // -----------------------SCHED FRIDAY START----------------------------//                                                        
                                                        if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){                                                                      
                                                            $fri_timeIN = '00:00:00';
                                                            $fri_timeout = '00:00:00';

                                                            $FridaytimeIN = new DateTime($fri_timeIN);
                                                            $FridaytimeOUT = new DateTime($fri_timeout);

                                                            $timeDifference = $FridaytimeOUT->diff($FridaytimeIN);
                                                            $fri_total_work = $timeDifference->format('%H:%I:%S');                                      
                                                        }else{
                                                            $fri_timeIN = $row_Sched['fri_timein'];
                                                            $fri_timeout = $row_Sched['fri_timeout'];

                                                            // Create a DateTime object from the string
                                                            $fri_timeIN_object = DateTime::createFromFormat('H:i', $fri_timeIN);
                                                            $fri_timeIN_formatted = $fri_timeIN_object->format('H:i'); 
                                                            list($fri_hours, $fri_minutes) = explode(':', $fri_timeIN_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $fri_total_minutes_timein = $fri_hours + $fri_minutes;


                                                            $fri_timeout_object = DateTime::createFromFormat('H:i', $fri_timeout);
                                                            $fri_timeout_formatted = $fri_timeout_object->format('H:i'); 
                                                            list($fri_hourss, $fri_minutess) = explode(':', $fri_timeout_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $fri_total_minutes_timeout = $fri_hourss + $fri_minutess;

                                                            $fri_total_minutes_timein = intval($fri_total_minutes_timein);
                                                            $fri_total_minutes_timeout = intval($fri_total_minutes_timeout);

                                                                if($fri_total_minutes_timeout > $fri_total_minutes_timein){
                                                                $fri_total_work = ($fri_total_minutes_timeout - $fri_total_minutes_timein) - 1;
                                                            }else{
                                                                $fri_total_work = ($fri_total_minutes_timein - $fri_total_minutes_timeout) - 1;
                                                            }               
                                                        }

                                                        // -----------------------SCHED Saturday START----------------------------//
                                                        if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){                                                                                                          
                                                            $sat_timeIN = '00:00:00';
                                                            $sat_timeout = '00:00:00';

                                                            $SaturdaytimeIN = new DateTime($sat_timeIN);
                                                            $SaturdaytimeOUT = new DateTime($sat_timeout);

                                                            $timeDifference = $SaturdaytimeOUT->diff($SaturdaytimeIN);
                                                            $sat_total_work = $timeDifference->format('%H:%I:%S');
                                                        }else{                                          
                                                            $sat_timeIN = $row_Sched['sat_timein'];
                                                            $sat_timeout = $row_Sched['sat_timeout'];

                                                            // Create a DateTime object from the string
                                                            $sat_timeIN_object = DateTime::createFromFormat('H:i', $sat_timeIN);
                                                            $sat_timeIN_formatted = $sat_timeIN_object->format('H:i'); 
                                                            list($sat_hours, $sat_minutes) = explode(':', $sat_timeIN_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $sat_total_minutes_timein = $sat_hours + $sat_minutes;


                                                            $sat_timeout_object = DateTime::createFromFormat('H:i', $sat_timeout);
                                                            $sat_timeout_formatted = $sat_timeout_object->format('H:i'); 
                                                            list($sat_hourss, $sat_minutess) = explode(':', $sat_timeout_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $sat_total_minutes_timeout = $sat_hourss + $sat_minutess;

                                                            $sat_total_minutes_timein = intval($sat_total_minutes_timein);
                                                            $sat_total_minutes_timeout = intval($sat_total_minutes_timeout);
                                                            
                                                            if($sat_total_minutes_timeout > $sat_total_minutes_timein){
                                                                $sat_total_work = ($sat_total_minutes_timeout - $sat_total_minutes_timein) - 1;
                                                            }else{
                                                                $sat_total_work = ($sat_total_minutes_timein - $sat_total_minutes_timeout) - 1;
                                                            }        
                                                        }

                                                         // -----------------------SCHED SUNDAY START----------------------------//
                                                        if ($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == '') {
                                                            $sun_timeIN = '00:00:00';
                                                            $sun_timeout = '00:00:00';

                                                            $SundaytimeIN = new DateTime($sun_timeIN);
                                                            $SundaytimeOUT = new DateTime($sun_timeout);

                                                            $timeDifference = $SundaytimeOUT->diff($SundaytimeIN);
                                                            $sun_total_work = $timeDifference->format('%H:%I:%S');
                                                        }else{
                                                            $sun_timeIN = $row_Sched['sun_timein'];
                                                            $sun_timeout = $row_Sched['sun_timeout'];

                                                            // Create a DateTime object from the string
                                                            $sun_timeIN_object = DateTime::createFromFormat('H:i', $sun_timeIN);
                                                            $sun_timeIN_formatted = $sun_timeIN_object->format('H:i'); 
                                                            list($sun_hours, $sun_minutes) = explode(':', $sun_timeIN_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $sun_total_minutes_timein = $sun_hours + $sun_minutes;

                                                            $sun_timeout_object = DateTime::createFromFormat('H:i', $sun_timeout);
                                                            $sun_timeout_formatted = $sun_timeout_object->format('H:i'); 
                                                            list($sun_hourss, $sun_minutess) = explode(':', $sun_timeout_formatted);

                                                            // Convert hours and minutes to total minutes
                                                            $sun_total_minutes_timeout = $sun_hourss + $sun_minutess;

                                                            $sun_total_minutes_timein = intval($sun_total_minutes_timein);
                                                            $sun_total_minutes_timeout = intval($sun_total_minutes_timeout);


                                                            if($sun_total_minutes_timeout > $sun_total_minutes_timein){
                                                                $sun_total_work = ($sun_total_minutes_timeout - $sun_total_minutes_timein) - 1;
                                                            }else{
                                                                $sun_total_work = ($sun_total_minutes_timein - $sun_total_minutes_timeout) - 1;
                                                            }                                                                  
                                                        }

                                                        //-------------------Path sa code ng deduction para sa late-------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/Late-Deduction.php';
                                                        //-------------------Path sa code ng deduction para sa late-------------------\\

                                                        //--------------------Syntax sa pagcompute ng overtime kung may naapproved na ot request-----------------------//
                                                        include 'Data Controller/Payroll/PayrollCompute/OvertimeCompute.php';
                                                        //--------------------End Syntax sa pagcompute ng overtime kung may naapproved na ot request-----------------------//
                                                        
                                                        //-------------Computation sa pagdeduct ng undertime---------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/UndertimeCompute.php';
                                                        //-------------------------End sa calculation para magdeduct sa undertime-----------------------------------\\

                                                        //-------------------------Total late, total undertime and total work sa attendance--------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/Totalwork_late_ut.php';
                                                        //-------------------------Total late, total undertime and total work sa attendance--------------------------\\
                                                        
                                                        $DeductionAllowance = 0;
                                                        $totalAbsentLWOP = 0;
                                                        $TransportDeduction = 0;
                                                        $MealDeduction = 0;
                                                        $InternetDeduction = 0;
                                                        $TotaladdAllowanceDeduction = 0;

                                                        //-------------------------Number of Absent, LWOP and Present----------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/Absent-LWOP-Present.php';
                                                        //-------------------------Number of Absent, LWOP and Present----------------------------\\


                                                        $allowanceCount = 0; //Value kapag walang allowance
                                                        $DailyrateAllowance = 0;

                                                        //-------------------Addallowance and Government total---------------------------------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/addAllowance-GovernCount.php';
                                                        //-------------------Addallowance and Government total---------------------------------------------------\\

                                                        $allowanceCount = $TotalAllowanceStandard + $TotaladdAllowance; //Para magamit ko sa pagkuha ng daily paid allowance
                                                            
                                                            
                                                        //-------Formula para makuha yung daily rate ng total allowance---------\\
                                                        $startDate = new DateTime($str_date);
                                                        $endDate = new DateTime($end_date);

                                                        $dates = array();

                                                        $currentDate = clone $startDate;

                                                        while ($currentDate <= $endDate) {
                                                            $dates[] = $currentDate->format('Y-m-d');
                                                            $currentDate->modify('+1 day');
                                                        
                                                        }
                                                        $Trans = 0;
                                                        $Food = 0;
                                                        $Nets = 0;
                                                        $newaddAllowance = 0;
                                                        $sum = 0;
                                                        foreach($dates as $totalWorkingdays){
                                                            $day_of_week_allowance = date('l', strtotime($totalWorkingdays));

                                                            if($day_of_week_allowance === 'Monday'){
                                                                if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){
                                                                        @$sum += 0;
                                                                }else{
                                                                        @$sum += 1;
                                                                }
                                                                }

                                                                if($day_of_week_allowance === 'Tuesday'){
                                                                    if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                                        @$sum += 0; 
                                                                    }else{
                                                                        @$sum += 1; 
                                                                    }
                                                                }   

                                                                if($day_of_week_allowance === 'Wednesday'){
                                                                    if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                                                                        @$sum += 0; 
                                                                    }else{
                                                                        @$sum += 1; 
                                                                    }
                                                                }                                                 

                                                                if($day_of_week_allowance === 'Thursday'){
                                                                    if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                                                                        @$sum += 0;
                                                                    }else{
                                                                        @$sum += 1; 
                                                                    }
                                                                }

                                                                if($day_of_week_allowance === 'Friday'){
                                                                    if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){
                                                                            @$sum += 0;
                                                                    }else{
                                                                            @$sum += 1;
                                                                    }
                                                                }                                         
                                                                
                                                                if($day_of_week_allowance === 'Saturday'){
                                                                    if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                                                                        @$sum += 0; 
                                                                    }else{
                                                                        @$sum += 1; 
                                                                    }
                                                                }

                                                                if($day_of_week_allowance === 'Sunday'){
                                                                    if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                                                                        @$sum += 0; 
                                                                    }else{
                                                                        @$sum += 1;
                                                                    }
                                                                }
                                                        }
                                                        
                                                        $DailyrateAllowance = $allowanceCount / $sum;//Divide yung kabuuang allowance sa cutoff
                                                        $DeductionAllowance = $totalAbsentLWOP * $DailyrateAllowance; //Eto pangdeduct sa allowance kapag may absent o lwop sa cut off
                                                        
                                                        $Trans = $Transport / $sum; //Daily rate ng transpo
                                                        $Food = $Meal / $sum; //Daily rate ng meal
                                                        $Nets = $Internet / $sum; //Daily rate ng internet
                                                        $newaddAllowance = $TotaladdAllowance / $sum;

                                                        $TransportDeduction = $Trans * $totalAbsentLWOP; //Deduction kapag may absent sa transpo
                                                        $MealDeduction = $Food * $totalAbsentLWOP; //Deduction kapag may absent
                                                        $InternetDeduction = $Nets * $totalAbsentLWOP; //Deduction kapag may absent
                                                        $TotaladdAllowanceDeduction = $newaddAllowance * $totalAbsentLWOP; //Deduction kapag may absent
                                                        //-------End Formula para makuha yung daily rate ng total allowance---------\\

                                                        //--------------------------------Fixed and Daily allowances---------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/Fixed-Daily-Allowance.php';
                                                        //--------------------------------Fixed and Daily allowances---------------------------\\

                                                        //----------------------------------Salary without Deduction para sa modal----------------------------------\\
                                                        if($EmpPayRule === 'Fixed Salary'){
                                                            if($Frequency === 'Monthly'){
                                                                $Salarycutoff = $EmpSalary;
                                                                $PayslipSalary = $EmpSalary; //Para sa payslip

                                                                $ThirteenSalary = $EmpSalary; //Para sa thirteen month
                                                            }else if($Frequency === 'Semi-Month'){
                                                                $Salarycutoff = $EmpSalary / 2;
                                                                $PayslipSalary = $EmpSalary / 2; //Para sa payslip

                                                                $ThirteenSalary = $EmpSalary / 2; //Para sa thirteen month
                                                            }else if($Frequency === 'Weekly'){
                                                                $Salarycutoff = $EmpSalary / 4;
                                                                $PayslipSalary = $EmpSalary / 4; //Para sa payslip

                                                                $ThirteenSalary = $EmpSalary / 4; //Para sa thirteen month
                                                            }

                                                            $BasicTotalPay = $Salarycutoff + $time_OT_TOTAL + $LeavewithPay + $allowances; //ito yung total para sa modal na wala pang deduction
                                                        } else if($EmpPayRule === 'Daily Paid'){
                                                            $Salarycutoff = $EmpDrate;
                                                            $PayslipSalary = $EmpDrate * $Totaldailyworks;//Basic pay para sa payslip modal

                                                            $ThirteenSalary = $EmpDrate * $Totaldailyworks;

                                                            $DailyrateTotalworks = $Salarycutoff * $Totaldailyworks;
                                                            $BasicTotalPay = $DailyrateTotalworks + $time_OT_TOTAL + $LeavewithPay + $allowances; //ito yung total para sa modal na wala pang deduction
                                                        }
                                                        //----------------------------------Salary without Deduction para sa modal----------------------------------\\
                                                        
                                                        //----------------------------------Total ng Deduction para sa modal----------------------------------\\
                                                        $TotalDeduction = $AbsentDeduction + $LateTotalDeduction + $UTtotaldeduction + $LWOPDeduction + $Governmentformat;
                                                        //----------------------------------End ng total Deduction para sa modal----------------------------------\\

                                                        //----------------------------------Deduction para sa thirteen Month--------------------------------------\\
                                                        $DeductionThirteen = $AbsentDeduction + $LateTotalDeduction + $UTtotaldeduction + $LWOPDeduction;
                                                        //----------------------------------End Deduction para sa thirteen Month--------------------------------------\\

                                                        //---------------------------------Check kung regular ba o hindi------------------------------\\
                                                        $EmpClassification = mysqli_query($conn, "SELECT
                                                        employee_tb.classification, classification_tb.classification AS employee_classificaion
                                                        FROM employee_tb INNER JOIN classification_tb
                                                        ON employee_tb.classification = classification_tb.id
                                                        WHERE employee_tb.empid = '$EmployeeID'");

                                                        $rowClassification = $EmpClassification->fetch_assoc();
                                                        $Empclassy = $rowClassification['classification'];

                                                        if($rowClassification['employee_classificaion'] != 'Internship/OJT'){
                                                            $attSQL = "SELECT * FROM attendances WHERE empid = '$EmployeeID' AND (`status` = 'Present' OR `status` = 'On-Leave')
                                                            AND `date` BETWEEN '$str_date' AND '$end_date'";
                                                            $attResult = mysqli_query($conn, $attSQL);

                                                            if($attResult->num_rows > 0){
                                                                $attArray = array();

                                                                while($rowAtt_All = $attResult->fetch_assoc()){
                                                                    $DateAtt = $rowAtt_All['date'];
                                                                    $TimeInAtt = $rowAtt_All['time_in'];
                                                                    $TimeOutAtt = $rowAtt_All['time_out'];

                                                                    $attArray[] = array('DateAttendance' => $DateAtt);
                                                                }

                                                                $double_pay_holiday = 0;
                                                                $totalOT_pay_holiday = 0;
                                                                $totalOT_pay_holiday_restday = 0;
                                                                $double_pay_holiday_restday = 0;

                                                                foreach($attArray as $HolidayAttendance){
                                                                    $holidayArray = $HolidayAttendance['DateAttendance'];

                                                                    //Check kung may holiday sa attendance na nakapaloob sa cutoff
                                                                    $HolidayAttendance = mysqli_query($conn, "SELECT * FROM holiday_tb
                                                                    WHERE date_holiday = '$holidayArray'
                                                                    AND (`holiday_type` = 'Regular Holiday' OR `holiday_type` = 'Special Non-Working Holiday' OR `holiday_type` = 'Special Working Holiday')");

                                                                    if($HolidayAttendance->num_rows > 0){
                                                                        $row_holiday = $HolidayAttendance->fetch_assoc();
                                                                        $valid_holiday = $row_holiday['date_holiday'];
                                                                        $valid_holiday_type = $row_holiday['holiday_type'];

                                                                        //Check anong rule para mavalid ang holiday pay
                                                                        $CompanySettings = mysqli_query($conn, "SELECT * FROM settings_tb
                                                                        ORDER BY `_datetime` DESC LIMIT 1");
                                                                        $rowSettings = $CompanySettings->fetch_assoc();
                                                                        include 'Data Controller/Payroll/holiday_validation.php'; // para sa validation get ang rule ng holiday pay

                                                                        if($validation_eligible_holiday === 'YES'){
                                                                            //select lahat ng date sa employee na may holiday
                                                                        if($valid_holiday_type === 'Regular Holiday') {
                                                                            include 'Data Controller/Payroll/regularPay.php'; // Para sa computation ng regular Holiday Pay
                                                                        }
                                                                        else if($valid_holiday_type === 'Special Non-Working Holiday' || $valid_holiday_type === 'Special Working Holiday'){
                                                                            include 'Data Controller/Payroll/specialPay.php'; // Para sa computation ng Special Holiday Pay
                                                                  } 
                                                                }
                                                              }
                                                            } //Foreach close bracket
                                                          }
                                                        } //Classification close bracket
                                                        //---------------------------------End Check kung regular ba o hindi------------------------------\\

                                                        @$holiday_rate_with_dpay = $double_pay_holiday + $double_pay_holiday_restday;
                                                        @$holiday_rate_with_dpay_OT = $totalOT_pay_holiday + $totalOT_pay_holiday_restday;
                         
                                                        include 'Data Controller/Payroll/check_holiday_toDEduct.php'; //to check ilan ang date ng may holiday para ma minus sa salary at d magdoble ang salary
                                                        $row_holiday_to_deduct_holiday = $row_emp['drate'] * $num_days_holiday; //Dapat mabawasan ko sa mga date daily mga pinasok na holiday

                                                        //-------------------------------Loan Request Query-----------------------------\\
                                                        include 'Data Controller/Payroll/PayrollCompute/Loandata.php'; 
                                                        //-------------------------------Loan Request Query-----------------------------\\        

                                                        //------------------------------Net Payslip-------------------------------\\
                                                        $NotformatNetpay = $BasicTotalPay - $TotalDeduction;
                                                        $PayslipNetPay = "₱" . number_format($NotformatNetpay, 2);
                                                        //------------------------------Net Payslip-------------------------------\\   
                                                        
                                                        //-----------------------------for 13month basis---------------------------\\
                                                        $Notthirteenformat = $ThirteenSalary - $DeductionThirteen;
                                                        $ThirteenMonthPay = number_format($Notthirteenformat, 2);
                                                        //----------------------------End ng 13month basis-------------------------\\
                                                        
                                            ?>  
                                        <tr>
                                            <td style="font-weight: 400;"><?php echo $EmployeeID ?></td> <!--0-->
                                            <td style="font-weight: 400;"><?php echo $Fullname?></td> <!--1-->
                                            <td style="font-weight: 400;"><?php echo $sum?></td> <!--2-->
                                            <td style="font-weight: 400;"><?php echo $cutoffMonth ?></td> <!--3-->
                                            <td style="font-weight: 400;"><?php echo $cutoffYear ?></td> <!--4-->
                                            <td style="font-weight: 400;"><?php echo $str_date ?></td> <!--5-->
                                            <td style="font-weight: 400;"><?php echo $end_date ?></td> <!--6-->
                                            <td style="font-weight: 400;"><?php echo $cutoffNumber ?></td> <!--7-->
                                            <td style="font-weight: 400; display: none;"><?php echo $EmpPayRule ?></td> <!--8-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($Salarycutoff, 2, '.', ','); ?></td><!--9-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Totaldailyworks ?></td> <!--10-->
                                            <td style="font-weight: 400; display: none;"><?php echo $OTtime ?></td> <!--11-->
                                            <td style="font-weight: 400; display: none;"><?php echo $time_OT_TOTAL ?></td> <!--12-->
                                            <td style="font-weight: 400; display: none;"><?php  ?></td> <!--13-->
                                            <td style="font-weight: 400; display: none;"><?php echo $LeavewithPay?></td> <!--14-->
                                            <td style="font-weight: 400; display: none;"><?php echo round($TranspoAllowance,2)?></td> <!--15-->
                                            <td style="font-weight: 400; display: none;"><?php echo round($MealAllowance,2)?></td> <!--16-->
                                            <td style="font-weight: 400; display: none;"><?php echo round($InternetAllowance,2)?></td> <!--17-->
                                            <td style="font-weight: 400; display: none;"><?php echo round($addTotalAllowance,2)?></td> <!--18-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($BasicTotalPay,2) ?></td> <!--19-->
                                            <td style="font-weight: 400; display: none;"><?php echo $TotalAbsent ?></td><!--20-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($AbsentDeduction, 2, '.', ',') ?></td><!--21-->
                                            <td style="font-weight: 400; display: none;"><?php echo $TotalLate ?></td><!--22-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($LateTotalDeduction, 2, '.', ',') ?></td><!--23-->
                                            <td style="font-weight: 400; display: none;"><?php echo $UndertimeHours ?></td><!--24-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($UTtotaldeduction, 2, '.', ',')?></td><!--25-->
                                            <td style="font-weight: 400; display: none;"><?php echo $TotalLWOP ?></td><!--26-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($LWOPDeduction, 2, '.', ',') ?></td><!--27-->
                                            <td style="font-weight: 400; display: none;"><?php echo $SssAmount ?></td><!--28-->
                                            <td style="font-weight: 400; display: none;"><?php echo $TinAmount ?></td><!--29-->
                                            <td style="font-weight: 400; display: none;"><?php echo $PagIbigAmount ?></td><!--30-->
                                            <td style="font-weight: 400; display: none;"><?php echo $PhilhealthAmount ?></td><!--31-->
                                            <td style="font-weight: 400; display: none;"><?php echo $addTotalGovern ?></td><!--32-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($TotalDeduction,2) ?></td><!--33-->
                                            <td style="font-weight: 400; display: none;"><?php echo $LoanType?></td><!--34-->
                                            <td style="font-weight: 400; display: none;"><?php echo $AmountPay?></td><!--35-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Amortization?></td><!--36-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Balance?></td><!--37-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Applied ?></td><!--38-->
                                            <td style="font-weight: 400; display: none;"><?php echo $LoanStatus?></td><!--39-->
                                            <td style="font-weight: 400; display: none;"><?php echo $LoanDate?></td><!--40-->
                                            <td style="font-weight: 400; display: none;"><?php echo $cutOffID?></td><!--41-->
                                            <td style="font-weight: 400; display: none;"><?php echo $EmpStatus?></td><!--42-->
                                            <td style="font-weight: 400; display: none;"><?php echo number_format($PayslipSalary, 2, '.', ',')?></td><!--43-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Frequency?></td><!--44-->
                                            <td style="font-weight: 400; display: none;"><?php echo $allowances ?></td><!--45-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Governmentformat?></td><!--46-->
                                            <td style="font-weight: 400; display: none;"><?php echo $PayslipNetPay?></td><!--47-->
                                            <td style="font-weight: 400; display: none;"><?php echo $Totalwork ?></td><!--48-->
                                            <td style="font-weight: 400; display: none;"><?php echo $TotalLeavePaid ?></td><!--49-->
                                            <td style="font-weight: 400; display: none;"><?php echo $ThirteenMonthPay ?></td><!--50-->
                                            <td style="font-weight: 400;"><button type="button" class="btn btn-primary payrolldetails" data-bs-toggle="modal" data-bs-target="#Payrollbootstrap">View</button></td>
                                            <td><button type="button" class="btn btn-success textempID" data-bs-toggle="modal" data-bs-target="#viewPayslip">Payslip</button></td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                </table>
                            </div>   


<!-------------------------------------------------TABLE START------------------------------------------->                           
                        <!-- Modal Payslip-->
                        <div class="modal fade" id="viewPayslip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content" >
                                <form action="generate-pdf.php" method="post">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">PAYSLIP</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>

                                <div class="modal-body" id="modal-body" style="height: 667px;">
                                <input type="hidden" name="cuttoff_id" id="hidden_cutoffid">
                                <input type="hidden" id="hidden_payrule">
                                <input type="hidden" id="hidden_empid">
                                <input type="hidden" id="hidden_frequency">
                                <input type="hidden" id="hidden_cutoffmonth">
                                <input type="hidden" id="hidden_cutoffstart">
                                <input type="hidden" id="hidden_cutoffend">
                                <input type="hidden" id="hidden_cutoffnumber">
                                <input type="hidden" id="hidden_workingdays">
                                <input type="hidden" id="hidden_totalhours">
                                <input type="hidden" id="hidden_basicamount">
                                <input type="hidden" id="hidden_overtimehours">
                                <input type="hidden" id="hidden_overtimepay">
                                <input type="hidden" id="hidden_transportallowance">
                                <input type="hidden" id="hidden_mealallowance">
                                <input type="hidden" id="hidden_netallowance">
                                <input type="hidden" id="hidden_addnewallowance">
                                <input type="hidden" id="hidden_totalallowance">
                                <input type="hidden" id="hidden_Leavecount">
                                <input type="hidden" id="hidden_LeavePay">
                                <input type="hidden" id="hidden_holidayPay">
                                <input type="hidden" id="hidden_totalEarn">

                                <input type="hidden" id="hidden_absentcount">
                                <input type="hidden" id="hidden_absentdeduction">
                                <input type="hidden" id="hidden_sss">
                                <input type="hidden" id="hidden_philhealth">
                                <input type="hidden" id="hidden_tin">
                                <input type="hidden" id="hidden_pagibig">
                                <input type="hidden" id="hidden_othergovern">
                                <input type="hidden" id="hidden_totalgovern">
                                <input type="hidden" id="hidden_totalLate">
                                <input type="hidden" id="hidden_latededuction">
                                <input type="hidden" id="hidden_undertimehours">
                                <input type="hidden" id="hidden_undertimededuction">
                                <input type="hidden" id="hidden_lwop">
                                <input type="hidden" id="hidden_lwopdeduction">
                                <input type="hidden" id="hidden_totaldeduction">
                                <input type="hidden" id="hidden_netpay">
                                <input type="hidden" id="hidden_thirteenmonths">



                                

                                    <div class="header_view">
                                        <img src="icons/logo_hris.png" width="70px" alt="">
                                        <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="comp_name">Slash Tech Solutions Inc.</p>
   
                                        <p class="lbl_payPeriod">Pay Period :</p>
                                        <p class="dt_mdl_from" id="cutoffstart" name="col_strCutoff"></p>
                                            
                                        <p class="lbl_to">TO</p>
                                        <p class="dt_mdl_TO" id="cutoffend" name="col_endCutoff"></p>


                                        <p class="lbl_stats">Employee Status :</p>
                                        <p class="p_statss" id="empstatus"></p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_no">EMPLOYEE NO.   :</p>
                                        <p class="p_empid" id="employeeID" name="nameEmployee_Id"></p>
                                        <p class="p_payout">Payout        :</p>
                                        <p class="dt_pyout">
                                            <?php
                                                date_default_timezone_set('Asia/Manila');
                                                $current_date = date('Y / m / d');
                                                echo $current_date;
                                            ?>
                                        </p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_name">EMPLOYEE NAME  :</p>
                                        <p class="p_emp_name" id="id_p_emp_name"></p>
                                    </div>

                                    <div class="headbody">
                                        
                                        <div class="headbdy_pnl1">
                                            <p class="lbl_sss"></p>
                                            <p class="p_sss"></p>
                                            <p class="lbl_tin"></p>
                                            <p class="p_tin"></p>
                                        </div>
    
                                        <div class="headbdy_pnl2">
                                            <p class="lbl_phl"></p>
                                            <P class="p_phl"></P>
                                        </div>
    
                                        <div class="headbdy_pnl3">
                                            <p class="lbl_pgibg"></p>
                                            <P class="p_pgibg"></P>
                                        </div>

                                    </div><!----headbody--->

                                    <div class="headbody2">

                                        <div class="headbdy_pnl1">
                                            <p class="lbl_earnings">Earnings</p>
                                            <p class="lbl_Hours">Hours</p>
                                            <p class="lbl_Amount">Amount</p>
                                        </div>

                                        <div class="headbdy_pnl2">
                                        <p class="lbl_earnings">Deduction</p>
                                            <p class="lbl_Hours">Hours</p>
                                            <p class="lbl_Amount">Amount</p>
                                        </div>

                                        <div class="headbdy_pnl3">
                                            <p class="lbl_Balance">NET PAY</p>
                                        </div>

                                 </div><!---headbody2-->

                                    <div class="headbody3">
                                        <div class="headbdy_pnl11">

                                            <div class="div_mdlcontnt_left">
                                                <p class="lbl_bsc_pay">Basic Pay</p>
                                                <p class="lbl_bsc_pay">Overtime Pay</p>
                                                <p class="lbl_bsc_pay">Allowance</p>
                                                <p class="lbl_bsc_pay">PAID LEAVES</p>
                                                <p class="lbl_bsc_pay">HOLIDAY PAY</p>
                                            </div>

                                             <div class="div_mdlcontnt_left1">
                                                <p class="p_Thrs" id="empTotalwork" name="basic_total_work"></p>
                                                <p class="p_Thrs" id="empOThours" name="overtime_hours_name"></p>

                                            </div>

                                            <div class="div_mdlcontnt_left2">
                                                <p class="amountInall" id="empAmount" name="basic_salary_amount"></p>
                                                <p class="amountInall" id="OTamount" name="overtime_amount_name"></p>
                                                <p class="amountInall" id="allowanceAmount" name="allowance_total_name"></p>
                                                <p class="amountInall" id="leaveAmount" name="paid_leave_name"></p>
                                                <p class="amountInall" id="holidayAmount" name="holiday_pay_name"></p>
                                            </div>

                                       </div><!--headbdy_pnl11-->
                                        
                                            <div class="headbdy_pnl22">
                                                <div class="div_mdlcontnt_mid">
                                                    <div class="div_mdlcontnt_mid_left">
                                                        <p class="lbl_hdmf">Tardiness</p>
                                                        <p class="lbl_hdmf">Undertime</p>
                                                        <p class="lbl_hdmf">Absent</p>
                                                        <p class="lbl_hdmf">LWOP</p>
                                                        <p class="lbl_sss_se">SSS SE CONTRI</p>
                                                        <p class="lbl_philhlt_c">PHILHEALTH CONTRI</p>
                                                        <p class="lbl_sss_se">TIN CONTRI</p>
                                                        <p class="lbl_philhlt_c">PAGIBIG CONTRI</p>
                                                        <p class="lbl_hdmf">OTHER CONTRI</p>

                                                        <p  style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div>    

                                                    <div class="hourcontent_mid">
                                                        <p class="latehour" id="latehour"></p>
                                                        <p class="utHour" id="underhour"></p>
                                                    </div>
                        
                                                    <div class="div_mdlcontnt_mid_right">
                                                        <p class="lbl_philhlt_c" id="deductLate" name="late_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductUT" name="undertime_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="absentdeduct" name="absentkaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductLWOP" name="lwop_kaltas"></p>
                                                        <p class="lbl_sss_se" id="deductSSS" name="sss_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductphil" name="phil_kaltas"></p>
                                                        <p class="lbl_sss_se" id="deductTIN" name="tin_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductPagibig" name="pagibig_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductOther" name="other_kaltas"></p>
                                                        <p style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div> 
                                                </div>
                                            </div>  <!---headbdy_pnl22--->
                                            
                                             <div class="headbdy_pnl33">
                                                <div class="div_mdlcontnt_right">
                                                <!-- NETPAY VALUE -->
                                                    <p class="p_balance" id="netpayslip" name="netpay_name">
                                                    </p>
                                                </div>
                                            </div><!--headbdy_pnl33--->
                                            

                                    </div><!--headbody3-->  



                                    <div class="headbody2">
                                        <div class="headbdy_pnl1">
                                            <p class="lbl_earnings">Total Earnings :</p>
                                            <p class="lbl_Hours" id="totalEarn" name="overall_earn">
                                        </div>

                                        <div class="headbdy_pnl2">
                                                <p class="lbl_deduct">Total Deduction : </p>
                                                <p class="lbl_Amount2" id="totalDeduction" name="overall_deduction_name"></p>
                                        </div>

                                        <div class="headbdy_pnl3">
                                        <p class="lbl_Balance"></p>
                                    </div>
                                    </div> <!---headbody2---->

                                </div><!----modal body---->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="pdfPrint" onclick="makePDF()">Print</button>
                                    <button type="button" class="btn btn-secondary" id="id_btn_close" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                      </div>
                  </div>
               </div>
            </div>
         </div>


<!---------------Script para sa pagpindot ng print button at mapaginsert papuntang insert payslip--------------------->
<script>
document.getElementById("pdfPrint").addEventListener("click", function () {

    const dataToSend = {
                table_cutoff_id: document.getElementById("hidden_cutoffid").value,
                table_payrule_id: document.getElementById("hidden_payrule").value,
                table_employee_id: document.getElementById("hidden_empid").value,
                table_frequency_id: document.getElementById("hidden_frequency").value,
                table_cutoffmonth_id: document.getElementById("hidden_cutoffmonth").value,
                table_cutoffstart_id: document.getElementById("hidden_cutoffstart").value,
                table_cutoffend_id: document.getElementById("hidden_cutoffend").value,
                table_cutoffnumber_id: document.getElementById("hidden_cutoffnumber").value,
                table_workingdays_id: document.getElementById("hidden_workingdays").value,
                table_workinghours_id: document.getElementById("hidden_totalhours").value,
                table_basictotalamount_id: document.getElementById("hidden_basicamount").value,
                table_overtimehours_id: document.getElementById("hidden_overtimehours").value,
                table_overtimepay_id: document.getElementById("hidden_overtimepay").value,
                table_transport_id: document.getElementById("hidden_transportallowance").value,
                table_meal_id: document.getElementById("hidden_mealallowance").value,
                table_internet_id: document.getElementById("hidden_netallowance").value,
                table_newallowance_id: document.getElementById("hidden_addnewallowance").value,
                table_totalallowance_id: document.getElementById("hidden_totalallowance").value,
                table_leavenumber_id: document.getElementById("hidden_Leavecount").value,
                table_leavepay_id: document.getElementById("hidden_LeavePay").value,
                table_holidaypay_id: document.getElementById("hidden_holidayPay").value,
                table_totalEarn_id: document.getElementById("hidden_totalEarn").value,
                table_absentnumber_id: document.getElementById("hidden_absentcount").value,
                table_absentdeduct_id: document.getElementById("hidden_absentdeduction").value,
                table_sssamount_id: document.getElementById("hidden_sss").value,
                table_philamount_id: document.getElementById("hidden_philhealth").value,
                table_tinamount_id: document.getElementById("hidden_tin").value,
                table_pagibigamount_id: document.getElementById("hidden_pagibig").value,
                table_otherGovern_id: document.getElementById("hidden_othergovern").value,
                table_totalGovernment_id: document.getElementById("hidden_totalgovern").value,
                table_totallate_id: document.getElementById("hidden_totalLate").value,
                table_latededuction_id: document.getElementById("hidden_latededuction").value,
                table_undertimehours_id: document.getElementById("hidden_undertimehours").value,
                table_undertimededuction_id: document.getElementById("hidden_undertimededuction").value,
                table_lwopcount_id: document.getElementById("hidden_lwop").value,
                table_lwopdeduction_id: document.getElementById("hidden_lwopdeduction").value,
                table_totaldeduction_id: document.getElementById("hidden_totaldeduction").value,
                table_Netpayslip_id: document.getElementById("hidden_netpay").value,
                table_thirteenMonth_id: document.getElementById("hidden_thirteenmonths").value
                
                
    };

    fetch("solo_payslip.php", {
        method: "POST",
        body: JSON.stringify(dataToSend),
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        // Handle response if needed
        console.log(data);
    })
    .catch(error => {
        console.error("Error:", error);
    });
});

</script>

<!---------------Script para sa pagpindot ng print all button at mapaginsert papuntang insert payslip--------------------->      

<!-----------------------------------Script sa pagprint ng data payslip sa modal------------------------------------------->
<!-- <script>
window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;
function makePDF() {
  var employeeId = document.getElementById('id_employeeid').value;
  var empName = document.getElementById('id_p_emp_name').textContent;
  var Cutoff_Frequency = document.getElementById('id_table_frequency').value;
  var Cutoff_Numbers = document.getElementById('id_table_cutoffnum').value;
  var employee_workdays = document.getElementById('id_workdays').value;
  var cutoff_Id = document.getElementById('id_cutoff_id').value;

  html2canvas(document.querySelector("#modal-body"), {
    allowTaint: true,
    useCORS: true,
    scale: 1
  }).then(canvas => {
    var img = canvas.toDataURL("Payslip PDF");
        var doc = new jsPDF();
        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 7, 13, 195,105);
        var pdfFileName = empName + " - Payslip";
       doc.save(pdfFileName);

    // AJAX request to generate the PDF
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response = xhr.responseText;
        if (response === "Done") {
          // PDF generated successfully
          window.location.href = "generatePayslip.php?msg=Successfully Generated the Payslip&pdfFile=" + encodeURIComponent(pdfFileName);
        } else {
          // PDF generation failed
          console.log(response);
        }
      }
    };
    xhr.open("POST", "generate-pdf.php", true);
    var formData = new FormData();
    formData.append("pdfData", img);
    formData.append("employeeId", employeeId);
    formData.append("Cutoff_Frequency", Cutoff_Frequency);
    formData.append("Cutoff_Numbers", Cutoff_Numbers);
    formData.append("employee_workdays", employee_workdays);
    formData.append("cutoff_Id", cutoff_Id);
    xhr.send(formData); // Send the FormData object directly
  });
}
</script> -->

<script type="text/javascript">
    $("body").on("click", "#pdfPrint", function () {
        let employeeId = document.getElementById('hidden_empid').value;
        let Cutoff_Frequency = document.getElementById('hidden_frequency').value;
        let Cutoff_Numbers = document.getElementById('hidden_cutoffnumber').value;
        let employee_workdays = document.getElementById('hidden_workingdays').value;
        let cutoff_Id = document.getElementById('hidden_cutoffid').value;        

        var emp_fullname = document.getElementById("id_p_emp_name");
        var fullname = emp_fullname.textContent;
        var currentDate = new Date();
        var options = {
        timeZone: "Asia/Manila",
        year: "numeric",
        month: "numeric",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric"
        };

var currentDateTime = currentDate.toLocaleString("en-PH", options);
        html2canvas($('#modal-body')[0], {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download(fullname + "_" + currentDateTime  +".pdf");
                pdfMake.createPdf(docDefinition).getBase64(function (pdfData) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = this.responseText;
                            console.log(response);
                            if (response === "Done") {
                                window.location.href = "generatePayslip.php?msg=Successfully Generated the Payslip";
                            } else {
                                console.log(response);
                            }
                        }
                    };
                    xhr.open("POST", "generate-pdf.php", true);
                    var formData = new FormData();
                    formData.append("pdfData", pdfData);
                    formData.append("employeeId", employeeId);
                    formData.append("Cutoff_Frequency", Cutoff_Frequency);
                    formData.append("Cutoff_Numbers", Cutoff_Numbers);
                    formData.append("employee_workdays", employee_workdays);
                    formData.append("cutoff_Id", cutoff_Id);
                    xhr.send(formData);
                    document.getElementById('id_btn_close').style.display="";
                    document.getElementById('download-pdf').style.display="";
                });
            }
        });
    });
</script>
<!-----------------------------------Script sa pagprint ng data payslip sa modal------------------------------------------->


<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
$(document).ready(function(){
    $('.payrolldetails').on('click', function(){
        $('#Payrollbootstrap').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();
        var empPayRule = data[8]; // Assuming the pay rule is in data[8]
        //Tab 1
        $('#checktable').val(data[0]);
        $('#employeeName').text(data[1]);
        $('#rulesPay').text(data[8]);
        $('#SalaryRate').text(data[9]);
        $('#acDays').text(data[10]);
        $('#ot_shours').text(data[11]);
        $('#overtime').text(data[12]);
        $('#holiPay').text(data[13]);
        $('#leavePay').text(data[14]);
        $('#transport').text(data[15]);
        $('#meal').text(data[16]);
        $('#internet').text(data[17]);
        $('#other').text(data[18]);
        $('#addtotal').text(data[19]);
        if (empPayRule === 'Fixed Salary') {
            $('#salarytag').text('Salary Rate');
        } else if (empPayRule === 'Daily Paid') {
            $('#salarytag').text('Daily Rate');
        } else {
            $('#salarytag').text('Default Text'); // Set a default text if needed
        }

        //tab 2
        $('#absence').text(data[20]);
        $('#absencededucts').text(data[21]);
        $('#late').text(data[22]);
        $('#lateDeduct').text(data[23]);
        $('#undertime').text(data[24]);
        $('#utDeduct').text(data[25]);
        $('#lwopnumber').text(data[26]);
        $('#lwopkaltas').text(data[27]);
        $('#sss').text(data[28]);
        $('#philhealth').text(data[29]);
        $('#pagibig').text(data[30]);
        $('#tin').text(data[31]);
        $('#otherContributions').text(data[32]);
        $('#total_Deductions').text(data[33]);



        //table3
        $('#loantype').text(data[34]);
        $('#payable').text(data[35]);
        $('#amortization').text(data[36]);
        $('#balance').text(data[37]);
        $('#applied').text(data[38]);
        $('#loanstatus').text(data[39]);
        $('#loandate').text(data[40]);
    });
});
</script>
<!---------------------------------End ng Script whole view data ng modal------------------------------------------>



<!--------------------------------- Script para sa payslip data ng modal------------------------------------------>
<script>
$(document).ready(function(){
    $('.textempID').on('click', function(){
        $('#viewPayslip').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        var EmployeeId = data[0];
        var Fullname = data[1];
        var ProjectedWorkingDays = data[2];
        var MonthCutOff = data[3];
        var YearCutoff = data[4];
        var CutoffStartDate = data[5];
        var CutoffEndDate = data[6];
        var NumberofCutoff = data[7];
        var Payrules = data[8];
        var CutoffSalary = data[9];
        var ActualWorkDays = data[10];
        var OThours = data[11];
        var OTpay = data[12];
        var holidaypay //13
        var LeavePay = data[14];
        var TransportAllowance = data[15];
        var MealAllowance = data[16];
        var InternetAllowance = data[17];
        var AnotherAllowance = data[18];
        var BasictotalPay = data[19];
        var NumberAbsent = data[20];
        var AbsentDeduct = data[21];
        var TotalLate = data[22];
        var LateDeduction = data[23];
        var Undertimehours = data[24];
        var UndertimeDeduct = data[25];
        var LWOPnumber = data[26];
        var LWOPDeduct = data[27];
        var SSSamount = data[28];
        var TinAmount = data[29];
        var PagibigAmount = data[30];
        var PhilhealthAmount = data[31];
        var otherGovernment = data[32];
        var TotalDeduction = data[33];
        var CutoffID = data[41];
        var employeeStatus = data[42];
        var PayslipBasicpay = data[43];
        var Frequency = data[44];
        var TotalAllowance = data[45];
        var TotalGovernment = data[46];
        var TotalNetPay = data[47];
        var TotalWorkinghours = data[48];
        var LeaveCount = data[49];
        var ThirteenMonthCalculation = data[50];


        // Set the value of the <p> tag

        
        $('#employeeID').text(EmployeeId);
        $('#id_p_emp_name').text(Fullname);
        $('#cutoffstart').text(CutoffStartDate);
        $('#cutoffend').text(CutoffEndDate);
        $('#empTotalwork').text(TotalWorkinghours);
        $('#empAmount').text(PayslipBasicpay);
        $('#empOThours').text(OThours);
        $('#OTamount').text(OTpay);
        $('#allowanceAmount').text(TotalAllowance);
        $('#leaveAmount').text(LeavePay);
        // $('#holidayAmount').text(Payholiday);
        $('#deductSSS').text(SSSamount);
        $('#deductphil').text(PhilhealthAmount);
        $('#deductTIN').text(TinAmount);
        $('#deductPagibig').text(PagibigAmount);
        $('#deductOther').text(otherGovernment);
        $('#deductLate').text(LateDeduction);
        $('#latehour').text(TotalLate);
        $('#deductUT').text(UndertimeDeduct);
        $('#absentdeduct').text(AbsentDeduct);
        $('#underhour').text(Undertimehours);
        $('#deductLWOP').text(LWOPDeduct);
        $('#netpayslip').text(TotalNetPay);
        $('#totalEarn').text(BasictotalPay);
        $('#totalDeduction').text(TotalDeduction);
        $('#empstatus').text(employeeStatus);


        //input hidden value para maipasa ko sa ajax
        $('#hidden_cutoffid').val(CutoffID);
        $('#hidden_payrule').val(Payrules);
        $('#hidden_empid').val(EmployeeId);
        $('#hidden_frequency').val(Frequency);
        $('#hidden_cutoffmonth').val(MonthCutOff);
        $('#hidden_cutoffstart').val(CutoffStartDate);
        $('#hidden_cutoffend').val(CutoffEndDate);
        $('#hidden_cutoffnumber').val(NumberofCutoff);
        $('#hidden_workingdays').val(ActualWorkDays);
        $('#hidden_totalhours').val(TotalWorkinghours);
        $('#hidden_basicamount').val(PayslipBasicpay);
        $('#hidden_overtimehours').val(OThours);
        $('#hidden_overtimepay').val(OTpay);
        $('#hidden_transportallowance').val(TransportAllowance);
        $('#hidden_mealallowance').val(MealAllowance);
        $('#hidden_netallowance').val(InternetAllowance);
        $('#hidden_addnewallowance').val(AnotherAllowance);
        $('#hidden_totalallowance').val(TotalAllowance);
        $('#hidden_Leavecount').val(LeaveCount);
        $('#hidden_LeavePay').val(LeavePay);
        // $('#hidden_holidayPay').val(holidaypay);
        $('#hidden_totalEarn').val(BasictotalPay);

        $('#hidden_absentcount').val(NumberAbsent);
        $('#hidden_absentdeduction').val(AbsentDeduct);
        $('#hidden_sss').val(SSSamount);
        $('#hidden_philhealth').val(PhilhealthAmount);
        $('#hidden_tin').val(TinAmount);
        $('#hidden_pagibig').val(PagibigAmount);
        $('#hidden_othergovern').val(otherGovernment);
        $('#hidden_totalgovern').val(TotalGovernment);
        $('#hidden_totalLate').val(TotalLate);
        $('#hidden_latededuction').val(LateDeduction);
        $('#hidden_undertimehours').val(Undertimehours);
        $('#hidden_undertimededuction').val(UndertimeDeduct);
        $('#hidden_lwop').val(LWOPnumber);
        $('#hidden_lwopdeduction').val(LWOPDeduct);
        $('#hidden_totaldeduction').val(TotalDeduction);

        $('#hidden_netpay').val(TotalNetPay);

        $('#hidden_thirteenmonths').val(ThirteenMonthCalculation);


    });
});
</script>
<!---------------------------------End Script para sa payslip data ng modal------------------------------------------>



<!----------------------Script sa tab button------------------------------------->
<script>
var activeButton = document.querySelector(".tab .active button");

function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].parentNode.classList.remove("active"); // Remove active class from all buttons' parent divs
  }
  
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.parentElement.classList.add("active"); // Add active class to the clicked button's parent
  
  if (activeButton) {
    activeButton.parentElement.classList.remove("active"); // Remove active class from the previously active button's parent
  }
  

  activeButton = evt.currentTarget; // Update the active button reference
}
</script>
<!----------------------End Script sa tab button------------------------------------->


    

<!------------------------------------------------MESSAGE FUNCTION START------------------------------------------->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
<!------------------------------------------------MESSAGE FUNCTION END------------------------------------------->
<script>
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 4000);
</script>




<script>
 //HEADER RESPONSIVENESS SCRIPT
 
 
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-link').on('click', function(e) {
    if ($(window).width() <= 390) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-links').on('click', function(e) {
    if ($(window).width() <= 500) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>

<script> 
        $(document).ready(function(){
                $('.sched-update').on('click', function(){
                                    $('#schedUpdate').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    console.log(data);
                                    //id_colId
                                    $('#empid').val(data[8]);
                                    $('#sched_from').val(data[5]);
                                    $('#sched_to').val(data[6]);
                                });
                            });
            
    </script>


    <script> 
     $('.header-dropdown-btn').click(function(){
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });

//     $(document).ready(function() {
//     $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//     $('.dashboard-container').toggleClass('move-content');
  
//   });
// });
 $(document).ready(function() {
    var isHamburgerClicked = false;

    $('.navbar-toggler').click(function() {
    $('.nav-title').toggleClass('hide-title');
    // $('.dashboard-container').toggleClass('move-content');
    isHamburgerClicked = !isHamburgerClicked;

    if (isHamburgerClicked) {
      $('#schedule-list-container').addClass('move-content');
    } else {
      $('#schedule-list-container').removeClass('move-content');

      // Add class for transition
      $('#schedule-list-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#schedule-list-container').removeClass('move-content-transition');
      }, 800); // Adjust the timeout to match the transition duration
    }
  });
});
 

//     $(document).ready(function() {
//   $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//   });
// });


    </script>

    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="path/to/mpdf/autoload.php"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>

<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

<!--skydash-->
<script src="skydash/vendor.bundle.base.js"></script>
<script src="skydash/off-canvas.js"></script>
<script src="skydash/hoverable-collapse.js"></script>
<script src="skydash/template.js"></script>
<script src="skydash/settings.js"></script>
<script src="skydash/todolist.js"></script>
<script src="main.js"></script>
<script src="bootstrap js/data-table.js"></script>

<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>