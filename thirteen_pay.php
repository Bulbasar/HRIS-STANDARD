<?php
session_start();
//    $empid = $_SESSION['empid'];
   if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
    // Check if the user's role is not "admin"
    if ($_SESSION['role'] != 'admin') {
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    } else{
        include 'config.php';
        $userId = $_SESSION['empid'];
       
        $iconResult = mysqli_query($conn, "SELECT id, emp_img_url, empid FROM employee_tb WHERE empid = '$userId'");
        $iconRow = mysqli_fetch_assoc($iconResult);

        if ($iconRow) {
            $image_url = $iconRow['emp_img_url'];
        } else {
            // Handle the case when the user ID is not found in the database
            $image_url = '../img/user.jpg'; // Set a default image or handle the situation accordingly
        }
    
    }
}

include_once 'config.php';

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Employee data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
    $alertStyle = 'style="font-size: 20px;"'; // add this line to set the font-size
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
      
      include 'configHardware.php';
      
      
      ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


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
    <link rel="stylesheet" href="css/13month.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtRecordsResponsives.css">
    <link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
    <title>13 Month Pay</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Barlow&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    </style>
</head>
<body>
<header>
    <?php
        include 'header.php';
    ?>
</header>
        <div class="modal fade" id="thirteenPrint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel" style="font-family: Poppins, 'Source Sans Pro';">13th Month Payslip</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="body-clone">
                                <div class="header-bodyof-modal">
                                    <div class="slash-id-empname">
                                        <table class="table table-bordered tabless">
                                            <tr>
                                                <th>COMPANY NAME</th>
                                                <td id="compName"></td>
                                            </tr>
                                            <tr>
                                                <th>EMPLOYEE ID</th>
                                                <td id="empids"></td>
                                            </tr>
                                            <tr>
                                                <th>EMPLOYEE NAME</th>
                                                <td id="empnames"></td>
                                            </tr>
                                        </table>
                                    </div>  

                                    <div class="paydates-container">
                                        <table class="table table-bordered tablesss">
                                            <tr>
                                                <th>PAY PERIOD</th>
                                                <td id="cutoffdate"></td>
                                            </tr>
                                            <tr>
                                                <th>PAY OUT</th>
                                                <td>
                                                    <?php
                                                    date_default_timezone_set('Asia/Manila');
                                                    $current_date = date('Y / m / d');
                                                    echo $current_date;
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="employeestatus-container">
                                        <table class="table table-bordered tablessss">
                                                <tr>
                                                    <th>EMPLOYEE STATUS</th>
                                                    <td id="empstatus"></td>
                                                </tr>
                                            </table>
                                        </div>
                                </div> <!--header-bodyof-modal-->

                                    <div class="table-side-contains">
                                        <div class="left-maincontainers">
                                            <div class="leftheads">
                                                <p>MONTHS COVERED</p>
                                                <p>BASIC PAY</p>
                                            </div>
                                            <div class="lefttable-contents">
                                                <div class="monthtagging">
                                                    <p id="monthNameTag"></p>
                                                </div>
                                                <div class="basicpay-value">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                            <div class="lefttable-footer">
                                                <div class="footerleft">
                                                    <p>Total Earnings: </p>
                                                </div>
                                                <div class="footerright">
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="middle-maincontainers">
                                            <div class="midheads">
                                                <p>MONTHS</p>
                                                <p>LATE(S)</p>
                                                <p>ABSENCE(S)</p>
                                                <p>UNDERTIME</p>
                                            </div>
                                            <div class="midtable-contents">
                                                <div class="midmonth-tag">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                                <div class="latevalue">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                                <div class="absencevalue">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                                <div class="utvalue">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                            <div class="midtable-footer">
                                                <div class="deductiontag">
                                                    <p>Total Deduction:</p>
                                                </div>
                                                <div class="totaldeduct-value">
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right-maincontainers">
                                            <div class="rightheads">
                                                <p>13 MONTH PAY</p>
                                            </div>
                                            <div class="righttable-contents">
                                                <div class="earn-deduct-tag">
                                                    <p>Total Earnings</p>
                                                    <p>Total Deductions</p>
                                                    <p>Total Net Pay</p>
                                                    <p>13th Month Pay</p>
                                                </div>
                                                <div class="earn-deduct-value">
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                            <div class="rightfooter">
                                                <div class="thirteentag">
                                                    <p>Thirteen Month</p>
                                                </div>
                                                <div class="thirteenvalue">
                                                    <p id=""></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="Print13month">Print</button>
                            <button type="button" class="btn btn-secondary" id="id_btn_close" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>


    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 30px;  font-family: Poppins, 'Source Sans Pro';">13th Month List</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <form action="thirteenmonthAll.php" method="post">
                                    <input type="hidden" name="view_emp_thirteen" value="<?php echo isset($_POST['view_emp_thirteen']) ? $_POST['view_emp_thirteen'] : ''; ?>">
                                    <button type="submit" name="viewpayslip" class="btn btn-primary">View all</button>
                                </form>

                                </div>
                            </div>

                            <div class="table-responsive mt-3" id="table-responsiveness">
                                <table id="order-listing" class="table">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th style="display: none;">Status</th>
                                            <th style="display: none;">Month Hired</th>
                                            <th>Thirteen Month Pay</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                        <?php 
                                            include 'config.php';
                                                if(isset($_POST['view_emp_thirteen'])){
                                                $cutoff_thirteen = $_POST['view_emp_thirteen'];
                                            
                                                $query_thirteen = "SELECT * FROM thirteencutoff_tb WHERE id = '$cutoff_thirteen'";
                                                $res_query = mysqli_query($conn, $query_thirteen);
                                                $thirteenRow = $res_query->fetch_assoc();
                                                $Monthrow = $thirteenRow['month'];
                                                $yearrow = $thirteenRow['year'];
                                                $str_date = $thirteenRow['start_date'];
                                                $end_date = $thirteenRow['end_date'];
                                                
                                                
                                                $getEmp = "SELECT * FROM empthirteen_tb WHERE `cut_id` = '$cutoff_thirteen'";
                                                $empresult = mysqli_query($conn, $getEmp);
                                                while($emprow = $empresult->fetch_assoc()){
                                                    $EmployeeID = $emprow['empid'];
                                                
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

                                                $settingscomp = mysqli_query($conn, "SELECT * FROM settings_company_tb");
                                                if($settingscomp){
                                                    $rowcomp = $settingscomp->fetch_assoc();
                                                    $companyname = $rowcomp['cmpny_name'];
                                                } else {
                                                    $companyname = "";
                                                }

                                            //-------------------Path sa code ng calculation sa sched-------------------\\
                                            include 'Data Controller/13Month/Sched-Calculation.php';
                                            //-------------------Path sa code ng calculation sa sched-------------------\\
                                                
                                            //-------------------Path sa code ng deduction para sa late-------------------\\
                                            include 'Data Controller/13Month/Deduction_Late.php';
                                            //-------------------Path sa code ng deduction para sa late-------------------\\

                                            //-------------------Path sa code ng computation para sa OT-------------------\\
                                            include 'Data Controller/13Month/OT-Computation.php';
                                            //-------------------Path sa code ng computation para sa OT-------------------\\                                      
                                                
                                            //-------------------Path sa code ng deduction para sa UT-------------------\\
                                            include 'Data Controller/13Month/UT-Computation.php';
                                            //-------------------Path sa code ng deduction para sa UT-------------------\\
                                            
                                            //-------------------Path sa code ng total sa working hours at late-------------------\\
                                            include 'Data Controller/13Month/Totalworkhours-late.php';
                                            //-------------------Path sa code ng total sa working hours at late-------------------\\ 

                                            //-------------------Path sa code ng total LWOP, Absent at Present-------------------\\
                                            include 'Data Controller/13Month/TotalLWOP-Absent-Present.php';
                                            //-------------------Path sa code ng total LWOP, Absent at Present-------------------\\      
                                            
                                            $Deductions = $AbsentDeduction + $LateTotalDeduction + $UTtotaldeduction + $LWOPDeduction;
                                            
                                                if($EmpPayRule === 'Fixed Salary'){
                                                    $Salary = $EmpSalary - $Deductions;

                                                    $Salaries = "";
                                                } else if ($EmpPayRule === 'Daily Paid'){
                                                    $CloneSalary = $EmpDrate * $Totaldailyworks;
                                                    $Salary = $CloneSalary - $Deductions;

                                                    $Salaries = $EmpDrate;
                                                }
                                            
                                                $ThirteenMonthPay = $Salary / 12;

                                                // $gethired = "SELECT * FROM employee_tb WHERE `empid` = '$EmployeeID'";
                                                // $resulthired = mysqli_query($conn, $gethired);
                                            
                                                // if(mysqli_num_rows($resulthired) > 0){
                                                //     $hiredrow = $resulthired->fetch_assoc();
                                                //     $Datehired = $hiredrow['empdate_hired'];
                                                // }
                                                // $months = array();
                                                // $start = new DateTime($Datehired);
                                                // $end = new DateTime($end_date);
                                                // $interval = DateInterval::createFromDateString('1 month');
                                                // $period = new DatePeriod($start, $interval, $end);

                                                // foreach ($period as $dt) {
                                                //     $months[] = $dt->format('F');
                                                // }

                                                // $months_variable = implode($months);

                                                $months = array();
                                                $start = new DateTime($str_date);
                                                $end = new DateTime($end_date);
                                                $interval = DateInterval::createFromDateString('1 month');
                                                $period = new DatePeriod($start, $interval, $end->modify('+1 month'));

                                                foreach ($period as $dt) {
                                                    $months[] = $dt->format('F');
                                                }

                                                $months_variable = implode($months);

                                                // $getatt = "SELECT MIN(`date`) AS min_date, MAX(`date`) AS max_date FROM attendances WHERE `empid` = '$EmployeeID' AND `date` BETWEEN '$str_date' AND '$end_date'";
                                                // $resultatt = mysqli_query($conn, $getatt);

                                                // if(mysqli_num_rows($resultatt) > 0){
                                                //     $rowatt = $resultatt->fetch_assoc();
                                                //     $min_date = $rowatt['min_date'];
                                                //     $max_date = $rowatt['max_date'];
                                                // }

                                                // $datearray = array();
                                                // $start_date = new DateTime($min_date);
                                                // $end_dates = new DateTime($max_date);
                                                // $interval = new DateInterval('P1M');
                                                // $daterange = new DatePeriod($start_date, $interval, $end_dates->modify('+1 month'));
                                                
                                                // foreach ($daterange as $date) {
                                                //     $datearray[] = $date->format('Y-m-d');
                                                // }    

                                                // $date_variable = implode($datearray);

                                                

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
                                                    foreach ($daterange as $date) {
                                                        $month = $date->format('m');
                                                        $monthly[$month][] = $date->format('Y-m-d');
                                                    }

                                                    $months_array = array();
                                                    foreach ($monthly as $month => $dates) {
                                                        $month_name = date('F', mktime(0, 0, 0, $month, 10));

                                                        $count_query = "SELECT COUNT(*) as present_count FROM attendances WHERE `empid` = '$EmployeeID' AND `date` IN ('" . implode("','", $dates) . "') AND `status` = 'Present'";
                                                        $result_count = mysqli_query($conn, $count_query);
                                                        $count_row = $result_count->fetch_assoc();
                                                        $present_count = $count_row['present_count'];

                                                        $total_present_count += $present_count;

                                                        $months_array[$month_name] = array(
                                                            'dates' => $dates,
                                                            'present_count' => $present_count
                                                        );

                                                        // echo "Month: " . $month_name . "<br>";
                                                        // echo "Dates: " . implode(", ", $dates) . "<br>";
                                                        // echo "Present Count: " . $present_count . "<br><br>";
                                                    }

                                                    // Echo total present count
                                                    // echo "Total Present Count: " . $total_present_count . "<br>";


                                                    

                                                
                                                
                                        ?>      

                                        <tr>
                                            <td style="font-weight: 400;"><?php echo $companyname?></td><!--0-->
                                            <td style="font-weight: 400;"><?php echo $EmployeeID?></td><!--1-->
                                            <td style="font-weight: 400;"><?php echo $Fullname?></td><!--2-->
                                            <td style="font-weight: 400;"><?php echo $str_date?></td><!--3-->
                                            <td style="font-weight: 400;"><?php echo $end_date?></td><!--4-->
                                            <td style="font-weight: 400; display: none;"><?php echo $EmpStatus?></td><!--5-->
                                            <td style="font-weight: 400; display: none;">
                                                <ul style="list-style-type: none; padding: 0;">
                                                    <?php 
                                                    foreach ($months as $month) {
                                                        echo "<li>" . $month . "</li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                            <td style="font-weight: 400;"><?php echo number_format($ThirteenMonthPay,2)?></td>
                                            <td>
                                                <button type="button" class="btn btn-success printthirteen" data-bs-toggle="modal" data-bs-target="#thirteenPrint">Print</button>
                                            </td>
                                        </tr>
                                        <?php
                                                
                                            }
                                        }
                                    ?>
                                </table>
                            </div>




                <div id="duplicateModal" class="modals">
                    <span class="close">&times;</span>
                    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                        <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                            <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                        </div>
                        <h4 class="mt-3">13th Month Cutoff is already existed!</h4>
                    </div>
                    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                        <button class="btn border border-black btn-closes">Close</button>
                    </div>
                </div>

                <!-- Overlay div -->
                <div class="overlay"></div>

                <!-- Modal HTML for Successfully Inserted -->
                <div id="insertedModal" class="modals">
                    <span class="close">&times;</span>
                    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                        <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                        <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; color: green"></i>
                        </div>
                        <h4 class="mt-3">Cutoff for 13th month is created successfully!</h4>
                    
                    </div>
                    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                        <button class="btn border border-black btn-closes">Close</button>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
                      




<script>
// Function to show a modal
function showModal(modalId, message) {
    var modal = document.getElementById(modalId);
    var overlay = document.querySelector('.overlay');
    modal.style.display = 'block';
    overlay.style.display = 'block';
}

// Function to hide a modal
function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    var overlay = document.querySelector('.overlay');
    modal.style.display = 'none';
    overlay.style.display = 'none';

    // Remove the parameter from the URL
    var urlParams = new URLSearchParams(window.location.search);
    urlParams.delete(modalId === 'duplicateModal' ? 'error' : 'inserted');
    var newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.replaceState({}, document.title, newUrl);
}

