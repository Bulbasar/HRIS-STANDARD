<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">


</head>
<body>
  <!-------------------------Modal para sa user profile------------------------------------------->
  <!-- <div class="modal fade" id="userProfileEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">User Profile</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                  <div class="header_userProfileEmployee"> 
                    <?php
                      // include 'config.php';
                      // $query = "SELECT * FROM settings_company_tb";
                      // $query_run = mysqli_query($conn, $query);

                      // if (mysqli_num_rows($query_run) > 0){
                      //   $company_row = mysqli_fetch_assoc($query_run);
                      //   $inserted_photo = $company_row['cmpny_logo'];
                      //   $image_data = base64_encode($inserted_photo); // Convert blob to base64
                        
                      //   $image_type = 'image/jpeg'; // Default image type
                      //   // Determine the image type based on the blob data
                      //   if (substr($image_data, 0, 4) === "\x89PNG") {
                      //     $image_type = 'image/png';
                      //   } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                      //     $image_type = 'image/jpeg';
                      //   } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                      //     $image_type = 'image/webp';
                      //   }
                      // }
                    ?>
                      <div class="Employee_userProfile">
                        <img src="data:<?php ?>;base64,<?php  ?>"> echo $image_type;  echo $image_data;
                        <div class="employee_photo">
                          <input type="file" name="photo" accept="image/jpeg, image/png, image/webp" value="">
                          <p class="file_guidance">Please upload a JPG, PNG, or WebP file.</p>
                        </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-6">
                            <label for="start" class="form-label">Name</label>
                            <input type="text" name="str_date" class="form-control" id="start_date" required>
                      </div>
                       <div class="col-6">
                             <label for="end" class="form-label">Email</label>
                             <input type="text" name="end_date" class="form-control" id="end_date" required>
                      </div>
                  </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> -->
     <!-------------------------Modal para sa user profile------------------------------------------->

    <!-- UPPER NAV -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row custom-navbar" id="upper-nav"> <!-- UPPER NAV MOTHER -->
                  <?php
                      include 'config.php';
                      $query = "SELECT * FROM settings_company_tb";
                      $query_run = mysqli_query($conn, $query);

                      if (mysqli_num_rows($query_run) > 0){
                        $company_row = mysqli_fetch_assoc($query_run);
                        $inserted_photo = $company_row['cmpny_logo'];
                        $image_data = base64_encode($inserted_photo); // Convert blob to base64
                        
                        $image_type = 'image/jpeg'; // Default image type
                        // Determine the image type based on the blob data
                        if (substr($image_data, 0, 4) === "\x89PNG") {
                          $image_type = 'image/png';
                        } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                          $image_type = 'image/jpeg';
                        } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                          $image_type = 'image/webp';
                        }
                      }
                    ?>
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" id="logo-upper-nav" >
      <a class="navbar-brand brand-logo me-5" href="dashboard.php" ><img src="data:<?php echo $image_type; ?>;base64,<?php echo $image_data; ?>" class="me-2" alt="logo" style="margin-left: 25px;"/></a>
        <!-- <a class="navbar-brand brand-logo-mini" href="dashboard.php" style="width: 100px;"><img src="img/header-logo-small.jpg" alt="logo" style="width: 100px; " /></a> -->
      </div>
      
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" id="upper-nav-container" >
        <button class="navbar-toggler navbar-toggler align-self-center" id="navbar-toggler" type="button" data-toggle="minimize">
            <span class="fa-solid fa-bars" style="color:white;"></span>
          </button> 
          <button id="sidebarToggle" class="responsive-bars-btn">
            <span class="fa-solid fa-bars" style="color:white;"></span>
          </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          
        <div class="header-user">
                <div class="header-notif">
                  <li class="nav-item dropdown">
                      <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-bell" style="color: white; font-size: 30px;"></i>
                        <span class="count"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>

                         <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $employeeID = $_SESSION['empid'];
                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));
                          
                            $query = "SELECT COUNT(*) AS employee_ot, MAX(`date_filed`) AS last_interact FROM overtime_tb
                            WHERE overtime_tb.empid = '$employeeID' AND overtime_tb.status IN ('Approved', 'Rejected')
                            AND DATE(overtime_tb.date_filed) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                $OT_row = mysqli_fetch_assoc($query_run);
                                $employeeOT = $OT_row['employee_ot'];
                                $Interact = $OT_row['last_interact'];

                                $now = time(); // Current timestamp
                                $InteractTime = strtotime($Interact); // Convert last_pending to timestamp
                                $timeDiff = $now - $InteractTime; // Difference in seconds

                                if ($timeDiff < 60) {
                                  $formattedTime = 'Just now';
                                } elseif ($timeDiff < 3600) {
                                  $minutes = floor($timeDiff / 60);
                                  $formattedTime = $minutes . ' minute(s) ago';
                                } else {
                                $hours = floor($timeDiff / 3600);
                                if ($hours < 24) {
                                  $formattedTime = $hours . ' hour(s) ago';
                                } else {
                                  $days = floor($hours / 24);
                                  $formattedTime = $days . ' day(s) ago';
                                }
                              }    
                            }

                            $hideDropdown = true;
                            $formattedTime = ""; // Initialize formattedTime variable outside the if block


                            $queryHide = "SELECT `status` FROM overtime_tb 
                            WHERE overtime_tb.empid = '$employeeID' AND overtime_tb.status IN ('Approved', 'Rejected') 
                            AND DATE(`date_filed`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsOT = mysqli_num_rows($query_run_hide);

                              if ($rowsOT > 0) {
                                  $hideDropdown = false;
                              }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeOT; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Overtime Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                 <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>

                     <!----------Undertime Notif----------->
                      <?php
                            include 'config.php';
                            $employeeID = $_SESSION['empid'];

                            date_default_timezone_set('Asia/Manila');
                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_ut, MAX(`date_file`) AS actiontaken_ut FROM undertime_tb 
                            WHERE undertime_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $UT_row = mysqli_fetch_assoc($query_run);
                              $employeeUT = $UT_row['employee_ut'];
                              $ActionTaken = $UT_row['actiontaken_ut'];
                              
                              $now = time(); // Current timestamp
                              $ActionTimeUT = strtotime($ActionTaken); // Convert last_pending to timestamp
                              $timeDiff = $now - $ActionTimeUT; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                              }
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM undertime_tb 
                            WHERE undertime_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsUT = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsUT > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeUT; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Undertime Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?> 

                    <!----------WFH Notif----------->
                        <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $employeeID = $_SESSION['empid'];

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_wfh, MAX(`date_file`) AS actiontaken_wfh FROM wfh_tb 
                            WHERE wfh_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $WFH_row = mysqli_fetch_assoc($query_run);
                              $employeeWFH = $WFH_row['employee_wfh'];
                              $ActionWFH = $WFH_row['actiontaken_wfh'];

                              $now = time(); // Current timestamp
                              $ActionTimeWFH = strtotime($ActionWFH); // Convert last_pending to timestamp
                              $timeDiff = $now - $ActionTimeWFH; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                             } 
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM wfh_tb 
                            WHERE wfh_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsWFH = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsWFH > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeWFH; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">WFH Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>

                      <!----------Official Business Notif----------->
                        <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $employeeID = $_SESSION['empid'];

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_OB, MAX(`_dateTime`) AS actiontaken_OB FROM emp_official_tb 
                            WHERE emp_official_tb.employee_id = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $OB_row = mysqli_fetch_assoc($query_run);
                              $employeeOB = $OB_row['employee_OB'];
                              $ActionOB = $OB_row['actiontaken_OB'];

                              $now = time(); // Current timestamp
                              $ActionTimeOB = strtotime($ActionOB); // Convert last_pending to timestamp
                              $timeDiff = $now - $ActionTimeOB; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                             } 
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM emp_official_tb 
                            WHERE emp_official_tb.employee_id = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsOB = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsOB > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeOB; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Official Business</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>


                     <!----------DTR Correction Notif----------->
                      <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $employeeID = $_SESSION['empid'];

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_DTR, MAX(`_dateTime`) AS actiontaken_DTR FROM emp_dtr_tb 
                            WHERE emp_dtr_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $DTR_row = mysqli_fetch_assoc($query_run);
                              $employeeDTR = $DTR_row['employee_DTR'];
                              $ActionDTR = $DTR_row['actiontaken_DTR'];

                              $now = time(); // Current timestamp
                              $ActionTimeDTR = strtotime($ActionDTR); // Convert last_pending to timestamp
                              $timeDiff = $now - $ActionTimeDTR; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                             } 
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM emp_dtr_tb 
                            WHERE emp_dtr_tb.empid = '$employeeID' AND `status` IN ('Approved', 'Rejected') 
                            AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsDTR = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsDTR > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeDTR; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">DTR Correction</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>


                      <!----------Leave Notif----------->
                      <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $employeeID = $_SESSION['empid'];

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_leave, MAX(`_datetime`) AS actiontaken_Leave FROM applyleave_tb 
                            WHERE applyleave_tb.col_req_emp = '$employeeID' AND `col_status` IN ('Approved', 'Rejected') 
                            AND DATE(`_datetime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $LEAVE_row = mysqli_fetch_assoc($query_run);
                              $employeeLEAVE = $LEAVE_row['employee_leave'];
                              $ActionLeave = $LEAVE_row['actiontaken_Leave'];

                              $now = time(); // Current timestamp
                              $ActionTimeLeave = strtotime($ActionLeave); // Convert last_pending to timestamp
                              $timeDiff = $now - $ActionTimeLeave; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                             } 
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `col_status` FROM applyleave_tb 
                            WHERE applyleave_tb.col_req_emp = '$employeeID' AND `col_status` IN ('Approved', 'Rejected') AND DATE(`_datetime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsLEAVE = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsLEAVE > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeLEAVE; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Leave Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>

                      </div>
                    </li>
                </div>

                <div class="header-head">
                  <?php 
                        include 'config.php';
                        $employeeID = $_SESSION['empid'];

                        $Supervisor_Profile = "SELECT * FROM employee_tb WHERE `empid` = '$employeeID'";
                        $profileRun = mysqli_query($conn, $Supervisor_Profile);

                        $SuperProfile = mysqli_fetch_assoc($profileRun);
                        $visor_Profile = $SuperProfile['user_profile'];

                        $image_data = "";
                                        
                        if (!empty($visor_Profile)) {
                            $image_data = base64_encode($visor_Profile); // Convert blob to base64
                        } else {
                            // Set default image path when user_profile is empty
                            $image_data = base64_encode(file_get_contents("img/user.jpg"));
                        }
                        
                        $image_type = 'image/jpeg'; // Default image type
                        
                        // Determine the image type based on the blob data
                        if (substr($image_data, 0, 4) === "\x89PNG") {
                            $image_type = 'image/png';
                        } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                            $image_type = 'image/jpeg';
                        } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                            $image_type = 'image/webp';
                        }
                     ?>
                    <img src="data:<?php echo $image_type; ?>;base64,<?php echo $image_data; ?>" alt="" srcset="" style="width: 5em; height:  5em;">
                </div>

                <div class="header-type">
                  <h1 style="color: white;margin-top: 15px; margin-bottom: 20px; text-transform: uppercase"><?php if(empty($_SESSION['username'])){
                                echo "no user!";
                            }else{
                                echo $_SESSION['username'];
                            }
                            ?></h1>
                    <p class="user-name" style="color: white; margin-top: 10px;"><?php if(empty($_SESSION['role'])){
                                echo "";
                            }else{
                                echo $_SESSION['role'];
                            }
                  ?></p>
                </div>

                <div class="header-dropdown" >
                    <button class="header-dropdown-btn" style="color: white"><span class="fa-solid fa-chevron-down"></span></button>
                    <div class="header-dropdown-menu" style="background-color: #000">
                        <a href="logout.php" class="header-dd-btn" style="text-decoration: none;color: white">Logout</a>
                        <a href="emp_profile.php" style="text-decoration:none; color: white">User Profile</a>
                    </div>
                </div>

         </div><!-----header-user----->
          <!-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../../../images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li> -->
        </ul>
        <!-- <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button> -->
      </div>
    </nav> <!-- END UPPER NAV -->
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        
      </div>
      <div id="right-sidebar" class="settings-panel">
        
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              
                <div class="form-group d-flex">
                  
                </div>
             
            </div>
            <div class="list-wrapper px-3">
              
            </div>
           
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                
              </div>
             
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                
              </div>
          
            </div>
          </div>
       
        </div>
      </div>

