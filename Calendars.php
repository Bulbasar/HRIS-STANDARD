
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
         @$userId = $_SESSION['empid'];
        
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

//  include 'Data Controller/Dashboard/fetchCalendar.php';
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
    <!-- <link rel="stylesheet" href="skydash/full-calendar.css"> -->

    <link rel="stylesheet" href="skydash/style.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="fullcalendar/lib/main.min.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <!-- <script src="js/bootstrap.min.js"></script> -->
    <script src="fullcalendar/lib/main.min.js"></script>

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- <link rel="stylesheet" href="css/calendar.css"> -->
    <title>HRIS | Calendar</title>
    </head>
<body>
    <header>
        <?php 
        include("header.php")
        ?>
    </header>

    <style>
       :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
          height: 100%;
          width: 100%;
            overflow: hidden; 
        } 

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }
        table, tbody, td, tfoot, th, thead, tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
        }
        #calendar{
          height: 75% !important;
        }
        .fc .fc-event{
          color: #000;
        } 
        .fc-event-title.fc-sticky{
          color: black !important;
          font-weight: 500;
        }

              /* Animation for appearing */
      @keyframes slideIn {
          0% {
              opacity: 0;
              transform: translateY(-20px);
          }
          100% {
              opacity: 1;
              transform: translateY(0);
          }
      }

      /* Animation for disappearing */
      @keyframes slideOut {
          0% {
              opacity: 1;
              transform: translateY(0);
          }
          100% {
              opacity: 0;
              transform: translateY(-20px);
          }
      }

      /* Apply animations to the d-block and d-none classes */
      .d-block {
          animation: slideIn 0.3s ease-in-out forwards;
      }

      .d-none {
          animation: slideOut 0.3s ease-in-out forwards;
      }
              
            
    </style>
        
    <div class="calendar-container" style="position: absolute; left: 19%; top: 14.6%; width: 78%; height: 80%; background-color: #fff; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); border-radius: 0.6em;">
      <div class="container-fluidd p-3 py-4 h-100" id="page-container" style="height: 100%">
          <div class="row w-100" style="height: 100%">
              <div class="col-md-9">
                  <h2 class="fs-2 ">Calendar</h2>
                  <div id="calendar" ></div>
              </div>
              <div class="col w-100 h-100 d-flex flex-column justify-content-between" style="position: inherit">
                  <div class=" w-100 d-flex align-items-center justify-content-end mt-2" style="height: 14%">
                  <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_event">Add Event</button> -->
                        <div class="w-100 d-flex mt-5 flex-row justify-content-between align-items-center pl-1 pr-1">
                              <h2 class="fs-4 mt-3">Events & Holidays</h2>
                              <div class="d-flex justify-content-center align-items-center" style="height: 2.8em; width: 2.8em; border-radius: 50%; cursor: pointer;   box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 1px 10px 0 rgba(0, 0, 0, 0.17)" id="addBtn">
                                  <i class="fa-solid fa-plus fs-4"></i>
                              </div>
                        </div>
                        <div class="d-flex flex-column p-3 d-none" style="background-color: white; position: absolute; top: 18%; width: 21em; box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2), 0 1px 10px 0 rgba(0, 0, 0, 0.17); border-radius: 0.3em;" id="btn-container">
                          <button class="btn btn-dark mb-2" data-bs-toggle="modal" data-bs-target="#add_event">Add Event</button>
                          <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#add_holiday">Add Holiday</button>
                      </div>
                  </div>
                    <script>
                        // Get references to the elements
                        const addBtn = document.getElementById("addBtn");
                        const btnContainer = document.getElementById("btn-container");

                        // Add a click event listener to the addBtn
                        addBtn.addEventListener("click", () => {
                            // Toggle the visibility of the btn-container
                            btnContainer.classList.toggle("d-none");
                            btnContainer.classList.toggle("d-block");
                        });
                    </script>

                  <div class=" w-100 rounded mt-2 border p-2 d-flex flex-column" style="height: 82.5%;">
                    <!-- today -->
                        <div style="height: 33%" class="w-100 p-2">
                              <div class="w-100">
                                  <p class="pl-1 pt-2 fs-5" style="font-weight: 500" >Today</p>
                              </div>
                            <?php 
                            include 'config.php';
                            $currentTimestamp = time();
                            $currentDate = date('Y-m-d', $currentTimestamp);
                            
                            $sql = "SELECT * FROM `schedule_list` 
                                    WHERE DATE(`start_datetime`) = '$currentDate' OR DATE(`end_datetime`) = '$currentDate'";
                            
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            @$start_date = $row['start_datetime'];
                            ?>
                            <style>
                              .scrollable-content::-webkit-scrollbar{
                                width: 1px;
                              }
                              .scrollable-content::-webkit-scrollbar-thumb{
                                background-color: #888;
                              }
                            </style>

                              <div class="w-100 h-75 mb-3 p-1 scrollable-content" style="<?php if(empty($start_date)){ echo ''; }else { echo 'overflow-y: scroll'; } ?>">
                                  <?php 
                                  include 'config.php';
                                  $currentTimestamp = time();
                                  $currentDate = date('Y-m-d', $currentTimestamp);
                                  $currentDayOfWeek = date('l', $currentTimestamp);
                                  
                                  $sql = "SELECT * FROM `schedule_list` 
                                          WHERE DATE(`start_datetime`) = '$currentDate' OR DATE(`end_datetime`) = '$currentDate'";

                                    $result = $conn->query($sql);

                                    if($result->num_rows > 0){
                                      while($row = $result->fetch_assoc()){
                                        $title = $row['title'];
                                        $description = $row['description'];
                                        $start_date = $row['start_datetime'];
                                        $end_date = $row['end_datetime'];
                                        
                                        $start_times = date('h:i a', strtotime($start_date));
                                        $end_time = date('h:i a', strtotime($end_date));

                                        $start_date = date('Y-m-d', strtotime($start_date));

                                        // Get the day of the week for the converted date
                                        $dayOfWeek = date('l', strtotime($start_date));

                                      ?>   
                                          <div style="height: 50%; width: 100%" class="mb-2">
                                            <div style="background-color: #F0F7FF; height: 100%; width: 100%; border-radius: 0.5em">
                                                <div class="d-flex flex-row w-100 h-100">
                                                    <div class="pl-3 pt-2 " style="width: 13%;">
                                                      <div style="height: 0.8em; width: 0.8em; border-radius: 50%; background-color: blue"></div>
                                                    </div>
                                                    <div class=" w-100 h-100">
                                                        <div class="p-2">
                                                              <h5 class=" "><?php echo $title ?></h5>
                                                              <p class="p-0" style="color: gray"><?php echo $start_times ?> - <span><?php echo $end_time ?></span> | <span><?php echo $dayOfWeek ?> </span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>      
                                      <?php
                                      }
                                    }else{ ?>
                                          <div class="d-flex justify-content-center w-100 h-100 align-items-center">
                                              <h4 style="color: gray; font-weight: 500" class="fs-5">No events or holiday today</h4>
                                          </div>
                                    <?php
                                    }
                                  
                                  
                                  ?>
                             </div>
                              <div class="w-100 border" style="height: 1px"></div>
                        </div>
                        <!-- This month -->
                        <div style="height: 33%" class="mb-2 w-100 p-2"> 
                          <div class="w-100">
                                    <p class="pl-1 pt-2 fs-5" style="font-weight: 500" >This Month</p>
                                </div>
                                <?php 
                                $currentTimestamp = time();

                                // Get the current month (numeric)
                                $currentMonth = date('n', $currentTimestamp);
                                
                                // Get the first day of the current month
                                $firstDayOfMonth = date('Y-m-01', $currentTimestamp);
                                
                                // Get the last day of the current month
                                $lastDayOfMonth = date('Y-m-t', $currentTimestamp);
                                
                                $sql = "SELECT * FROM `schedule_list` 
                                WHERE DATE(`start_datetime`) BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' ";
                                  
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                @$start_date = $row['start_datetime'];

                                ?>

                                <div class="w-100 h-75 mb-3 p-2 scrollable-content" style="<?php if(empty($start_date)){ echo ''; }else { echo 'overflow-y: scroll'; } ?>">
                                    
                                    <?php 
                                    include 'config.php';
                                     $currentTimestamp = time();

                                      // Get the current month (numeric)
                                      $currentMonth = date('n', $currentTimestamp);
                                      
                                      // Get the first day of the current month
                                      $firstDayOfMonth = date('Y-m-01', $currentTimestamp);
                                      
                                      // Get the last day of the current month
                                      $lastDayOfMonth = date('Y-m-t', $currentTimestamp);
                                      
                                      $sql = "SELECT * FROM `schedule_list` 
                                      WHERE DATE(`start_datetime`) BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' 
                                      ORDER BY `start_datetime` ASC";
                              

                                      $result = $conn->query($sql);

                                      if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $title = $row['title'];
                                            $description = $row['description'];
                                            $start_datetime = $row['start_datetime']; // Keep the original datetime value
                                            $end_datetime = $row['end_datetime']; // Keep the original datetime value
                                    
                                            // Convert datetime to date and time
                                            $start_date = date('Y-m-d', strtotime($start_datetime));
                                            $end_date = date('Y-m-d', strtotime($end_datetime));
                                            $start_time = date('h:i a', strtotime($start_datetime));
                                            $end_time = date('h:i a', strtotime($end_datetime));
                                    
                                            // Get the day of the week for the converted date
                                            $dayOfWeek = date('l', strtotime($start_date));
                                            ?>
                                            <div style="height: 50%; width: 100%" class="mb-2">
                                                <div style="background-color: #ABEBC6; height: 100%; width: 100%; border-radius: 0.5em">
                                                    <div class="d-flex flex-row w-100 h-100">
                                                        <div class="pl-3 pt-2 " style="width: 13%;">
                                                            <div style="height: 0.8em; width: 0.8em; border-radius: 50%; background-color: blue"></div>
                                                        </div>
                                                        <div class=" w-100 h-100">
                                                            <div class="p-2">
                                                                <h5 class=" "><?php echo $title ?></h5>
                                                                <p class="p-0" style="color: gray"><?php echo $start_time ?> - <span><?php echo $end_time ?></span> | <span><?php echo $dayOfWeek ?></span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        <?php  
                                        }
                                      }else{ ?>
                                            <div class="d-flex justify-content-center w-100 h-100 align-items-center">
                                              <h4 style="color: gray; font-weight: 500" class="fs-5">No events or holiday this month</h4>
                                          </div>
                                    <?php
                                      }
                                    ?>

                                </div>
                                <div class="w-100 border" style="height: 1px"></div>
                          </div>
                          <!-- next month -->
                        <div style="height: 33%" class="mb-2 w-100 p-2">
                          <div class="w-100">
                                  <p class="pl-1 pt-2 fs-5" style="font-weight: 500" >Nexth Month </p>
                              </div>
                              <?php 
                              $currentTimestamp = time();

                              // Get the current month (numeric)
                              $currentMonth = date('n', $currentTimestamp);
                              
                              // Get the first day of the current month
                              $firstDayOfMonth = date('Y-m-01', $currentTimestamp);
                              
                              // Get the last day of the current month
                              $lastDayOfMonth = date('Y-m-t', $currentTimestamp);
                              
                              // Calculate the first day of the next month
                              $firstDayOfNextMonth = date('Y-m-01', strtotime('+1 month', $currentTimestamp));
                              
                              // Calculate the last day of the next month
                              $lastDayOfNextMonth = date('Y-m-t', strtotime('+1 month', $currentTimestamp));
                              
                              
                              $sql = "SELECT * FROM `schedule_list` 
                              WHERE DATE(`start_datetime`) BETWEEN '$firstDayOfNextMonth' AND '$lastDayOfNextMonth' ";
                                
                              $result = mysqli_query($conn, $sql);
                              $row = mysqli_fetch_assoc($result);

                              @$start_date = $row['start_datetime'];
                              
                              ?>
                              <div class="w-100 h-75 mb-3 p-2 scrollable-content" style="<?php if(empty($start_date)){ echo ''; }else { echo 'overflow-y: scroll'; } ?>">
                              <?php 
                                    include 'config.php';
                                    $currentTimestamp = time();

                                    // Get the current month (numeric)
                                    $currentMonth = date('n', $currentTimestamp);
                                    
                                    // Get the first day of the current month
                                    $firstDayOfMonth = date('Y-m-01', $currentTimestamp);
                                    
                                    // Get the last day of the current month
                                    $lastDayOfMonth = date('Y-m-t', $currentTimestamp);
                                    
                                    // Calculate the first day of the next month
                                    $firstDayOfNextMonth = date('Y-m-01', strtotime('+1 month', $currentTimestamp));
                                    
                                    // Calculate the last day of the next month
                                    $lastDayOfNextMonth = date('Y-m-t', strtotime('+1 month', $currentTimestamp));
                                    
                                    
                                    $sql = "SELECT * FROM `schedule_list` 
                                    WHERE DATE(`start_datetime`) BETWEEN '$firstDayOfNextMonth' AND '$lastDayOfNextMonth' ";
                              

                                      $result = $conn->query($sql);

                                      if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $title = $row['title'];
                                            $description = $row['description'];
                                            $start_datetime = $row['start_datetime']; // Keep the original datetime value
                                            $end_datetime = $row['end_datetime']; // Keep the original datetime value
                                    
                                            // Convert datetime to date and time
                                            $start_date = date('Y-m-d', strtotime($start_datetime));
                                            $end_date = date('Y-m-d', strtotime($end_datetime));
                                            $start_time = date('h:i a', strtotime($start_datetime));
                                            $end_time = date('h:i a', strtotime($end_datetime));
                                    
                                            // Get the day of the week for the converted date
                                            $dayOfWeek = date('l', strtotime($start_date));
                                            ?>
                                            <div style="height: 50%; width: 100%" class="mb-2">
                                                <div style="background-color: #F5B7B1; height: 100%; width: 100%; border-radius: 0.5em">
                                                    <div class="d-flex flex-row w-100 h-100">
                                                        <div class="pl-3 pt-2 " style="width: 13%;">
                                                            <div style="height: 0.8em; width: 0.8em; border-radius: 50%; background-color: blue"></div>
                                                        </div>
                                                        <div class=" w-100 h-100">
                                                            <div class="p-2">
                                                                <h5 class=" "><?php echo $title ?></h5>
                                                                <p class="p-0" style="color: gray"><?php echo $start_time ?> - <span><?php echo $end_time ?></span> | <span><?php echo $dayOfWeek ?></span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   
                                        <?php  
                                        }
                                      }else{ ?>
                                            <div class="d-flex justify-content-center w-100 h-100 align-items-center">
                                              <h4 style="color: gray; font-weight: 500" class="fs-5">No events or holiday this month</h4>
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
      <!-- Event Details Modal -->
      <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal" >
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-0">
                  <div class="modal-header rounded-0">
                      <h5 class="modal-title">Schedule Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body rounded-0">
                      <div class="container-fluid">
                          <dl>
                              <dt class="text-muted">Title</dt>
                              <dd id="title" class="fw-bold fs-4"></dd>
                              <dt class="text-muted">Description</dt>
                              <dd id="description" class=""></dd>
                              <dt class="text-muted">Start</dt>
                              <dd id="start" class=""></dd>
                              <dt class="text-muted">End</dt>
                              <dd id="end" class=""></dd>
                             
                           
                              
                          </dl>
                      </div>
                  </div>
                  <div class="modal-footer rounded-0">
                      <div class="text-end">
                          <!-- <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button> -->
                          <!-- <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button> -->
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>


    <!-------------------------------------------Modal of Event Start Here--------------------------------------------->
<div class="modal fade" id="add_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Calendar/insert_event.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        echo 'Super Admin';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" name="event_title" class="form-control" id="id_title_event" required>
                        </div>

                        <div class="mb-3">
                            <label for="event" class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" class="form-control" id="id_event_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="event" class="form-label">End Date</label>
                            <input type="datetime-local" name="end_date" class="form-control" id="id_event_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="eventype" class="form-label">Type of Event</label>
                            <input type="text" class="form-control" name="event_type" id="id_event_type"></input>
                        </div>
                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_event" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Event End Here---------------------------------------------> 
<!-------------------------------------------Modal of Holiday Start Here--------------------------------------------->
<div class="modal fade" id="add_holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Holiday</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Calendar/insert_holiday.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        echo 'Super Admin';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Holiday Title</label>
                            <input type="text" name="type_holiday" class="form-control" id="id_title_holiday" required>
                        </div>


                        <div class="mb-3">
                            <label for="event" class="form-label">Holiday Date</label>
                            <input type="date" name="date_holiday" class="form-control" id="id_holiday_date" required>
                            <p style="color: red" id="holidayMsg"></p>
                        </div>
                        
                       
                            
                        <div class="mb-3">
                            <label for="eventype" class="form-label">Type of Holiday</label>
                            <select class="form-select form-select-m" name="title_holiday" id="" aria-label=".form-select-sm example" style="height: 50px; cursor: pointer;">
                                <option value="Regular Working Day">Regular Working Day</option>
                                <option value="Regular Holiday">Regular Holiday</option>
                                <option value="Special Working Holiday">Special Working Holiday</option>
                                <option value="Special Non-Working Holiday">Special Non-Working Holiday</option>
                            </select>
                        </div>
                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_holiday" class="btn btn-primary" id="btn_save">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Holiday End Here---------------------------------------------> 
<!-------------------------------------------Modal of Holiday Start Here--------------------------------------------->
<div class="modal fade" id="add_cutoff" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Holiday</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Calendar/cutoff_date.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                          <label for="">Payroll 1st Cutoff Day</label><br>
                          <input type="text" name="cutoff_day1" placeholder="Input within a number in a month" value="<?php echo $hello ?>" id="" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 2) this.value = this.value.slice(0, 2);"><br>

                          <label for="">Payroll 2nd Cutoff Day</label><br>
                          <input type="text" name="cutoff_day2" placeholder="Input within a number in a month" id="" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 2) this.value = this.value.slice(0, 2);">
                          
                    </div>
                </div><!--Modal body Close tag--->
                <div class="modal-footer">
                <button type="submit" name="add_holiday" class="btn btn-primary" id="btn_save">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Holiday End Here---------------------------------------------> 

    <?php 
