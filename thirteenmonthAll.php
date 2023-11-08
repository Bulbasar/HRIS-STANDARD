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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
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
    <link rel="stylesheet" href="css/gnrate_payroll.css"/>
    <link rel="stylesheet" href="css/gnrate_all_slip.css">
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


                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 30px;  font-family: Poppins, 'Source Sans Pro';">13th Month Pay List</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                
                                <button type="button" class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#Modalslip" >
                                    Print All
                                </button>
                                <button class="btn btn-primary"><a href="13month.php" style="text-decoration: none; color: white;">Back</a></button>
                                </div>
                            </div>

                         <?php
                                include 'config.php';
                                if(isset($_POST['viewpayslip'])){
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
                                } else if ($EmpPayRule === 'Daily Paid'){
                                    $CloneSalary = $EmpDrate * $Totaldailyworks;
                                    $Salary = $CloneSalary - $Deductions;
                                }
                            
                                $ThirteenMonthPay = $Salary / 12;
                            ?>
                                <div class="card" style="background-color: inherit;">
                                    <div class="card-body" style="width: 60%;">
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
                                            </div> <!---tablesidecontains-->
                                                
                                        </div> <!--body-clone-->
                                    </div>
                                </div>

                            <?php
                            }
                         }  
                      ?>
                








                      









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

<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
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