// Check if the URL contains a parameter and show the modal accordingly
window.onload = function () {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        showModal('duplicateModal', 'Duplicate Group Name!');
    }
    if (urlParams.has('inserted')) {
        showModal('insertedModal', 'Successfully Inserted!');
    }
}

// Close the modals when the user clicks the close button
var closeBtns = document.querySelectorAll('.close');
if (closeBtns) {
    closeBtns.forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function () {
            var modalId = this.closest('.modals').id;
            closeModal(modalId);
        });
    });
}
var closes = document.querySelectorAll('.btn-closes');
if (closes) {
    closes.forEach(function (closes) {
        closes.addEventListener('click', function () {
            var modalId = this.closest('.modals').id;
            closeModal(modalId);
        });
    });
}
</script>

<script>
$(document).ready(function(){
    $('.printthirteen').on('click', function(){
        $('#thirteenPrint').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        var CompanyName = data[0];
        var EmployeeId = data[1];
        var EmployeeName = data[2];
        var StartDate = data[3];
        var EndDate = data[4];
        var Status = data[5];
        var MonthHired = data[6];

        $('#compName').text(CompanyName);
        $('#empids').text(EmployeeId);
        $('#empnames').text(EmployeeName);
        $('#cutoffdate').text(StartDate + ' to ' + EndDate);
        if (Status === 'Active') {
            $('#empstatus').css('color', 'green');
        } else if (Status === 'Inactive') {
            $('#empstatus').css('color', 'red');
        } else {
            $('#empstatus').css('color', 'black');
        }
        $('#empstatus').text(Status);
                // Displaying months in the modal
                var months_list = '';
        $tr.find("td:eq(6) li").each(function(){
            months_list += $(this).text() + "<br/>";
        });
        $('#monthNameTag').html(months_list);
    });
});        
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