<?php
      session_start();
      //    $empid = $_SESSION['empid'];
         if (!isset($_SESSION['username'])) {
          header("Location: ../login.php");
      } else {
          // Check if the user's role is not "admin"
          if ($_SESSION['role'] != 'Employee') {
              // If the user's role is not "admin", log them out and redirect to the logout page
              session_unset();
              session_destroy();
              header("Location: logout.php");
              exit();
          } 
          
      }
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>

    <!-- unicon cdn -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">



<!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles2.css">
    <title>HRIS | Dashboard</title>

</head>
<body>
    <style>
        body {
            overflow-X: hidden;
            background-color: #f4f4f4 !important;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 1.2em;
            font-weight: 700;
        }

        .emp-dash2-shortcut a{
            text-decoration: none;
            font-size: 1em;        }

        .time{
            color: red;
        }
        .progress-bar li{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
        }

        .progress-bar li .icons{
            font-size: 3em;
            color: black;
              margin: 0 200px;
        }

        .progress-bar li .text{
            font-size: 1.4em;
            font-weight: 600;
            color: black;
        }
        
        .progress-bar li .prog {
            margin: 0.8em 0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: rgb(68,68,68);
            font-weight: 600;
            color: #fff;
            position: relative;
            cursor: pointer;
        }

        .progress-bar li .prog::after{
            content: " ";
            position: absolute;
            width: 410px;
            height: 5px;
            background-color: rgba(68,68,68,0.781);
            right: 34px;
        }

        .progress-bar li .one::after{
            width: 0;
            height: 0;
        }


        .progress-bar li .prog .uil{
            display:none;
            font-size: 1.2em;
            font-weight: 600;
        }
        
        .progress-bar li .prog p{
            font-size: 1.123em;
        }

        .progress-bar li .progress-active{
            background-color: blue;
        }

        .progress-bar li .progress-active::after{
            background-color: blue;
        }

        .progress-bar li .progress-active p{
            display :none;
           
        }
        .progress-bar li .progress-active .uil{
            display: flex;
        }

        .scrollable-content::-webkit-scrollbar{
         width: 1px;
        }
        .scrollable-content::-webkit-scrollbar-thumb{
        background-color: #888;
         }


    </style>
    <header>
        <?php include("header.php")?>
    </header>

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->


<!--------------------------------------Modal For Time In Button---------------------------------------->
<div class="modal fade" id="timeIn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Data Controller/Time Button/time_in.php" method="POST">
      <div class="modal-body">
          <h4>Do you want to Time In?</h4>
          <h4 id="currentTime">Loading...</h4> 
      </div>
         <div class="modal-footer">
          <button type="submit" name="time_in" class="btn btn-primary">Yes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------Modal For Time In Button---------------------------------------->


<!--------------------------------------Modal For Time Out Button---------------------------------------->
<div class="modal fade" id="timeOut" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Data Controller/Time Button/time_out.php" method="POST">
      <div class="modal-body">
          <h4>Do you want to Time Out?</h4>
          <h4 id="outTime"></h4> 
      </div>
         <div class="modal-footer">
          <button type="submit" name="time_out" class="btn btn-primary">Yes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------Modal For Time Out Button---------------------------------------->