$schedules = $conn->query("SELECT * FROM `schedule_list`");
$sched_res = [];
  foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
      $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
      $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
      $hello = $row['title'];
      $sched_res[$row['id']] = $row;


  }



?>
<?php 
if(isset($conn)) $conn->close();
?>



<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="js/script.js"></script>

<style>
  <?php 
    if (!empty($hello)) {
      // Array of possible background colors
      $backgroundColors = ['#A3E4D7'];

      // Select a random background color from the array
      $randomColor = $backgroundColors[array_rand($backgroundColors)];

      echo '.fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start { background-color: ' . $randomColor . ' }';
    } else {
      echo '.fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start { background-color: #f4f4f4; } ';
    }
  ?>
  .fc-direction-ltr .fc-daygrid-event .fc-event-time {
    display: none;
  }
</style>


<script>
    getHour = document.querySelectorAll(".fc-event-time").value;

    console.log(getHour);


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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

           <!--skydash-->
    <script src="skydash/vendor.bundle.base.js"></script>
    <script src="skydash/full-calendar.js"></script>
    <script src="skydash/moment-min.js"></script>
    <script src="skydash/off-canvas.js"></script>
    <script src="skydash/hoverable-collapse.js"></script>
    <script src="skydash/template.js"></script>
    <script src="skydash/settings.js"></script>
    <script src="skydash/todolist.js"></script>
     <script src="main.js"></script>
     <script src="skydash/calendar.js"></script>
    <script src="bootstrap js/data-table.js"></script>


    

  
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>