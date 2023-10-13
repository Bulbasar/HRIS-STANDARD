<?php
  session_start();
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
    } else {
        include 'config.php';
        include 'user-image.php';
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <link rel="stylesheet" href="css/gnrate_all_slip.css">
    <title>Employee's Payslip</title>
</head>
<body>

    <div class="row">
                    <div class="col-6">
                        <p style="font-size: 25px; padding: 10px">Generate Payslip</p>
                    </div>
                    <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                           
                        <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#Modalslip" >
                            Print All
                        </button>
                        <button class="btn btn-primary"><a href="cutoff.php" style="text-decoration: none; color: white;">Back</a></button>    
                    </div>
                </div>  

                <!-- Modal -->
            <div class="modal fade" id="Modalslip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <h4>Print All Payslip?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="buttonPrint" id="PrintAllbutton" class="btn btn-primary" onclick="PrintallPayslip()">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                    </div>
                </div>
            </div>


<?php 
include 'config.php';
if(isset($_POST['printAll'])){
    $cutOffID = $_POST['name_btnview'];

    $Getcutoff = "SELECT * FROM cutoff_tb WHERE `col_ID` = '$cutOffID'";
    $Getrun = mysqli_query($conn, $Getcutoff);
    $cutoffrow = mysqli_fetch_assoc($Getrun);
    $cutOffID = $cutoffrow['col_ID'];
    $cutoffType = $cutoffrow['col_type'];
    $cutoffMonth = $cutoffrow['col_month'];
    $cutoffYear = $cutoffrow['col_year'];
    $cutoffNumber = $cutoffrow['col_cutOffNum'];
    $str_date = $cutoffrow['col_startDate'];
    $end_date = $cutoffrow['col_endDate'];
    $Frequency = $cutoffrow['col_frequency'];

    $CheckEmp = "SELECT * FROM empcutoff_tb WHERE `cutOff_ID` = '$cutOffID'";
    $RunEmp = mysqli_query($conn, $CheckEmp);

    $printAllslipArray = array(); // Array to store the dates
    while($rowEmp = mysqli_fetch_assoc($RunEmp)){
        $EmployeeID = $rowEmp['emp_ID'];

        $payruleQuery = "SELECT 
        payrule_tb.id,
        payrule_tb.rule_name,
        employee_tb.empid,
        employee_tb.payrules
        FROM employee_tb INNER JOIN payrule_tb ON employee_tb.payrules = payrule_tb.rule_name WHERE empid = '$EmployeeID'";
        $payruleResult = mysqli_query($conn, $payruleQuery);
        if($payruleResult){
            $payrow = $payruleResult->fetch_assoc();
            $payrules = $payrow['payrules']; //gagamitin ko sa condition kung fixed salary o daily paid
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
            }else if($Frequency === 'Semi-Month'){
                $Salarycutoff = $EmpSalary / 2;
                $PayslipSalary = $EmpSalary / 2; //Para sa payslip
            }else if($Frequency === 'Weekly'){
                $Salarycutoff = $EmpSalary / 4;
                $PayslipSalary = $EmpSalary / 4; //Para sa payslip
            }

            $BasicTotalPay = $Salarycutoff + $time_OT_TOTAL + $LeavewithPay + $allowances; //ito yung total para sa modal na wala pang deduction
            } else if($EmpPayRule === 'Daily Paid'){
                $Salarycutoff = $EmpDrate;
                $PayslipSalary = $EmpDrate * $Totaldailyworks;//Basic pay para sa payslip modal

                $DailyrateTotalworks = $Salarycutoff * $Totaldailyworks;
                $BasicTotalPay = $DailyrateTotalworks + $time_OT_TOTAL + $LeavewithPay + $allowances; //ito yung total para sa modal na wala pang deduction
            }
         //----------------------------------Salary without Deduction para sa modal----------------------------------\\

        //----------------------------------Total ng Deduction para sa modal----------------------------------\\
        @$TotalDeduction = $AbsentDeduction + $LateTotalDeduction + $UTtotaldeduction + $LWOPDeduction + $Governmentformat; //Total deduction ng modal
        //----------------------------------End ng total Deduction para sa modal----------------------------------\\

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

?>
                <div class="card" style="background-color: inherit;">
                  <div class="card-body" style="width: 58%;">
                    <form action="insert_payslip.php" method="post">
                    <div class="payslip_body" id="body_payslip" style="overflow-y: auto;">
                                <div class="header_view">
                                        <img src="icons/logo_hris.png" width="70px" alt="">
                                        <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="comp_name">Slash Tech Solutions Corp.</p>
                                        <p class="lbl_payPeriod">Pay Period :</p>
                                        <p class="dt_mdl_from" id="cutoffstart" name="cutoffstart_name"><?php echo $str_date?></p>
                                        <p class="lbl_to">TO</p>
                                        <p class="dt_mdl_TO" id="cutoffend" name="cutoffend_name"><?php echo $end_date?></p>

                                        <p class="lbl_stats">Employee Status :</p>
                                        <p class="p_statss" id="empstatus"><?php echo $EmpStatus?></p>
                                    </div>
                                    
                                    <div class="div1_mdl">
                                        <p class="emp_no">EMPLOYEE NO.   :</p>
                                        <p class="p_empid" id="employeeID" name="nameEmployee_Id"><?php echo $EmployeeID?></p>
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
                                        <p class="p_emp_name" id="id_p_emp_name" name="fullName_name"><?php echo $Fullname?></p>
                                        <p style="margin-left: 38px; font-size: smaller;"><?php echo $EmpPayRule?></p>
                                        <p style="display: none;"><?php echo $cutOffID?></p>
                                        <p style="display: none;"><?php echo $Totaldailyworks?></p>
                                        <p style="display: none;"><?php echo round($TranspoAllowance,2)?></p>
                                        <p style="display: none;"><?php echo round($MealAllowance,2)?></p>
                                        <p style="display: none;"><?php echo round($InternetAllowance,2)?></p>
                                        <p style="display: none;"><?php echo round($addTotalAllowance,2)?></p>
                                        <p style="display: none;"><?php echo $TotalAbsent ?></p>
                                        <p style="display: none;"><?php echo $Governmentformat?></p>
                                        <p style="display: none;"><?php echo $TotalLWOP?></p>
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


                                        <div class="headbdy_pnl1">
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

                                            <!---dito ang time ng total work at overtime--->
                                             <div class="div_mdlcontnt_left1">
                                                <p class="p_Thrs" id="empTotalwork" name="totalbasicwork_name"><?php echo $Totalwork?></p>
                                                <p class="p_Thrs" id="empOThours" name="totalot_name"><?php echo $OTtime?></p>
                                            </div>

                                            <!---dito naman ang amount--->
                                            <div class="div_mdlcontnt_left2">
                                            <p class="p_Tamount" id="empAmount" name="basicsalary_name"><?php echo number_format($PayslipSalary,2)?></p>
                                            <p class="p_Tamount" id="OTamount" name="amountOT_name"><?php echo number_format($time_OT_TOTAL,2)?></p>
                                            <p class="p_Tamount" id="allowanceAmount" name="allowance_name"><?php echo $allowances?></p>
                                            <p class="p_Tamount" id="leaveAmount" name="leavepay_name"><?php echo number_format($LeavewithPay,2)?></p>
                                            <p class="p_Tamount" id="holidayAmount" name="holiday_name"></p>
                                            </div>
                                       </div><!--headbdy_pnl11-->

                                       
                                        
                                            <div class="headbdy_pnl22">
                                                <div class="div_mdlcontnt_mid">
                                                    <div class="div_mdlcontnt_mid_left">
                                                        <p class="lbl_hdmf">Tardiness</p>
                                                        <p class="lbl_hdmf">Undertime</p>
                                                        <p class="lbl_hdmf">Absent</p>
                                                        <p class="lbl_hdmf">LWOP</p>
                                                        <p class="lbl_hdmf">SSS SE CONTRI</p>
                                                        <p class="lbl_hdmf">PHILHEALTH CONTRI</p>
                                                        <p class="lbl_hdmf">TIN CONTRI</p>
                                                        <p class="lbl_hdmf">PAGIBIG CONTRI</p>
                                                        <p class="lbl_hdmf">OTHER CONTRI</p>
                                                        <p  style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div>    

                                                    <div class="hourcontent_mid">
                                                        <p class="latehour" id="latehour"><?php echo $TotalLate?></p>
                                                        <p class="utHour" id="underhour"><?php echo $UndertimeHours?></p>
                                                    </div>
                        
                                                    <div class="div_mdlcontnt_mid_right">
                                                        <p class="lbl_philhlt_c" id="deductLate" name="late_name"><?php echo number_format($LateTotalDeduction,2)?></p>
                                                        <p class="lbl_philhlt_c" id="deductUT" name="under_name"><?php echo number_format($UTtotaldeduction,2)?></p>
                                                        <p class="lbl_philhlt_c" id="absencededuct" name="absent_name"><?php echo number_format($AbsentDeduction,2)?></p>
                                                        <p class="lbl_philhlt_c" id="deductLWOP" name="lwop_name"><?php echo number_format($LWOPDeduction,2)?></p>
                                                        <p class="lbl_sss_se" id="deductSSS" name="sss_name"><?php echo $SssAmount?></p>
                                                        <p class="lbl_philhlt_c" id="deductphil" name="philhealt_name"><?php echo $PhilhealthAmount?></p>
                                                        <p class="lbl_sss_se" id="deductTIN" name="tin_name"><?php echo $TinAmount?></p>
                                                        <p class="lbl_philhlt_c" id="deductPagibig" name="pagibig_name"><?php echo $PagIbigAmount?></p>
                                                        <p class="lbl_philhlt_c" id="deductOther" name="other_name"><?php echo $addTotalGovern?></p>
                                                        <p style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div> 
                                                </div>
                                            </div> 
                                            
                                             <div class="headbdy_pnl33">
                                                <div class="div_mdlcontnt_right">
                                                    <p class="p_balance" id="netpayslip" name="nitpiy_name">
                                                        <?php echo $PayslipNetPay?>
                                                    </p>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="headbody2">
                                        <div class="headbdy_pnl1">
                                            <p class="lbl_earnings">Total Earnings :</p>
                                            <p class="lbl_Hours" id="totalEarn" name="earn_name"><?php echo number_format($BasicTotalPay,2) ?></p>
                                        </div>

                                        <div class="headbdy_pnl2">
                                                <p class="lbl_deduct">Total Deduction : </p>
                                                <p class="lbl_Amount2" id="totalDeduction" name="totalkaltas_name"><?php echo number_format($TotalDeduction,2)?></p>
                                        </div>

                                        <div class="headbdy_pnl3">
                                        <p class="lbl_Balance"></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                      
    <?php
        
        $printAllslipArray[] = array('cutoffId' => $cutOffID, 'Payrules' => $EmpPayRule, 'EmployeeId' => $EmployeeID, 'Frequent' => $Frequency, 'Monthcutoff' => $cutoffMonth, 'Startcutoff' => $str_date, 'Endcutoff' => $end_date, 'Numbercutoff' => $cutoffNumber, 'Workingdays' => $Totaldailyworks, 'Workinghours' => $Totalwork, 'Basicpayslip' => number_format($PayslipSalary,2), 'HoursOT' => $OTtime, 'PayOT' => number_format($time_OT_TOTAL,2), 'Transport' => round($TranspoAllowance,2), 'Food' => round($MealAllowance,2), 'Internet' => round($InternetAllowance,2), 'newAllowance' => round($addTotalAllowance,2), 'totalAllowance' => $allowances, 'leavePay' => number_format($LeavewithPay,2), 'Totalearn' => $BasicTotalPay, 'Absentcount' => $TotalAbsent, 'Deductabsent' => $AbsentDeduction, 'SSScontribute' => $SssAmount, 'philcontribute' => $PhilhealthAmount, 'tincontribute' => $TinAmount, 'pagibigContribute' => $PagIbigAmount, 'othercontribute' => $addTotalGovern, 'totalcontribute' => $Governmentformat, 'latetotal' => $TotalLate, 'latedeductions' => number_format($LateTotalDeduction,2), 'Undertimehours' => $UndertimeHours, 'UTdeductions' => number_format($UTtotaldeduction,2), 'Lwopcount' => $TotalLWOP, 'DeductionLWOP' => number_format($LWOPDeduction,2), 'totalDeduction' => number_format($TotalDeduction,2), 'Totalnetpay' => $PayslipNetPay);
     }
     foreach ($printAllslipArray as $Employeeslip) {
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
     }
   }