<!---------------------------------------Announcement Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Announcement/download.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------- Announcement Download Modal End Here --------------------------------------->

    <div class="container-fluid p-2 pb-5" style="height: 100vh; width: 80%; position:absolute; top: 12.5%; left: 18%;">
        <div class=" w-100 h-100 d-flex flex-row justify-content-between">
            <div class="h-100 rounded bg-white d-flex flex-column p-3" style="width: 48.9%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                <div class="w-100 d-flex flex-column" style="height: 35%">
                    <div class="w-100 d-flex flex-row" style="height: 22%">
                        <div class="w-50 h-100 d-flex justify-content-center align-items-center">
                            <div class="d-flex flex-column justify-content-between p-2">
                               <span><span class="fw-bold">Schedule For: </span> <span style="color: blue; font-style: italic"> November 13,2023</span></span>
                               <span><span class="fw-bold">Schedule Type: </span><span style="color: blue; font-style: italic"> Office Based</span></span>
                            </div>
                        </div>
                        <div class="w-50 h-100 d-flex justify-content-center align-items-center">
                            <span><span class="fw-bold">Schedule Time: </span><span style="color: blue; font-style:italic">09:00 AM-06:00 PM</span></span>
                        </div>
                    </div>
                    <div class=" d-flex flex-column mt-2 border rounded shadow mx-auto" style="height: 78%; width: 90%">
                        <div class="w-100 h-25 d-flex justify-content-center align-items-center">
                            <h6 id="current_time" style="color: black; font-size: 1.3em; font-style:italic"></h6>
                        </div>  
                        <div class="w-100 h-75 d-flex justify-content-center align-items-center">
                            <div class="progress h-100 bg-white d-flex flex-row align-items-center">
                                <ul style="list-style-type:none; background-color: white; color: black;" class="d-flex flex-row progress-bar">
                                    <li>
                                        <i class="uil uil-stopwatch icons icon1"></i>
                                        <div class=" progress-one one prog d-flex flex-column justify-content-center align-items-center ">
                                            <p class="mt-2 mr-1 ml-1">1</p>
                                            <i class="uil uil-check"></i>
                                        </div>
                                        <p class="text text-one" style="cursor: pointer">Time in</p>
                                    </li>
                                    <li>
                                        <i class="uil uil-stopwatch-slash icons icon2"></i>
                                        <div class=" progress-two two prog d-flex flex-column justify-content-center align-content-center " style="padding-left: 0.7em">
                                            <p class="mt-2 mr-2">2</p>
                                            <i class="uil uil-check"></i>
                                        </div>
                                        <p class="text text-two" style="cursor: pointer">Time out</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100  mt-3 p-3" style="height: 13%">
                    <div class="w-100 h-100 d-flex flex-row justify-content-between  align-items-center">
                        <div class="h-100 w-50 d-flex flex-row justify-content-center align-items-center p-3" style="border-right:blue 1px solid">
                            <div class="h-100 w-100 d-flex flex-column align-items-center">
                                <h5 style="font-size: 1.5em">Yesterday</h5>
                                <h5 style="font-size: 1em">Restday</h5>
                            </div>
                        </div>
                        <div class="h-100 w-50 d-flex flex-row justify-content-center align-items-center p-3" style="border-left:blue 1px solid">
                            <div class="h-100 w-100 d-flex flex-column align-items-center">
                                <h5 style="font-size: 1.5em">Tomorrow</h5>
                                <h5 style="font-size: 1em">09:00AM - 06:00PM</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100  mt-3" style="height: 40%">
                    <div class=" h-100 d-flex flex-row justify-content-between rounded bg-white mx-auto" style="width: 95%;box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                        <div class="w-50 h-100 d-flex flex-column justify-content-around pl-5 ">
                            <div class="w-100 d-flex flex-row justify-content-between" style="height: 31%">
                                <div class="w-25 h-100 d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(87,44,198); background: linear-gradient(36deg, rgba(87,44,198,1) 22%, rgba(0,212,255,1) 90%, rgba(2,0,36,1) 100%);">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        $employeeid = $_SESSION['empid'];
                                        include '../config.php';
                                        date_default_timezone_set('Asia/Manila');

                                        $currentMonth = date('Y-m');
                                        $query = "SELECT COUNT(*) AS late_count
                                                        FROM attendances
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                        WHERE employee_tb.empid = '$employeeid'
                                                        AND DATE_FORMAT(attendances.date, '%Y-%m') = '$currentMonth'
                                                        AND attendances.late > 0"; // Modify the query to filter by the current month and check for late entries
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                                        
                                        if($row){
                                            $lateCount = $row['late_count'];
                                                    
                                        ?>
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $lateCount; ?></span>
                                                <span style="font-size: 1em">Total Tardiness</span>
                                            </div>
                                        <?php    
                                        }else{
                                            // Handle case when no row is returned or row is empty
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Total Tardiness</span>
                                                </div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="w-100 d-flex flex-row justify-content-between " style="height: 31%">
                                <div class="w-25 h-100  d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(34,193,195); background: linear-gradient(36deg, rgba(34,193,195,1) 0%, rgba(189,189,89,1) 35%, rgba(253,187,45,1) 100%);">
                                        <i class="fa-solid fa-bed"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        include '../config.php';
                                        $employeeid = $_SESSION['empid'];
                                                        date_default_timezone_set('Asia/Manila');

                                        $currentMonth = date('m');
                                        $currentYear = date('Y');

                                        // Modify the query to filter by the current month and year
                                        $query = "SELECT COUNT(*) AS total_absent
                                        FROM attendances
                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                        WHERE employee_tb.empid = '$employeeid'
                                        AND MONTH(attendances.date) = '$currentMonth'
                                        AND YEAR(attendances.date) = '$currentYear'
                                        AND attendances.status = 'Absent';"; 

                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);

                                        if($row){
                                            $totalAbsent = $row['total_absent'];
                                            ?>    
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $totalAbsent; ?></span>
                                                <span style="font-size: 1em">Total Absent</span>
                                            </div>
                                        <?php
                                        }else{
                                            // Handle case when no row is returned or row is empty
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Total Absent</span>
                                                </div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="w-100 d-flex flex-row justify-content-between" style="height: 31%">
                                <div class="w-25 h-100 d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(131,58,180);background: linear-gradient(36deg, rgba(131,58,180,1) 0%, rgba(253,29,29,1) 50%, rgba(252,176,69,1) 100%);">
                                        <i class="fa-solid fa-plane-departure"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        include '../config.php';

                                        $employeeid = $_SESSION['empid'];
                                        $query = "SELECT leaveinfo_tb.col_ID,
                                                employee_tb.empid,
                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                leaveinfo_tb.col_vctionCrdt,
                                                leaveinfo_tb.col_sickCrdt,
                                                leaveinfo_tb.col_brvmntCrdt
                                                FROM leaveinfo_tb
                                                INNER JOIN employee_tb ON leaveinfo_tb.col_empID = employee_tb.empid WHERE employee_tb.empid = '$employeeid';";

                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                                        
                                        if ($row) {
                                            $totalvacation = $row['col_vctionCrdt'];
                                            ?>
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $totalvacation; ?></span>
                                                <span style="font-size: 1em">Vacation Leave Balance</span>
                                            </div>
                                        <?php } else {
                                            // Handle case when no row is returned or row is empty
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Vacation Leave Balance</span>
                                                </div>";
                                        }
                                    ?>
                                </div>
                            </div> 
                        </div>

                        <!-- 2ND ROW -->
                        <div class="w-50 h-100 d-flex flex-column justify-content-around align-items-start pl-3">
                            <div class="w-100 d-flex flex-row justify-content-between" style="height: 31%">
                                <div class="w-25 h-100 d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(122,106,106); background: linear-gradient(65deg, rgba(122,106,106,1) 0%, rgba(230,230,47,1) 67%, rgba(253,187,45,1) 100%);">
                                        <i class="fa-solid fa-stopwatch-20"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        include '../config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        $employeeid = $_SESSION['empid'];
                                        $currentMonth = date('m');
                                        $currentYear = date('Y');

                                        $query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.overtime))) AS total_overtime 
                                                FROM attendances 
                                                INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                WHERE employee_tb.empid = '$employeeid' 
                                                AND MONTH(attendances.date) = '$currentMonth' 
                                                AND YEAR(attendances.date) = '$currentYear';";

                                        $result = mysqli_query($conn, $query);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if ($row['total_overtime']) {
                                                [$hours, $minutes, $seconds] = explode(':', $row['total_overtime']);
                                            } else {
                                                $hours = '00';
                                                $minutes = '00';
                                                $seconds = '00';
                                            }
                                        ?>
                                            
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $hours; ?>hr(s) <?php echo $minutes; ?>mn(s) <?php echo $seconds; ?>sec(s)</span>
                                                <span style="font-size: 1em">Total Overtime</span>
                                            </div>
                                        <?php
                                        } else {
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Vacation Leave Balance</span>
                                                </div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="w-100 d-flex flex-row justify-content-between " style="height: 31%">
                                <div class="w-25 h-100  d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(122,106,106); background: linear-gradient(65deg, rgba(122,106,106,1) 0%, rgba(214,214,201,1) 67%, rgba(168,151,113,1) 100%);">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        include '../config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        $employeeid = $_SESSION['empid'];
                                        $currentMonth = date('m');
                                        $currentYear = date('Y');

                                        $query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.early_out))) AS total_early_out 
                                                FROM attendances 
                                                INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                WHERE employee_tb.empid = '$employeeid' 
                                                AND MONTH(attendances.date) = '$currentMonth' 
                                                AND YEAR(attendances.date) = '$currentYear';";

                                        $result = mysqli_query($conn, $query);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if($row['total_early_out']){
                                            [$hours, $minutes, $seconds] = explode(':', $row['total_early_out']);
                                        }else{
                                            $hours = '00';
                                            $minutes = '00';
                                            $seconds = '00';
                                        }
                                    ?>
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $hours; ?>hr(s) <?php echo $minutes; ?>mn(s) <?php echo $seconds; ?>sec(s)</span>
                                                <span style="font-size: 1em">Total Overtime</span>
                                            </div>
                                    <?php
                                         } else {
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Vacation Leave Balance</span>
                                                </div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="w-100 d-flex flex-row justify-content-between" style="height: 31%">
                                <div class="w-25 h-100 d-flex flex-row justify-content-center align-items-center">
                                    <div class="dash-emp-icon" style="background: rgb(246,164,164); background: linear-gradient(65deg, rgba(246,164,164,1) 0%, rgba(214,214,201,1) 67%, rgba(245,220,165,1) 100%);">
                                        <i class="fa-solid fa-laptop-medical"></i>
                                    </div>
                                </div>
                                <div class="w-75 h-100 ">
                                    <?php
                                        include '../config.php';

                                        $employeeid = $_SESSION['empid'];
                                        $query = "SELECT leaveinfo_tb.col_sickCrdt,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                                leaveinfo_tb.col_vctionCrdt,
                                                                leaveinfo_tb.col_sickCrdt,
                                                                leaveinfo_tb.col_brvmntCrdt
                                                FROM leaveinfo_tb
                                                INNER JOIN employee_tb ON leaveinfo_tb.col_empID = employee_tb.empid WHERE employee_tb.empid = '$employeeid';";

                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                                        
                                        if ($row) {
                                            $sickcredit = $row['col_sickCrdt'];
                                            ?>
                                            <div class="d-flex flex-column justify-content-center  w-100 h-100">
                                                <span style="font-size: 1.2em;" class="fw-bold"><?php echo $sickcredit; ?></span>
                                                <span style="font-size: 1em">Total Tardiness</span>
                                            </div>
                                        <?php } else {
                                            // Handle case when no row is returned or row is empty
                                            echo "<div class='d-flex flex-column justify-content-center  w-100 h-100'>
                                                    <span style='font-size: 1.2em;' class='fw-bold'>0</span>
                                                    <span style='font-size: 1em'>Vacation Leave Balance</span>
                                                </div>";
                                         }
                                    ?>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <!-- box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); -->
            <div class="h-100 border d-flex flex-column" style="width: 48.9%;">
                <div class="w-100 p-3 bg-white rounded" style="height: 35%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17)">
                    <div class="w-100 h-100 d-flex flex-column">
                        <div class="w-100 d-flex align-items-center" style="height: 15%">
                            <h5 class="fs-4">Announcement</h5>
                        </div>
                        <div class="w-100" style="height: 80%;">
                            <div class="swiper w-100 h-100">
                                <div class="swiper-wrapper">
                                <?php
                                    include 'config.php';

                                    $query = "SELECT announcement_tb.id,
                                            announcement_tb.announce_title,
                                            employee_tb.empid,
                                            CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                            announcement_tb.announce_date,
                                            announcement_tb.description,
                                            announcement_tb.file_attachment 
                                            FROM announcement_tb 
                                            INNER JOIN employee_tb ON announcement_tb.empid = employee_tb.empid;";
                                    $result = mysqli_query($conn, $query);
                                    $slideIndex = 0;

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($slideIndex % 1 === 0) {
                                                $file = $row['file_attachment'];
                                                $id = $row['id'];
                                                $title = $row['announce_title'];
                                                echo "<div class='scrollable-content swiper-slide pl-5 pr-5 pt-3' style='overflow-y: scroll; overflow-x: hidden;'>";
                                            }
                                            ?>                          
                                            <div class="w-100 mt-2 ml-2 d-flex pr-3 flex-row justify-content-between"><h4 class=""><?php echo $row['announce_title'] ?></h4> <?php if(empty($file)) { echo ''; }else{ 
                                                echo '
                                                <table>
                                                    <thead>
                                                        <th class="d-none">id </th>
                                                        <th class="d-none">tb name </th>
                                                        <th class="d-none"> button </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="d-none">'?><?php echo $id;?><?php echo'</td>
                                                            <td class="d-none">'?><?php echo $title;?><?php echo'</td>
                                                            <td><button class="border-0 p-0 downloadbtn" style="font-style: italic; color: blue; text-decoration: underline;" type="button"
                                                                        data-bs-toggle="modal" data-bs-target="#download">File Attachment</butto</td>
                                                        </tr>
                                                    </tbody>
                                                </table>'; 
                                                            
                                                }  ?></div>
                                                <p class="ml-2"><span style="color: #7F7FDD; font-style: Italic;"><?php echo $row['full_name'] ?></span> - <?php echo $row['announce_date'] ?></p>
                                                    <p class="ml-2"><?php echo $row['description'] ?></p>
                                                    <?php
                                                    if (($slideIndex + 1) % 1 === 0) {
                                                    echo "</div>";
                                                }
                                                $slideIndex++;
                                            }
                                            if ($slideIndex % 1 !== 0) {
                                                echo "</div>";
                                            }
                                         } else {
                                            echo "<div class='announcement-slide'>";
                                            echo "<h1 style='text-align: center; margin-top:60px;'>No items on whiteboard</h1>";
                                            echo "</div>";
                                        }
                                        ?>
                                </div>
                                    <!-- If we need pagination -->
                                    <!-- <div class="swiper-pagination"></div> -->

                                    <!-- If we need navigation buttons -->
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                            </div>
                        </div>
                                
                    </div>
                </div>
                <div class="w-100 bg-white rounded mt-3 p-3" style="height: 30%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17)">
                    <div class="w-100 h-100 d-flex flex-column">
                        <div class="w-100 d-flex align-items-center" style="height: 15%">
                            <h5 class="fs-4">Events & Holidays</h5>
                        </div>
                        <div class="w-100" style="height: 80%;">
                            <div class="event-body pl-3 pr-3 pb-3 pt-1" style="width: 100%; height: 100%">
                                        <div class="event-week w-100 pl-3 pr-3 " style="height: 15%">
                                            <h5 style="font-weight: 400; font-size: 1.2em; color: blue" class="d-flex align-items-end mt-1">Week</h5>
                                        </div>
                                        <div class="event-contents w-100 pl-3 pr-3 scrollable-content" style="height: 80%; overflow-y: scroll">
                                             <div class="event_display w-100 ">
                                                <div class="first_content">
                                                    <?php
                                                        date_default_timezone_set('Asia/Manila');

                                                        // Get the current month's start and end dates
                                                        $startDate = date('Y-m-d');
                                                        $endDate = date('Y-m-t');

                                                        $currentDate = date('Y-m-d');

                                                        $query = "SELECT * FROM event_tb
                                                                WHERE ('$currentDate' BETWEEN `start_date` AND `end_date`)
                                                                OR (`start_date` BETWEEN '$startDate' AND '$endDate'
                                                                AND `end_date` >= '$currentDate')
                                                                ORDER BY `start_date` ASC";

                                                        $result = mysqli_query($conn, $query);

                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $event_title = $row['event_title'];
                                                            $event_type = $row['event_type'];
                                                            $start_date = date('Y-m-d', strtotime($row['start_date']));
                                                            $end_date = date('Y-m-d', strtotime($row['end_date']));
                                                            $eventDay = date('l', strtotime($row['start_date']));

                                                            $start_day = date('d', strtotime($start_date));
                                                            $start_month = date('F', strtotime($start_date));

                                                            // Extract day and month for the end date
                                                            $end_day = date('d', strtotime($end_date));
                                                            $end_month = date('F', strtotime($end_date));

                                                    ?>
                                                        <div class="w-100 d-flex flex-row justify-content-between align-items-center mb-2 rounded" style="height: 4em">
                                                            <div class="h-100 d-flex flex-column align-items-center justify-content-center" style="background-color: #cececece; width: 15%;  ">
                                                                <span style="font-size: 1.1em;" class="pt-2"><?php echo $start_day ?> </span>
                                                                <span style="font-size: 1em;"><?php echo $start_month ?> </span>
                                                                <span style="color: blue; font-weight: 500; font-size: 0.7em; margin-bottom: 1em" >Event</span>
                                                            </div>
                                                            <div class=" h-100 border" style="width: 85%">
                                                                <div class="w-100 h-100 pt-2 pb-2 pl-3 pr-3">
                                                                    <span class="mb-1" style="font-size: 1.2em"><?php echo $event_title ?></span><br>
                                                                    <span style="color: gray; font-weight: 500; font-size: 0.9em"><?php echo $event_type ?></span>
                                                                    <!-- <p style=" font-size: 1.2em" class="border mt-2"><?php echo $event_title ?></p> -->
                                                                    <!-- <p style="color: gray; font-weight: 500; font-size: 1em" class="border"><?php echo $event_type ?></p> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        }
                                                    ?>
                                                </div>
                                             </div>
                                             <div class="holiday_display">
                                                <div class="first_holiday_content">
                                                    <?php                                                
                                                        // // Display the start and end dates
                                                        // echo "Start date: " . $startDate . "<br>";
                                                        // echo "End date: " . $endDate;

                                                        $query = "SELECT * FROM holiday_tb WHERE holiday_type != 'Regular Working Day' AND `date_holiday` BETWEEN '$startDate' AND '$endDate' ORDER BY `date_holiday` ASC";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $holidayDate = date('Y-m-d', strtotime($row['date_holiday']));
                                                            $holidayDay = date('l', strtotime($row['date_holiday']));

                                                            $start_day = date('d', strtotime($row['date_holiday']));
                                                            $start_month = date('F', strtotime($row['date_holiday']));

                                                            $holiday_title = $row['holiday_title'];
                                                            $holiday_type = $row['holiday_type'];

                                                    ?>
                                                         <div class="w-100 d-flex flex-row justify-content-between align-items-center mb-2 rounded" style="height: 4em">
                                                            <div class="h-100 d-flex flex-column align-items-center justify-content-center" style="background-color: #cececece; width: 15%;  ">
                                                                <span style="font-size: 1.1em;" class="pt-2"><?php echo $start_day ?> </span>
                                                                <span style="font-size: 1em;"><?php echo $start_month ?> </span>
                                                                <span style="color: blue; font-weight: 500; font-size: 0.7em; margin-bottom: 1em" >Holiday</span>
                                                            </div>
                                                            <div class=" h-100 border" style="width: 85%">
                                                                <div class="w-100 h-100 pt-2 pb-2 pl-3 pr-3">
                                                                    <span class="mb-1" style="font-size: 1.2em"><?php echo $holiday_title ?></span><br>
                                                                    <span style="color: gray; font-weight: 500; font-size: 0.9em"><?php echo $holiday_type ?></span>
                                                                    <!-- <p style=" font-size: 1.2em" class="border mt-2"><?php echo $holiday_type ?></p> -->
                                                                    <!-- <p style="color: gray; font-weight: 500; font-size: 1em" class="border"><?php echo $event_type ?></p> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        }
                                                    ?>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
                <div class="w-100 bg-white rounded mt-3 p-3" style="height: 32%;box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17)"> 
                    <div class="w-100 h-100 d-flex flex-column">
                        <div class="w-100 d-flex align-items-center" style="height: 15%">
                            <h5 class="fs-4">Shortcut Links</h5>
                        </div>
                        <div class="w-100 p-2 d-flex flex-column justify-content-around" style="height: 85%">
                            <div class="w-100 d-flex flex-row align-items-center" style="height: 15%">
                                <div class="shortcut-icon d-flex justify-content-center " style="width: 2em; height: 100%; background-color: #333; cursor: pointer">
                                    <a href="attendance"><i class="fa-solid fa-chevron-right mt-1" style="color: #fff"></i></a>
                                </div>
                                    <a href="attendance" style="text-decoration: none" class="ml-2">View Attendance</a>
                            </div>
                            <div class="w-100 d-flex flex-row align-items-center" style="height: 15%">
                                <div class="shortcut-icon d-flex justify-content-center " style="width: 2em; height: 100%; background-color: #333; cursor: pointer">
                                    <a href="overtime_req"><i class="fa-solid fa-chevron-right mt-1" style="color: #fff"></i></a>
                                </div>
                                    <a href="overtime_req" style="text-decoration: none" class="ml-2">File Overtime</a>
                            </div>
                            <div class="w-100 d-flex flex-row align-items-center" style="height: 15%">
                                <div class="shortcut-icon d-flex justify-content-center " style="width: 2em; height: 100%; background-color: #333; cursor: pointer">
                                    <a href="leaveReq"><i class="fa-solid fa-chevron-right mt-1" style="color: #fff"></i></a>
                                </div>
                                    <a href="leaveReq" style="text-decoration: none" class="ml-2">Leave Request</a>
                            </div>
                            <div class="w-100 d-flex flex-row align-items-center" style="height: 15%">
                                <div class="shortcut-icon d-flex justify-content-center " style="width: 2em; height: 100%; background-color: #333; cursor: pointer">
                                    <a href="#"><i class="fa-solid fa-chevron-right mt-1" style="color: #fff"></i></a>
                                </div>
                                    <a href="#" style="text-decoration: none" class="ml-2">View Payslip</a>
                            </div>
                            <div class="w-100 d-flex flex-row align-items-center" style="height: 15%">
                                <div class="shortcut-icon d-flex justify-content-center " style="width: 2em; height: 100%; background-color: #333; cursor: pointer">
                                    <a href="my_schedule"><i class="fa-solid fa-chevron-right mt-1" style="color: #fff"></i></a>
                                </div>
                                    <a href="my_schedule" style="text-decoration: none" class="ml-2">View Schedule</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- swiper -->

    <script>
        const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
        });
    </script>

    <script>
        const one = document.querySelector(".one");
        const two = document.querySelector(".two");
        const text1 = document.querySelector(".text-one");
        const text2 = document.querySelector(".text-two");
        const icon1 = document.querySelector(".icon1");
        const icon2 = document.querySelector(".icon2");

        one.onclick = function(){
            one.classList.add("progress-active");
            two.classList.remove("progress-active");
             icon1.style.color = 'blue';
        }
        two.onclick = function(){
            // one.classList.remove("progress-active");
            two.classList.add("progress-active");
            icon2.style.color = 'blue';
        }

        text1.onclick = function(){
            one.classList.add("progress-active");
            two.classList.remove("progress-active");
              icon1.style.color = 'blue';
        }
        text2.onclick = function(){
            // one.classList.remove("progress-active");
            two.classList.add("progress-active");
            icon2.style.color = 'blue';
        }
    </script>

    <script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                $('#download').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table').val(data[0]);
                   $('#name_table').val(data[1]);
               });
             });
    </script>

    <script>
        function displayTime() {
    // get the current time in Manila timezone
    const manilaTime = new Date().toLocaleString("en-US", {
      timeZone: "Asia/Manila",
      hour12: true,
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
    });

    // display the time in the dashboard
    const currentTimeElem = document.getElementById("current_time");
    currentTimeElem.textContent = `Current Time in Manila: ${manilaTime}`;
  }

  // call the displayTime function every second to update the time
  setInterval(displayTime, 1000);

  