<!-- sidebar -->      
<nav class="sidebar sidebar-offcanvas custom-nav" id="sidebar" style="margin-top: 20px; position:fixed;">
        <ul class="nav" style="margin-top: 50px; color:red;">
          <li class="nav-item" style="color: black">
            <a class="nav-link" href="dashboard.php" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500">DASHBOARD</span>
            </a>
          </li>

          <li class="nav-item">
          <a class="nav-link nav-links" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" style="margin-top: 10px; color:white;">
            <i class="fa-regular fa-clock" id="side-icon"></i>
            <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px">SCHEDULE</span>
            <i class=" menu-arrow" style="color: white;"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu" id="sub-menu" style="width: 100%;">
              <li class="nav-item"> <a class="nav-link" href="my_schedule.php">MY SCHEDULE</a></li>
              <li class="nav-item"> <a class="nav-link" href="overtime_req.php">OVERTIME REQUEST</a></li>
              <li class="nav-item"> <a class="nav-link" href="undertime_req.php">UNDERTIME REQUEST</a></li>
              <li class="nav-item"> <a class="nav-link" href="wfh_request.php">WFH REQUEST</a></li>
              <li class="nav-item"> <a class="nav-link" href="official_emp.php">OFFICIAL BUSINESS</a></li>
              <li class="nav-item"> <a class="nav-link" href="dtr_emp.php">DTR CORRECTION</a></li>
             
            </ul>
          </div>
        </li>
        
        <li class="nav-item" style="color: black">
            <a class="nav-link" href="attendance.php" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500">ATTENDANCE</span>
            </a>
          </li>

          <li class="nav-item" style="color: black">
            <a class="nav-link" href="loanRequest" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500">LOAN REQUEST</span>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced" style="margin-top: 5px; color:white">
              <i class=" fa-regular fa-credit-card"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >PAYROLL</span>
              <i class="menu-arrow" style="color: white;"></i>
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="loanRequest.php">LOAN REQUEST</a></li>
                <li class="nav-item"> <a class="nav-link" href="gnrate_payroll.php">GENERATE PAYROLL</a></li>
                <li class="nav-item"> <a class="nav-link" href="generatePayslip.php">GENERATE PAYSLIP</a></li>
              </ul>
            </div>
          </li> -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-emp" aria-expanded="false" aria-controls="ui-emp" style="margin-top: 5px; color:white">
              <i class=" fa-solid fa-users" ></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >LEAVE INFORMATION</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-emp">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="leaveReq.php">LEAVE REQUEST</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">LEAVE CALENDAR</a></li>
              </ul>
            </div>
          </li>

           
        <li class="nav-item" style="color: black">
            <a class="nav-link" href="#" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 14px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500; letter-spacing: 1px;">COMPENSATION/BENEFITS</span>
            </a>
          </li>

          <li class="nav-item" style="color: black">
            <a class="nav-link" href="#" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 18px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500">DOCUMENT REQUEST</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-perf" aria-expanded="false" aria-controls="ui-perf" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-person-running"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >PERFORMANCE</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-perf">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="#">EVALUATION</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">TRAINING & COURSES</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">TASK</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-settings_profile" aria-expanded="false" aria-controls="ui-settings_profile" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-gear"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >SETTINGS</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-settings_profile">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="emp_profile.php">USER PROFILE</a></li>
              </ul>
            </div>
          </li>

          

        </ul>
      </nav>

 
</body>
</html>