?>
<!---------------Script para sa pagpindot ng print all button at mapaginsert papuntang insert payslip--------------------->
<script>
    document.getElementById("PrintAllbutton").addEventListener("click", function () {
        // Assuming you have an API endpoint to handle the insertion
        fetch("insert_payslip.php", {
            method: "POST",
            body: JSON.stringify(<?php echo json_encode($printAllslipArray); ?>),
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


<!----Script para macapture ang multiple payslip at mailagay sa separate na pages ----->
<script>
window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;

    function PrintallPayslip() {
        $('#Modalslip').modal('hide');
        var printAllslipArray = <?php echo json_encode($printAllslipArray); ?>;
        var pdf = new jsPDF('landscape');

        printAllslipArray.forEach(function (Employeeslip, index) {
            var Empid = Employeeslip.Empid;
            var Frequent = Employeeslip.Frequent;
            var Cutoffnumber = Employeeslip.Cutoffnumber;
            var WorkingDays = Employeeslip.WorkingDays;
            var CutoffId = Employeeslip.CutoffId;

            var payslipContainers = document.querySelectorAll(".payslip_body");

            payslipContainers.forEach(function (payslipContainer, index) {
                html2canvas(payslipContainer, {
                    allowTaint: true,
                    useCORS: true,
                    scale: 1
                }).then(canvas => {
                    if (index > 0) {
                        pdf.addPage('landscape');
                    }

                    var img = canvas.toDataURL("image/png");
                    pdf.addImage(img, 'PNG', 7, 13, pdf.internal.pageSize.getWidth() - 14, pdf.internal.pageSize.getHeight() - 26);

                    if (index === payslipContainers.length - 1) {
                        pdf.save("All_Payslips.pdf");

                        var formData = new FormData();
                        formData.append("pdfData", img);
                        formData.append("Empid", Empid);
                        formData.append("Frequent", Frequent);
                        formData.append("Cutoffnumber", Cutoffnumber);
                        formData.append("WorkingDays", WorkingDays);
                        formData.append("CutoffId", CutoffId);

                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "generate_all_pdf.php", true);
                        xhr.send(formData);
                    }
                });
            });
        });
    }    
// $("body").on("click", "#PrintAllbutton", function () {
//     var printAllslipArray = <?php echo json_encode($printAllslipArray); ?>;
    
//     var docDefinition = {
//         content: []
//     };

//     printAllslipArray.forEach(function (Employeeslip, index) {
//         var employeeId = Employeeslip.employeeId;
//         var Cutoff_Frequency = Employeeslip.frequency;
//         var Cutoff_Numbers = Employeeslip.numbercutoff;
//         var employee_workdays = Employeeslip.worknumdays;
//         var cutoffId = Employeeslip.cutoffId;
        
//         var emp_fullname = document.getElementById("id_p_emp_name");
//         var fullname = emp_fullname.textContent;
        
//         var currentDate = new Date();
//         var options = {
//             timeZone: "Asia/Manila",
//             year: "numeric",
//             month: "numeric",
//             day: "numeric",
//             hour: "numeric",
//             minute: "numeric",
//             second: "numeric"
//         };
        
//         var currentDateTime = currentDate.toLocaleString("en-PH", options);
//         var payslipContainers = document.querySelectorAll(".card-body");
        
//         payslipContainers.forEach(function (payslipContainer, containerIndex) {
//             html2canvas(payslipContainer, {
//                 onrendered: function (canvas) {
//                     var data = canvas.toDataURL();
//                     var pageContent = {
//                         image: data,
//                         width: 500
//                     };

//                     if (containerIndex > 0) {
//                         pageContent.pageBreak = 'after';
//                     }
                    
//                     docDefinition.content.push(pageContent);
                    
//                     if (index === printAllslipArray.length - 1 && containerIndex === payslipContainers.length - 1) {
//                         var pdfName = fullname + "_" + currentDateTime + ".pdf";
                        
//                         pdfMake.createPdf(docDefinition).download(pdfName);
//                         pdfMake.createPdf(docDefinition).getBase64(function (pdfData) {
//                             var formData = new FormData();
//                             formData.append("pdfData", pdfData);
//                             formData.append("employeeId", employeeId);
//                             formData.append("Cutoff_Frequency", Cutoff_Frequency);
//                             formData.append("Cutoff_Numbers", Cutoff_Numbers);
//                             formData.append("employee_workdays", employee_workdays);
//                             formData.append("cutoffId", cutoffId);
                            
//                             var xhr = new XMLHttpRequest();
//                             xhr.open("POST", "generate_all_pdf.php", true);
//                             xhr.send(formData);
//                         });
//                     }
//                 }
//             });
//         });
//     });
// });
// $("body").on("click", "#PrintAllbutton", function () {
//     var printAllslipArray = <?php echo json_encode($printAllslipArray); ?>;
    
//     var docDefinition = {
//         content: []
//     };

//     printAllslipArray.forEach(function (Employeeslip, index) {
//         var employeeId = Employeeslip.employeeId;
//         var Cutoff_Frequency = Employeeslip.frequency;
//         var Cutoff_Numbers = Employeeslip.numbercutoff;
//         var employee_workdays = Employeeslip.worknumdays;
//         var cutoffId = Employeeslip.cutoffId;
        
//         var emp_fullname = document.getElementById("id_p_emp_name");
//         var fullname = emp_fullname.textContent;
        
//         var currentDate = new Date();
//         var options = {
//             timeZone: "Asia/Manila",
//             year: "numeric",
//             month: "numeric",
//             day: "numeric",
//             hour: "numeric",
//             minute: "numeric",
//             second: "numeric"
//         };
        
//         var currentDateTime = currentDate.toLocaleString("en-PH", options);
//         var payslipContainers = document.querySelectorAll(".card-body");
        
//         payslipContainers.forEach(function (payslipContainer, containerIndex) {
//             html2canvas(payslipContainer, {
//                 onrendered: function (canvas) {
//                     var data = canvas.toDataURL();
//                     var pageContent = {
//                         image: data,
//                         width: 500
//                     };
                    
//                     // Add a page separator after each payslip except the last one
//                     if (containerIndex > payslipContainers.length - 0) {
//                         pageContent.pageBreak = 'after';
//                     }

                    
//                     docDefinition.content.push(pageContent);
                    
//                     // If this is the last payslip, generate and download the PDF
//                     if (index === printAllslipArray.length - 1 && containerIndex === payslipContainers.length - 1) {
//                         var pdfName = fullname + "_" + currentDateTime + ".pdf";
                        
//                         var pdf = pdfMake.createPdf(docDefinition);
//                         pdf.download(pdfName);
//                         pdf.getBase64(function (pdfData) {
//                             var formData = new FormData();
//                             formData.append("pdfData", img);
//                             formData.append("employeeId", employeeId);
//                             formData.append("Cutoff_Frequency", Cutoff_Frequency);
//                             formData.append("Cutoff_Numbers", Cutoff_Numbers);
//                             formData.append("employee_workdays", employee_workdays);
//                             formData.append("cutoffId", cutoffId);
                            
//                             var xhr = new XMLHttpRequest();
//                             xhr.open("POST", "generate_all_pdf.php", true);
//                             xhr.send(formData);
//                         });
//                     }
//                 }
//             });
//         });
//     });
// });



</script>
<!----Script para macapture ang multiple payslip at mailagay sa separate na pages ----->

<!-- <script> PARA TO SA MULTIPLE NAKASEPARATE NG PDF FILES 
window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;

function PrintallPayslip() {
    var payslipContainers = document.querySelectorAll(".payslip_body");

    payslipContainers.forEach(function(payslipContainer, index) {
        html2canvas(payslipContainer, {
            allowTaint: true,
            useCORS: true,
            scale: 1
        }).then(canvas => {
            var img = canvas.toDataURL("image/png");
            var doc = new jsPDF();
            doc.setFont('Arial');
            doc.getFontSize(11);
            doc.addImage(img, 'PNG', 7, 13, 195, 105);

            // Save each payslip as a separate PDF
            doc.save("Payslip_" + (index + 1));
        });
    });
}
</script> -->

<!-- <script> ITO AY PARA PAGSAMAHIN SA IISANG PAGE ANG MGA PAYSLIP
    window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;
    function PrintallPayslip() {
    html2canvas(document.querySelector("#all_payslips_container"), {
        allowTaint: true,
        useCORS: true,
        scale: 1
    }).then(canvas => {
        var img = canvas.toDataURL("image/png");
        var doc = new jsPDF();
        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 7, 13, 195, 105);
        doc.save("All_Payslips.pdf");
    });
}
</script> -->

<!-- <script>
    window.html2canvas = html2canvas;
    window.jsPDF = window.jspdf.jsPDF;
    
function PrintallPayslip() {
    var payslipContainers = document.querySelectorAll(".payslip_body");

    var pdf = new jsPDF();

    payslipContainers.forEach(function(payslipContainer, index) {
        html2canvas(payslipContainer, {
            allowTaint: true,
            useCORS: true,
            scale: 1
        }).then(canvas => {
            if (index > 0) {
                pdf.addPage();
            }
            var imgData = canvas.toDataURL("image/png");
            pdf.addImage(imgData, 'PNG', 0, 0, pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight());

            if (index === payslipContainers.length - 1) {
                pdf.save("All_Payslips.pdf");
            }
        });
    });
}

</script> -->



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