// Get the current date in Manila, Philippines
let currentDate = new Date().toLocaleString('en-PH', {
  timeZone: 'Asia/Manila',
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});

// Display the current date above the progress-container
document.getElementById("current_date").textContent = currentDate;




  // Function to get the current time in Manila paste in modal
  function getCurrentTimeInManila() {
    var now = new Date();
    var utcOffset = 8; // Manila is UTC+8
    var utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
    var manilaTime = new Date(utcTime + (3600000 * utcOffset));
    var hours = manilaTime.getHours();
    var minutes = manilaTime.getMinutes();
    var seconds = manilaTime.getSeconds();
    return hours + ":" + padZero(minutes) + ":" + padZero(seconds);
  }

  // Function to pad single digits with leading zero
  function padZero(num) {
    return num < 10 ? "0" + num : num;
  }

  // Update the <h4> tag with the current time in Manila
  document.addEventListener('DOMContentLoaded', function() {
    var currentTimeElement = document.getElementById('currentTime');
    if (currentTimeElement) {
      currentTimeElement.innerText =  getCurrentTimeInManila();
    }
  });
    </script>


<!-----------------------Script sa graph--------------------------------->
<!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('barChart').getContext('2d');
            var data = {
                labels: ['Label 1', 'Label 2', 'Label 3', 'Label 4', 'Label 5'],
                datasets: [{
                    label: 'Data',
                    data: [10, 20, 15, 25, 30],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            };

            var options = {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var barChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        });
    </script> -->
<!-----------------------Script sa graph--------------------------------->

<!------------------------Script sa function ng Previous and Next Button--------------------------------------->    
<script>
var currentSlide = 0;
var slides = document.getElementsByClassName("announcement-slide");

function showSlide(n) {
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[n].style.display = "block";
  currentSlide = n;
}

function prevSlide() {
  if (currentSlide > 0) {
    showSlide(currentSlide - 1);
  }
}

function nextSlide() {
  if (currentSlide < slides.length - 1) {
    showSlide(currentSlide + 1);
  }
}

showSlide(0); // Show the first slide initially


var announceContent = document.querySelector('.emp-dash2-announcement-content');
var prevButton = document.querySelector('.previous');
var nextButton = document.querySelector('.next-step');

announceContent.onscroll = function() {
  var scrollPosition = announceContent.scrollTop;

  // I-adjust ang posisyon ng mga prev at next button base sa scroll position
  prevButton.style.top = scrollPosition + announceContent.offsetHeight - prevButton.offsetHeight + 'px';
  nextButton.style.top = scrollPosition + announceContent.offsetHeight - nextButton.offsetHeight + 'px';
};

</script>
<!------------------------End Script sa function ng Previous and Next Button--------------------------------------->
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
      $('#dashboard-container').addClass('move-content');
    } else {
      $('#dashboard-container').removeClass('move-content');

      // Add class for transition
      $('#dashboard-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#dashboard-container').removeClass('move-content-transition');
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 390) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 500) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script> -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>


<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    
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

<script src="js/dashboard.js"></script>
</body>

</html>