<?php
  session_start();
//   error_reporting(0);
  include 'config.php';
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

 

    
    $sql = "SELECT COUNT(*) AS employee_count FROM employee_tb WHERE classification != 3";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $employee_count = $row["employee_count"];

   include 'Data Controller/Dashboard/fetchHoliday.php'; //para sa pag fetch ng holidays using API
   include 'Data Controller/Dashboard/fetchCalendar.php';

// FOR ATTENDANCE AUTO REFRESHER ABSENT
    $_query_attendance = "SELECT * FROM attendances";
    $result_attendance = mysqli_query($conn, $_query_attendance);
    if(mysqli_num_rows($result_attendance) > 0){
        include ('Data Controller/Attendance/absent_refreshed.php'); // para mag generate ng automatic absent feature    
    }
// FOR ATTENDANCE AUTO REFRESHER ABSENT END
   


//     // for payroll holiday rule for holiday computations
   $query_check = "SELECT * FROM settings_tb";
   $result = mysqli_query($conn, $query_check);

   if(mysqli_num_rows($result) <= 0){
    $query = "INSERT INTO settings_tb (`holiday_pay`) VALUES ('Default')";
    $query_run = mysqli_query($conn, $query);      
   } 
//    // for payroll holiday rule for holiday computations END


   // may error
   $query = "SELECT * FROM settings_company_tb";
   $result = mysqli_query($conn, $query);

   if (mysqli_num_rows($result) <= 0){
    //    $row = mysqli_fetch_assoc($result);

       $query = "INSERT INTO settings_company_tb (`col_salary_settings`)
       VALUES ('Fixed Salary')";
       $query_run = mysqli_query($conn, $query);   
   }




// //CLASSIFICATION AUTO INSERT

//    //para sa holiday payroll computation kasi need na Regular ang employee para  may holiday pay
$query = "SELECT * FROM classification_tb WHERE classification = 'Regular'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0) {
    // Position does not exist, insert the new record
    $query = "INSERT INTO classification_tb (`id`, `classification`) VALUES (1, 'Regular')";
    $query_run = mysqli_query($conn, $query);    
} 


// //para sa holiday payroll computation kasi need na Regular ang employee para  may holiday pay
$query = "SELECT * FROM classification_tb WHERE classification = 'Internship/OJT'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0) {
    // Position does not exist, insert the new record
    $query = "INSERT INTO classification_tb (`id`, `classification`) VALUES (2, 'Internship/OJT')";
    $query_run = mysqli_query($conn, $query);    
} 


$query = "SELECT * FROM classification_tb WHERE classification = 'Pakyawan'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0) {
    // Position does not exist, insert the new record
    $query = "INSERT INTO classification_tb (`id`,`classification`) VALUES (3, 'Pakyawan')";
    $query_run = mysqli_query($conn, $query);    
} 


//error
$query = "SELECT * FROM positionn_tb WHERE position = 'Pakyawan'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0) {
    // Position does not exist, insert the new record
    $query = "INSERT INTO positionn_tb (`id`,`position`) VALUES (1,'Pakyawan')";
    $query_run = mysqli_query($conn, $query);    
} 


$query = "SELECT * FROM dept_tb WHERE col_deptname = 'Pakyawan'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) <= 0) {
    // Position does not exist, insert the new record
    $query = "INSERT INTO dept_tb (`col_ID`,`col_deptname`) VALUES (1,'Pakyawan')";
    $query_run = mysqli_query($conn, $query);    
} 

    mysqli_close($conn);
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/data .bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <!-- Link to the MDI CSS file -->
    <!-- <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css"> -->
    <!-- Import the MDI font files using @font-face -->
    <!-- inject:css -->
    <!-- <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css"> -->

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



<!-- skydash -->

<!-- <link rel="stylesheet" href="skydash/feather.css"> -->
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">

    <style>
    @font-face {
        font-family: 'Material Design Icons';
        font-style: normal;
        font-weight: 400;
        src: url('https://cdn.materialdesignicons.com/5.4.55/fonts/materialdesignicons-webfont.woff2?v=5.4.55') format('woff2'),
            url('https://cdn.materialdesignicons.com/5.4.55/fonts/materialdesignicons-webfont.woff?v=5.4.55') format('woff');
    }
    </style>
    <title>HRIS | Dashboard</title>
</head>
<body >
    <header>
        <?php
         include("header.php")
        ?>
    </header>
    <style>
        html{
            overflow: hidden !important;
        }
        .hhe{
            box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);
        }
        .scrollable-content::-webkit-scrollbar{
         width: 1px;
        }
        .scrollable-content::-webkit-scrollbar-thumb{
        background-color: #888;
         }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 1em;
            position: absolute !important;
            bottom: 10% !important;
        }
        /* .swiper-button-next:after {
            position: absolute !important;
            bottom: 10% !important;
            margin-top: 10% !important; */

            .announce-img{
                width: 70% !important;
            }
        
        #announce-modal a:hover{
            background-color: inherit;
            color: blue;
        }

        #event-modal a:hover{
            background-color: inherit;
            color: blue;
        }

        .highlight{
            background-color: #fff;
             box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.12);
             /* border-radius: 1em; */
        }

        .req-btn h5:hover{
            color: blue;
        }

        .req-btn:hover{
            color:blue;
        }

        .tables thead th:nth-child(2){
            
            width: 33% !important;
            
        }
        .tables tbody tr td:nth-child(2){
            
            width: 33% !important;
        }

        .tables thead th:nth-child(3){
            
            width: 43% !important;
        }
        .tables tbody tr td:nth-child(3){
            
            width: 43% !important;
        }
        .tables thead th:nth-child(4){
           
            width: 23% !important;
        }
        .tables tbody tr td:nth-child(4){
            
            width: 23% !important;
        }

        table thead{
            width: 100em !important;
        }

        #table_loan{
            width: 100% !important;
        }
        .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
        z-index: 999; /* Ensure overlay is on top */
    }

    .modals {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    z-index: 9999; /* Ensure modal is on top of the overlay */
    height: 33%;
    width: 25%;
    border-radius: 0.5em;
    animation: delayAndFadeIn 0.8s ease-in-out forwards; /* Delay and then fade in */
}

    

    
    /* Style for the close button */
    .modals .close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

    @keyframes delayAndFadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
    /* Bouncing animation for the icon */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }

    /* Apply the bouncing animation to the icon */
    .bouncing-icon {
        animation: bounce 2s infinite;
    }

    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        /* 15% {
            transform: rotate(180deg);
        }
        30%{
            transform: rotate(280deg);
        } */
        70%{
            transform: rotate(359deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Apply the rotation animation to the checkmark icon */
    #insertedModal .rotating-icon {
        animation: rotate 2.5s infinite; /* 0.5 seconds rotation + 3 seconds pause */
    }
    </style>

    
<!-------------------------------------------Modal of Announce Start Here--------------------------------------------->
<div class="modal fade" id="announcement_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Announcement</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Announcement/insert_announce.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php 
                                    error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        echo '0909090909';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Title</label>
                            <input type="text" name="announce_title" class="form-control" id="announce_title_id" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_announcement" class="form-label">Date</label>
                            <input type="date" name="announce_date" class="form-control" id="announce_date_id" required>
                        </div>

                        <div class="mb-3">
                            <label for="text_description" class="form-label">Description</label>
                            <textarea class="form-control" name="announce_description" id="announce_description_id"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="text_description" class="form-label">File Attachment</label>
                            <input type="file" name="file_upload" class="form-control" id="inputfile" >
                        </div>

                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_announcement" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Announce End Here---------------------------------------------> 



<!-------------------------------------------Modal of View Summary Start Here--------------------------------------------->
<div class="modal fade" id="view_summary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Summary of Announcement</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <table id="order-listing" class="table" style="width: 100%; max-height: 450px;">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Title</th>
                                <th>Details</th>
                                <th style="display: none;">View Button</th>
                                <th>Attachment</th>
                                <th class="d-none">ID</th>
                                
                            </tr>
                        </thead>
                        <?php
                            include 'config.php';
                            
                            try {
                                // Code that may throw an exception
                                // $result = 10 / 0; // This will throw a "Division by zero" exception
                            


                            $query = "SELECT
                            announcement_tb.id,
                            announcement_tb.announce_title,
                            employee_tb.empid,
                            CONCAT(
                                employee_tb.`fname`,
                                ' ',
                                employee_tb.`lname`
                            ) AS `full_name`,
                            announcement_tb.announce_date,
                            announcement_tb.description,
                            announcement_tb.file_attachment,
                            announcement_tb.date_file
                            FROM announcement_tb INNER JOIN employee_tb ON employee_tb.empid = announcement_tb.empid;";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['announce_date']?></td>
                                <td><?php echo $row['full_name']?></td>
                                <td><?php echo $row['announce_title']?></td>
                                <td style="display: none;"><?php echo $row['description']?></td>
                                <td><a href="" class="btn btn-primary showmodal" data-bs-toggle="modal" data-bs-target="#view_desc_modal">View</a></td>
                                <?php if(!empty($row['file_attachment'])): ?>
                                <td>
                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download">Download</button>
                                </td>
                                <td class="d-none"><?php echo $row['id'] ?></td>
                                <?php else: ?>
                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                               
                            </tr>
                        <?php
                                }

                                
                            }
                            catch (Exception $e) {
                                // Handle the exception
                                echo "An exception occurred: " . $e->getMessage();
                            }

                            
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div><!---Modal Body Close Tag-->

    </div>
  </div>
</div>
<!-------------------------------------------Modal of View Summary End Here--------------------------------------------->
    
<!---------------------------------------Download Modal Start Here -------------------------------------->
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
<!---------------------------------------Download Modal End Here --------------------------------------->

<!---------------------------------------View Modal Start Here -------------------------------------->
<div class="modal fade" id="view_desc_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Description</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label for="text_area" class="form-label"></label>
            <textarea class="form-control" name="text_description" id="view_description" readonly></textarea>
         </div>
      </div><!--Modal Body Close Tag-->

    </div>
  </div>
</div>
<!---------------------------------------View Modal End Here --------------------------------------->

<!-------------------------------------------Modal of Event Start Here--------------------------------------------->
<div class="modal fade" id="add_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Event/insert_event.php" method="POST" enctype="multipart/form-data">
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
      
       <form action="Data Controller/Holiday/insert_holiday.php" method="POST" enctype="multipart/form-data">
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
                            <input type="text" name="title_holiday" class="form-control" id="id_title_holiday" required>
                        </div>


                        <div class="mb-3">
                            <label for="event" class="form-label">Holiday Date</label>
                            <input type="date" name="date_holiday" class="form-control" id="id_holiday_date" required>
                            <p style="color: red" id="holidayMsg"></p>
                        </div>
                        
                       
                            
                        <div class="mb-3">
                            <label for="eventype" class="form-label">Type of Holiday</label>
                            <select class="form-select form-select-m" name="type_holiday" id="" aria-label=".form-select-sm example" style="height: 50px; cursor: pointer;">
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

    <script>                      
        let holidates = document.getElementById("id_holiday_date");
        let holidate_msg = document.getElementById("holidayMsg");
        let btn_save = document.getElementById("btn_save");

            holidates.addEventListener("change", function(){
            let holidate = holidates.value;
            console.log(holidate);

                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = this.responseText;
                    console.log(response);
                    holidate_msg.textContent = response;
                        if(response != ''){
                        btn_save.disabled = true;
                        }else{
                            btn_save.disabled = false;
                        }
                }
                        
                }

                xhr.open("POST", "holiday_validation.php", true);
                var formData = new FormData();
                formData.append("holidate", holidate);
                xhr.send(formData);


            });

                        
    </script>

<!-------------------------------- Modal of view all Holiday Start Here ----------------------->
<div class="modal fade" id="view_holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Holidays</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
            <table id="order-listing" class="table" style="width: 100%; " >
                <thead style="background-color: #ececec">
              
                    <tr> 
                        <th style= 'display: none;'> ID  </th>  
                        <th> Holiday Title </th>
                        <th> Holiday Date </th>
                        <th> Holiday Type </th>           
                        <th> Action </th>                     
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config.php';
                    
                    // Query the department table to retrieve department names
                    $dept_query = "SELECT * FROM holiday_tb Order By `date_holiday`";
                    $dept_result = mysqli_query($conn, $dept_query);
                    
                    // Generate the HTML table header
                    
                    // Loop over the departments and count the employees
                    while ($holiday_row = mysqli_fetch_assoc($dept_result)) {
                        $holiday_id = $holiday_row['id'];
                        $holiday_name = $holiday_row['holiday_title'];
                        $date_holiday = $holiday_row['date_holiday'];
                        $holiday_type = $holiday_row['holiday_type'];
                        $remarks = $holiday_row['remarks'];
                        
                        // Generate the HTML table row
                        echo "<tr data-holiday-id='$holiday_id'>
                                <td style='display: none;'>$holiday_id</td>
                                <td>$holiday_name</td>
                                <td>$date_holiday</td>
                                <td>$holiday_type</td>
                                <td>";
                                    echo "
                                    <button type='button' class='border-0 edit' title='Working days' data-bs-toggle='modal' data-bs-target='#edit' style='background: transparent; font-size: 20px; margin-right: 10px;' >
                                        <i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i>
                                    </button>
                                    
                                    ";
                                    
                                    echo "
                                </td>
                                
                        </tr>";
                    }
                    ?>
                </tbody>

            </table>        
        </div> <!--table my-3 end--> 
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>
<!--------------------------- Modal of view all Holiday Start Here ---------------------------------->


<!-------------------------------------------------------------------EDIT  MODAL -------------------------------------------------------->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Holiday</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

            <div class="modal-body">

                <input type="hidden" name="id_holiday" id="id_modall">
                <input type="hidden" name="holiday_title" id="hol_title">

                <div class="mb-4">
                    <label for="name_employee_fname" class="form-label">Date :</label>
                        <div class="input-group">
                            <input type="date" name="date" id="HolidayDate_id" class="form-control bg-light">
                        </div>
                </div>

                <div class="mb-4">
                    <label for="eventype" class="form-label">Type of Day</label>
                    <select class="form-select form-select-m" onchange="holiday_change()" name="type_day" id="type_id" aria-label=".form-select-sm example" style="height: 50px; cursor: pointer;">
                        <option value="" selected disabled>Select Holiday</option>
                        <option value="Regular Working Day">Regular Working Day</option>
                        <option value="Holiday">Holiday</option>                       
                    </select>
                </div>

                <div class="mb-4 div_type_holiday" style = 'display: none ;'>
                    <label for="eventype" class="form-label">Type of Holiday</label>
                    <select class="form-select form-select-m" name="type_holiday" id="type_holiday_id" aria-label=".form-select-sm example" style="height: 50px; cursor: pointer;">
                        <option value="Regular Working Day">Regular Working Day</option>
                        <!-- <option value="Regular Holiday">Regular Holiday</option>
                        <option value="Special Non-Working Holiday">Special Non-Working Holiday</option>  
                        <option value="Special Working Holiday">Special Working Holiday</option>                        -->
                    </select>
                </div>
            </div> 
            <div class="modal-footer">
                <button type="button" name="to_workingday_name" id="id_approved" class="btn btn-primary">Yes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </div>



    </div>
  </div>
</div>

<script>
  function holiday_change() {
    let type_day = document.getElementById('type_id').value;
    let div_type_holiday = document.querySelector('.div_type_holiday');

   

    var selectElement = document.getElementById("type_holiday_id");

    // Remove all existing options (optional, if you want to start with a clean slate)
    selectElement.innerHTML = "";

  

    if (type_day === 'Regular Working Day') {
      div_type_holiday.style.display = 'none';

      var newOptions = [
                { value: "Regular Working Day", text: "Regular Working Day" },
               
            ];

            newOptions.forEach(function (optionData) {
            var option = document.createElement("option");
            option.value = optionData.value;
            option.text = optionData.text;
            selectElement.appendChild(option);
        });
    } else {
      div_type_holiday.style.display = 'block';

        // Create new options and add them to the select element
            var newOptions = [
                { value: "Regular Holiday", text: "Regular Holiday" },
                { value: "Special Non-Working Holiday", text: "Special Non-Working Holiday" },
                { value: "Special Working Holiday", text: "Special Working Holiday" },
            ];

            newOptions.forEach(function (optionData) {
            var option = document.createElement("option");
            option.value = optionData.value;
            option.text = optionData.text;
            selectElement.appendChild(option);
        });
    }
  }


  $(document).ready(function () {
  $('#id_approved').click(function (event) {
    event.preventDefault();

    var formData = $('#edit').find('input, select').serialize();

    $.ajax({
      type: 'POST',
      url: 'actions/Holiday/update_allow.php',
      data: formData,
      dataType: 'json',
      success: function (response) {
        // Check if the response contains data and if it has the expected properties
        if (response && response.id && response.date_holiday && response.holiday_type) {
          // Get the holiday ID from the updated row
          var updatedHolidayId = response.id;

          // Update the corresponding row in the HTML table
          var rowToUpdate = $('#order-listing tr[data-holiday-id="' + updatedHolidayId + '"]');
          rowToUpdate.find('td:eq(2)').text(response.date_holiday);
          rowToUpdate.find('td:eq(3)').text(response.holiday_type);

          // Close the modal after successful update
          $('#edit').modal('hide');
        } else {
          console.error('Unexpected response format:', response);
        }
      },
      error: function (error) {
        console.error('AJAX Error:', error);
      }
    });
  });
});


</script>
<!--------------------------------------------------- EDIT MODAL  ------------------------------------------------------------------->

<!-- View Event -->
                   
<div class="modal fade" id="view_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl" >
            <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Events</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    
            <div class="table-responsive mt-2" style="overflow-x:hidden; height: 18.75em;">
                <table id="order-listing" class="table" >
                    <thead style="background-color: #ececec;">
                        <tr>
                            <th class='d-none'>id</th>
                            <th>Event Title</th>
                            <th>Event Type</th>
                            <th>Event Start</th>
                            <th>Event End</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php 
                        include 'config.php';

                        $sql = "SELECT * FROM event_tb ORDER BY `start_date` DESC";
                        $result = mysqli_query($conn, $sql);

                        while($row = mysqli_fetch_assoc($result)){
                            $id = $row['id'];
                            $title = $row['event_title'];
                            $type = $row['event_type'];
                            $start_date = $row['start_date'];
                            $end_date = $row['end_date'];

                            echo "<tr>";
                            echo "<td class='d-none'>$id</td>";
                            echo "<td style='font-weight: 400'>$title</td>";
                            echo "<td style='font-weight: 400'>$type</td>";
                            echo "<td style='font-weight: 400'>$start_date</td>";
                            echo "<td style='font-weight: 400'>$end_date</td>";
                            echo "<td>
                            <button type='button' class='border-0 edit_event' data-bs-toggle='modal' data-bs-target='#edit_event' id='open_edit_event_$id'> <i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i> </button>
                            
                            <button class='deletebtn'  title = 'Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style='border: none; background-color: inherit' id='delete_event_$id'> <i class='fa-sharp fa-solid fa-trash' style='font-size: 1.4em'></i> </button>
                            </td>";
                            echo "</tr>";
                        }
                        ?>


                </table>
            </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Understood</button> -->
            </div>
            </div>
        </div>
    </div>

    <!-- edit event -->
    <div class="modal fade" id="edit_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close_event"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form action="Data Controller/Event/update_event.php" method="POST">
                        <input type="hidden" name="id" id="id">

                        <label for="">Event Title</label><br>
                        <input type="text" class="form-control" name="event_title" id="title" readonly><br>

                        <label for="">Event Type</label><br>
                        <input type="text" class="form-control" name="event_type" id="type"><br>

                        <label for="">Start Date</label><br>
                        <input type="datetime-local" class="form-control" name="start_date" id="start"><br>

                        <label for="">End Date</label><br>
                        <input type="datetime-local" class="form-control" name="end_date" id="end">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close_event">Close</button>
                <button type="submit" name="update_event" class="btn btn-primary">Update</button>
                    </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="Data Controller/Event/delete_event.php" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" id="delete_id">
                <input type="hidden" name="event_title" id="delete_title">
            

                <h4>Do you want to delete?</h4>

            </div> <!--Modal body div close tag-->
            <div class="modal-footer">
            
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
            </div>
            </form>


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Add a click event handler for all buttons with the class 'edit_event'
            $(".edit_event").on("click", function () {
                // Get the unique ID of the clicked button
                var buttonId = $(this).attr("id");
                // Extract the ID from the button's ID
                var eventId = buttonId.split("_")[2];
                // Close the first modal
                $("#view_event").modal("hide");
                // Open the second modal with the corresponding ID
                $("#edit_event").modal("show");
                
                // You can use 'eventId' to fetch data for the specific event and populate the second modal.
            });

            // When the second modal is closed, show the first modal
            $('#edit_event').on('hidden.bs.modal', function (e) {
                $("#view_event").modal("show");
            });
        });

        
    </script>

    <script>
        $(document).ready(function () {
            // Add a click event handler for all buttons with the class 'edit_event'
            $(".deletebtn").on("click", function () {
                // Get the unique ID of the clicked button
                var buttonId = $(this).attr("id");
                // Extract the ID from the button's ID
                var eventId = buttonId.split("_")[2];
                // Close the first modal
                $("#view_event").modal("hide");
                // Open the second modal with the corresponding ID
                $("#deletemodal").modal("show");
                
                // You can use 'eventId' to fetch data for the specific event and populate the second modal.
            });

            // When the second modal is closed, show the first modal
            $('#deletemodal').on('hidden.bs.modal', function (e) {
                $("#view_event").modal("show");
            });
        });


    </script>





              
                            <!-- Modal of view all Present Employee Start Here --------------------------------------->
                            <div class="modal fade" id="IDmodal_ViewPresent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Present Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; " >
                                                    <thead style="background-color: #ececec">   
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT * FROM attendances 
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                        WHERE attendances.status = 'Present' AND employee_tb.classification != 3 AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Present Employee End Here ---------------------------------->

                              <!-- Modal of view all Absent Employee Start Here --------------------------------------->
                              <div class="modal fade" id="IDmodal_ViewAbsent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Absent Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT * FROM attendances 
                                                                            INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                                            WHERE attendances.status = 'Absent' AND employee_tb.classification != 3 AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];     

                                                            if(empty($time_in) || $time_in == NULL){
                                                                $time_in = '00:00:00';
                                                            }
                                                            
                                                            if(empty($time_out) || $time_out == NULL){
                                                                $time_out = '00:00:00';
                                                            }
                                                            
                                                            if(empty($late) || $late == NULL){
                                                                $late = '00:00:00';
                                                            }                                                      
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Absent Employee End Here ---------------------------------->

                             <!-- Modal of view all On-Leave Employee Start Here --------------------------------------->
                             <div class="modal fade" id="IDmodal_ViewOnleave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">On-Leave Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT * FROM attendances 
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                        WHERE attendances.status = 'On-Leave' AND employee_tb.classification != 3 AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all On-Leave Employee End Here ---------------------------------->

                            <!-- Modal of view all Working Home Employee Start Here --------------------------------------->
<div class="modal fade" id="IDmodal_ViewWFH" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Employees Working Home</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" id="wfh_table" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody id="wfh_table_body">
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');
                
                
                
                
                                                        // Run code 
                                                        $query_approver = "SELECT empid from approver_tb WHERE approver_empid = '$approver_ID'";
                                                        $result_approver = mysqli_query($conn, $query_approver);
                                                        
                                                        // Check if any rows are fetched
                                                        if (mysqli_num_rows($result_approver) > 0) {
                                                            $empid_Assigned = array(); // Array to store the employee assigned to the log in supervisor
                                                        
                                                            // Loop through each row
                                                            while($row = $result_approver->fetch_assoc()) 
                                                            {
                                                                $empid = $row['empid'];
                                                                                                    
                                                                $empid_Assigned[] = array('empid' => $empid);
                                                            }
                
                                                            $employeeWFH_bool = false;
                                                            
                                                            
                
                                                            
                                                            foreach ($empid_Assigned as $empid_Assign) {
                                                                $timestamp = strtotime($currentDate);
                                                                $today = date("l", $timestamp);
                                                                $emp_array_ID =  $empid_Assign['empid'];

                                                                $sql = "SELECT * FROM wfh_tb WHERE `empid` = '$emp_array_ID' AND `date` = '$currentDate' ";
                                                                $result = mysqli_query($conn, $sql);
                                                                if(mysqli_num_rows($result) > 0){
                                                                    $row = mysqli_fetch_assoc($result);

                                                                    $status = $row['status'];
                                                                    $empids = $row['empid'];
                                                                    $wfh_date = $row['date'];

                                                                    if($status == 'Approved'){
                                                                       

                                                                        date_default_timezone_set('Asia/Manila');
                                                                        $currentDate = date('Y-m-d');

                                                                        $sql = "SELECT * FROM `employee_tb`
                                                                        INNER JOIN `attendances` ON `employee_tb`.`empid` = `attendances`.`empid` 
                                                                        WHERE `attendances`.`date` = '$currentDate' AND `attendances`.`empid` = '$empids' ";
                                                                        $result = mysqli_query($conn, $sql);
                                                                        $row = mysqli_fetch_assoc($result);

                                                                        $empidss = $row['empid'];
                                                                        $fullname = $row['fname'] . " " . $row['lname'];
                                                                        $time_in = $row['time_in'];
                                                                        $time_out = $row['time_out'];
                                                                        $status = $row['status'];
                                                                        $late = $row_emp_tb['late'];

                                                                        if(empty($time_in)){
                                                                            $time_in = '00:00:00';
                                                                        }else{
                                                                            $time_in = $time_in;
                                                                        }

                                                                        if(empty($time_out)){
                                                                            $time_out = '00:00:00';
                                                                        }else{
                                                                            $time_out = $time_out;
                                                                        }

                                                                        if(empty($late)){
                                                                            $late = '00:00:00';
                                                                        }else{
                                                                            $late = $late;
                                                                        }


                                                                        echo "<tr>
                                                                                <td style='font-weight: 400 '>$status</td>
                                                                                <td style='font-weight: 400 '>$empidss</td>
                                                                                <td style='font-weight: 400 '>$fullname</td>
                                                                                <td style='font-weight: 400 '>$time_in</td>
                                                                                <td style='font-weight: 400 '>$time_out</td>
                                                                                <td style='font-weight: 400 '>$late</td>                                                                          
                                                                            </tr>";

                                                                            

                                                                    }else{
                                                                        
                                                                          
                                                                    } 
                                                                }else{
                                                                    $timestamp = strtotime($currentDate);
                                                                    $today = date("l", $timestamp);
                                                                    // $emp_array_ID =  $empid_Assign['empid'];
    
                                                                    $query_empSched = "SELECT * FROM empschedule_tb
                                                                                    INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                                                                    WHERE empschedule_tb.empid = '$emp_array_ID' 
                                                                                    AND empschedule_tb.sched_from <= '$currentDate' 
                                                                                    AND empschedule_tb.sched_to >= '$currentDate'";
                                                                    $result_empSched = mysqli_query($conn, $query_empSched);
    
                                                                    if (mysqli_num_rows($result_empSched) > 0) {
                                                                        $row_empSched = mysqli_fetch_assoc($result_empSched);
    
                                                                        // Modify the condition to use logical AND (&&) and strict comparison (===)
                                                                        if ($today === 'Monday' && ($row_empSched['mon_wfh'] !== NULL && $row_empSched['mon_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Tuesday' && ($row_empSched['tues_wfh'] !== NULL && $row_empSched['tues_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Wednesday' && ($row_empSched['wed_wfh'] !== NULL && $row_empSched['wed_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Thursday' && ($row_empSched['thurs_wfh'] !== NULL && $row_empSched['thurs_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Friday' && ($row_empSched['fri_wfh'] !== NULL && $row_empSched['fri_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Saturday' && ($row_empSched['sat_wfh'] !== NULL && $row_empSched['sat_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else if ($today === 'Sunday' && ($row_empSched['sun_wfh'] !== NULL && $row_empSched['sun_wfh'] !== '')) {
                                                                            $employeeWFH_bool = true;
                                                                        } else{
                                                                            $employeeWFH_bool = false;
                                                                        }
    
                                                                        if ($employeeWFH_bool === true) {
                                                                            $empid = $row_empSched['empid'];
                                                                            
                                                                            $query_emp_tb = "SELECT 
                                                                                               attendances.status,
                                                                                               attendances.empid,
                                                                                               employee_tb.fname,
                                                                                               employee_tb.lname,
                                                                                               attendances.time_in,
                                                                                               attendances.time_out,
                                                                                               attendances.late                                                
                                                                                            FROM attendances
                                                                                            INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                                                            WHERE DATE(attendances.`date`) = '$currentDate' AND attendances.empid = '$empid'";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);
    
                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);
                                                                            $empidss =  $row_emp_tb['empid'];
                                                                            $status = $row_emp_tb['status'];
                                                                            $fullname = $row_emp_tb['fname'] . " " . $row_emp_tb['lname'];
                                                                            $time_in = $row_emp_tb['time_in'];
                                                                            $time_out = $row_emp_tb['time_out'];
                                                                            $late = $row_emp_tb['late'];

                                                                            if(empty($time_in)){
                                                                                $time_in = '00:00:00';
                                                                            }else{
                                                                                $time_in = $time_in;
                                                                            }
    
                                                                            if(empty($time_out)){
                                                                                $time_out = '00:00:00';
                                                                            }else{
                                                                                $time_out = $time_out;
                                                                            }
    
                                                                            if(empty($late)){
                                                                                $late = '00:00:00';
                                                                            }else{
                                                                                $late = $late;
                                                                            }
                                                                            echo "<tr>
                                                                                    <td style='font-weight: 400'>$status</td>
                                                                                    <td style='font-weight: 400'>$empidss</td>
                                                                                    <td style='font-weight: 400'>$fullname</td>
                                                                                    <td style='font-weight: 400'>$time_in</td>
                                                                                    <td style='font-weight: 400'>$time_out</td>
                                                                                    <td style='font-weight: 400'>$late</td>                                                                          
                                                                                </tr>";
                                                                        } 
                                                                    }

                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                           
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Working Home Employee End Here ---------------------------------->

                            <!-- Modal of view all LATE Employee Start Here --------------------------------------->
<div class="modal fade" id="IDmodal_ViewLate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Employees with Late</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = " SELECT * FROM attendances 
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                        WHERE `late` != '00:00:00' AND `late` != ''  AND employee_tb.classification != 3 AND DATE(`date`) = '$currentDate'
                                                                       ";
                                                                        
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all LAte Employee End Here ---------------------------------->
    
    <div class="dashboard-container" style="position: absolute; width:82%; height: 100%; top: 9%; left: 16% ">
        <div class="dashboard-content pl-4 pr-4 d-flex flex-column justify-content-between mx-auto " style="width: 97%; height: 98%;">
            <div class="dashboard-title  d-flex justify-content-between flex-row align-items-center p-2" style="height: 7%;">
                <div>
                    <h3 class="fs-2">DASHBOARD</h3>
                </div>
                <div>
                   <?php 
                   date_default_timezone_set('Asia/Manila'); // Set the time zone to Philippines

                   $today = date('l, j F Y'); 
                   ?>
                   <h5 class="fs-4" style="font-weight: 500"><?php echo $today; ?></h5>
                </div>
            </div>
            <div class="dashboard-overview d-flex align-items-center justify-content-center" style="height: 20%;">
                <div class="bg-white rounded d-flex flex-column justify-content-between" style="height: 100%; width: 100%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                    <div class="w-100 p-3" style="height: 25%">
                        <p class="fs-5" style="font-weight: 600">Employee Status Overview</p>
                    </div>
                    <div class="w-100" style="height: 75%">
                        <div class="w-100 h-100 mx-auto d-flex flex-row justify-content-between">
                            <!-- Present -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center">
                                <div style="width: 80%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); cursor: pointer;" class="border rounded mb-2 d-flex flex-column justify-content-between" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewPresent">
                                    <?php 
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila');
                                            
                                        // Get the current date in Manila, Philippines
                                        $currentDate = date('Y-m-d');
                                            
                                        // Query to count the employees with a "Present" status for the current date
                                        $query = "SELECT COUNT(*) AS employee_count FROM attendances 
                                                INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                WHERE attendances.status = 'Present' AND 
                                                    employee_tb.classification != 3 AND
                                                DATE(`date`) = '$currentDate'";
                                        $result = mysqli_query($conn, $query);
                                            
                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $employeePresent = $row['employee_count'];
                                        } else {
                                            $employeePresent = 0;
                                        }
                                    ?>
                                    <div class="w-100 d-flex align-items-center pl-4 pt-1" style="height:40%">
                                        <p style="color: green; font-weight: 400; font-size: 23px; margin-top: 0.3em">Active</p>
                                    </div>
                                    <div class="w-100 pl-4" style="height:60%">
                                        <div class="d-flex flex-row position-relative w-100 h-100 d-flex flex-row justify-content-between">
                                            <div class="w-25 h-100">
 
                                            </div>
                                            <div class="w-75 h-100 d-flex flex-row justify-content-between pr-4 pl-3">
                                                <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em; cursor: pointer" name="present" value="<?php echo $employeePresent; ?>" readonly>
                                                <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <!-- Absent -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center  ">
                                <div style="width: 80%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); cursor:pointer" class="border rounded mb-2" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewAbsent">
                                    <?php 
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        
                                        // Get the current date in Manila, Philippines
                                        $currentDate = date('Y-m-d');
                                        
                                        // Query to count the employees with a "Present" status for the current date
                                        $query = "SELECT COUNT(*) AS employee_absent FROM attendances
                                                  INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                  WHERE attendances.status = 'Absent' AND 
                                                  employee_tb.classification != 3 AND
                                                  DATE(`date`) = '$currentDate'";
                                        $result = mysqli_query($conn, $query);
                                        
                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $employeeAbsent = $row['employee_absent'];
                                        } else {
                                            $employeeAbsent = 0;
                                        }
                                    ?>

                                    <div class="w-100 d-flex align-items-center pl-4 pt-1" style="height:40%">
                                        <p style="color: red; font-weight: 400; font-size: 23px; margin-top: 0.3em">Absent</p>
                                    </div>
                                    <div class="w-100 pl-4" style="height:60%">
                                        <div class="d-flex flex-row position-relative w-100 h-100 d-flex flex-row justify-content-between">
                                            <div class="w-25 h-100">

                                            </div>
                                            <div class="w-75 h-100 d-flex flex-row justify-content-between pr-4 pl-3">
                                                <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em; cursor:pointer" name="present" value="<?php echo $employeeAbsent; ?>" readonly>
                                                <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <!-- On Leave -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center ">
                                <div style="width: 80%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); cursor:pointer" class="border rounded mb-2" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewOnleave">
                                    <?php 
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        
                                        // Get the current date in Manila, Philippines
                                        $currentDate = date('Y-m-d');
                                        
                                        // Query to count the employees with a "Present" status for the current date
                                        $query = "SELECT COUNT(*) AS employee_onleave FROM attendances 
                                                 INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                WHERE attendances.status = 'On-Leave' AND 
                                                employee_tb.classification != 3 AND 
                                                DATE(`date`) = '$currentDate'";
                                        $result = mysqli_query($conn, $query);
                                        
                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $employeeOnleave = $row['employee_onleave'];
                                        } else {
                                            $employeeOnleave = 0;
                                        }
                                    ?>
                                    <div class="w-100 d-flex align-items-center pl-4 pt-1" style="height:40%">
                                        <p style="color: gray; font-weight: 400; font-size: 23px; margin-top: 0.3em">On Leave</p>
                                    </div>
                                    <div class="w-100 pl-4" style="height:60%">
                                        <div class="d-flex flex-row position-relative w-100 h-100 d-flex flex-row justify-content-between">
                                            <div class="w-25 h-100">

                                            </div>
                                            <div class="w-75 h-100 d-flex flex-row justify-content-between pr-4 pl-3">
                                                <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em; cursor:pointer" name="present" value="<?php echo $employeeOnleave; ?>" readonly>
                                                <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Working home  -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center ">
                                <div style="width: 80%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); cursor:pointer " class="border rounded mb-2" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewWFH">
                                    <?php 
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        
                                        // Get the current date in Manila, Philippines
                                        $currentDate = date('Y-m-d');
                                        
                                        // Query to count the employees with a "Present" status for the current date
                                        $query = "SELECT COUNT(*) AS employee_wfh FROM empschedule_tb WHERE `schedule_name` = 'Wfh' AND empschedule_tb.sched_from <= '$currentDate' AND empschedule_tb.sched_to >= '$currentDate'";
                                        $result = mysqli_query($conn, $query);
                                        
                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $employeeWFH = $row['employee_wfh'];
                                        } else {
                                            $employeeWFH = 0;
                                        }

                                        
                                    ?>
                                    <div class="w-100 d-flex align-items-center pl-4 pt-1" style="height:40%">
                                        <p style="color: #2087C1; font-weight: 400; font-size: 23px; margin-top: 0.3em">Working Home</p>
                                    </div>
                                    <div class="w-100 pl-4" style="height:60%">
                                        <div class="d-flex flex-row position-relative w-100 h-100 d-flex flex-row justify-content-between">
                                            <div class="w-25 h-100">
                                                
                                            </div>
                                            <div class="w-75 h-100 d-flex flex-row justify-content-between pr-4 pl-3">
                                                <span id="wfh_count" style="margin-top: 3px; font-size: 2.8em;  cursor:pointer; "><?php echo $employeeWFH ?></span>
                                                <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <script>
                                    // JavaScript to count and display the number of <tr> tags in the tbody
                                    const tbodies = document.getElementById('wfh_table_body');
                                    const countSpans = document.getElementById('wfh_count');

                                    if (tbodies && countSpans) {
                                        const trCount = tbodies.getElementsByTagName('tr').length;
                                        countSpans.textContent = trCount;
                                    }
                                </script>
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center  ">
                                <div style="width: 80%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); cursor:pointer" class="border rounded mb-2" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewLate">
                                    <?php
                                    include 'config.php';
                                    date_default_timezone_set('Asia/Manila');

                                    // Get the current date in Manila, Philippines
                                    $currentDate = date('Y-m-d');

                                    $query = "SELECT COUNT(*) AS employee_late FROM attendances
                                    INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                    WHERE attendances.status != '00:00:00' AND `late` != '' AND 
                                    employee_tb.classification != 3 AND 
                                    DATE(`date`) = '$currentDate'";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        $employeeLate = $row['employee_late'];
                                    } else {
                                        $employeeLate = 0;
                                    }
                                    ?>
                                    <div class="w-100 d-flex align-items-center pl-4 pt-1" style="height:40%">
                                        <p style="color: Orange; font-weight: 400; font-size: 23px; margin-top: 0.3em">Late</p>
                                    </div>
                                    <div class="w-100 pl-4" style="height:60%">
                                        <div class="d-flex flex-row position-relative w-100 h-100 d-flex flex-row justify-content-between">
                                            <div class="w-25 h-100">

                                            </div>
                                            <div class="w-75 h-100 d-flex flex-row justify-content-between pr-4 pl-3">
                                                <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em; cursor:pointer" name="present" value="<?php echo $employeeLate; ?>" readonly>
                                                <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-body w-100 pt-2" style="height:73%; ">
                <div class="w-100 h-100  mt-2 d-flex flex-row justify-content-between">
                    <div class="bg-white rounded" style="width: 55%; height:81%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                        <div class="w-100 h-100 p-4">
                            <div class="request-title w-100 d-flex align-items-center" style="height: 8%; margin-bottom: 0.5em">
                                <h4 class="fs-4">Employee Request</h4>
                            </div>
                            <div class="request-button w-100" style="height: 13%">
                                <div class="request-button-container border border-secondary rounded d-flex flex-row justify-content-between align-items-center pl-2 pr-2" style="height: 100%; background-color: #cececece; width: 98.8% ">
                                    <div class="req-btn rounded d-flex flex-row align-items-center highlight p-2 justify-content-around" style="height: 80%; width: 22%; cursor: pointer;" id="request">
                                        <h5>All Request </h5>
                                        <div class="border d-flex flex-row align-items-center justify-content-center mb-2 rounded" style="height: 1.7em; width: 1.7em">
                                            <span id="request_count" style="color: blue">
                                            <?php
                                                $sql = "
                                                    SELECT COUNT(*) AS total_requests
                                                    FROM (
                                                        SELECT applyleave_tb.col_ID
                                                        FROM employee_tb
                                                        INNER JOIN applyleave_tb ON employee_tb.empid = applyleave_tb.col_req_emp
                                                        WHERE applyleave_tb.col_status = 'Pending'
                                                        
                                                        UNION ALL
                                                        
                                                        SELECT overtime_tb.id
                                                        FROM employee_tb
                                                        INNER JOIN overtime_tb ON employee_tb.empid = overtime_tb.empid
                                                        WHERE overtime_tb.status = 'Pending'
                                                        
                                                        UNION ALL
                                                        
                                                        SELECT undertime_tb.id
                                                        FROM employee_tb
                                                        INNER JOIN undertime_tb ON employee_tb.empid = undertime_tb.empid
                                                        WHERE undertime_tb.status = 'Pending'
                                                        
                                                        UNION ALL
                                                        
                                                        SELECT wfh_tb.id
                                                        FROM employee_tb
                                                        INNER JOIN wfh_tb ON employee_tb.empid = wfh_tb.empid
                                                        WHERE wfh_tb.status = 'Pending'
                                                        
                                                        UNION ALL
                                                        
                                                        SELECT emp_official_tb.id
                                                        FROM employee_tb
                                                        INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id
                                                        WHERE emp_official_tb.status = 'Pending'
                                                        
                                                        UNION ALL
                                                        
                                                        SELECT emp_dtr_tb.id
                                                        FROM employee_tb
                                                        INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid
                                                        WHERE emp_dtr_tb.status = 'Pending'
                                                    ) AS requests";

                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        $row = $result->fetch_assoc();
                                                        echo $row['total_requests'];
                                                    } else {
                                                        echo 0;
                                                    }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="req-btn rounded d-flex flex-row align-items-center p-2 justify-content-center" style="height: 80%; width: 17%; cursor: pointer;" id="leave">
                                        <h5>Leave</h5>
                                    </div>
                                    <div class="req-btn rounded d-flex flex-row align-items-center p-2 justify-content-center" style="height: 80%; width: 17%; cursor: pointer;" id="loan">
                                        <h5>Loan</h5>   
                                    </div>
                                    <div class="req-btn rounded d-flex flex-row align-items-center p-2 justify-content-center" style="height: 80%; width: 17%; cursor: pointer;" id="under">
                                        <h5>Undertime</h5>
                                    </div>
                                    <div class="req-btn rounded d-flex flex-row align-items-center p-2 justify-content-center" style="height: 80%; width: 17%; cursor: pointer;" id="over">
                                        <h5>Overtime</h5>   
                                    </div>
                                </div>
                            </div>

                            <div class="w-100 pt-3 pl-2 pr-2" style="height: 75%">
                                <div class="w-100 h-100 table-responsive border rounded tables-container " id="table-responsive" style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 6px 0 rgba(0, 0, 0, 0.1);">
                                    <table class="table table-borderless p-0 scrollable-content tables" style="width: 100%; overflow-y: scroll; height: 100%; display:table;" id="table_request">
                                        <thead class="" style="background-color: #cecece; border-bottom-left-radius: 10px; width: 100%">
                                            <th class="d-none">ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody style="width: 100%">
                                       
                                        <?php 
                                             include 'config.php';

                                             $sql = "
                                             SELECT
                                             CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                             positionn_tb.position AS Position,
                                             dept_tb.col_deptname AS Department,
                                             request_data.col_ID AS col_ID,
                                             request_data.col_req_emp AS col_req_emp,
                                             request_data.datefiled AS datefiled,
                                             request_data.col_status AS col_status,
                                             request_data.request_type AS request_type
                                         FROM employee_tb
                                         INNER JOIN (
                                             SELECT
                                                 applyleave_tb.col_ID,
                                                 applyleave_tb.col_req_emp,
                                                 applyleave_tb.col_strDate AS datefiled,
                                                 applyleave_tb.col_status,
                                                 'Leave Request' AS request_type
                                             FROM applyleave_tb
 
                                             UNION
 
                                             SELECT
                                                 overtime_tb.id AS col_ID,
                                                 overtime_tb.empid AS col_req_emp,
                                                 overtime_tb.work_schedule AS datefiled,
                                                 overtime_tb.status AS col_status,
                                                 'Overtime Request' AS request_type
                                             FROM overtime_tb
 
                                             UNION
 
                                             SELECT
                                                 undertime_tb.id AS col_ID,
                                                 undertime_tb.empid AS col_req_emp,
                                                 undertime_tb.date AS datefiled,
                                                 undertime_tb.status AS col_status,
                                                 'Undertime Request' AS request_type
                                             FROM undertime_tb
 
                                             UNION
 
                                             SELECT
                                                 wfh_tb.id AS col_ID,
                                                 wfh_tb.empid AS col_req_emp,
                                                 wfh_tb.date AS datefiled,
                                                 wfh_tb.status AS col_status,
                                                 'WFH Request' AS request_type
                                             FROM wfh_tb
 
                                             UNION
 
                                             SELECT
                                                 emp_official_tb.id AS col_ID,
                                                 emp_official_tb.employee_id AS col_req_emp,
                                                 emp_official_tb.str_date AS datefiled,
                                                 emp_official_tb.status AS col_status,
                                                 'Official Business' AS request_type
                                             FROM emp_official_tb
 
                                             UNION
 
                                             SELECT
                                                 emp_dtr_tb.id AS col_ID,
                                                 emp_dtr_tb.empid AS col_req_emp,
                                                 emp_dtr_tb.date AS datefiled,
                                                 emp_dtr_tb.status AS col_status,
                                                 'DTR Request' AS request_type
                                             FROM emp_dtr_tb
                                         ) AS request_data ON employee_tb.empid = request_data.col_req_emp
                                         INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                         INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                         WHERE request_data.col_status = 'Pending'";

                                         $result = $conn->query($sql);

                                         if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                        echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . $row['col_ID'] . "</span></td>";
                                                        echo "<td style='font-weight: 500; font-size: 14px' class='mb-2'>" . $row['full_name'] . " <br> <span style='color:gray; font-size: 0.835em; font-weight: 700'>". $row['Position'] ."</td>";
                                                        echo "<td style='font-weight: 400; font-size: 14px'>" . $row['request_type'] . "</td>";
                                                        echo "<td><button type='submit' name='view_data' class='btn btn-primary viewrequest'>View</button></td>";
                                                    echo "</tr>";
                                            }
                                         }
                                        
                                        ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-borderless p-0 scrollable-content tables" style="width: 100%; overflow-y: scroll; height: 100%; display:none" id="table_leave">
                                        <thead class="" style="background-color: #cecece; border-bottom-left-radius: 10px;width: 100%">
                                            <th class="d-none">ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody style="width: 100%">
                                       
                                        <?php 
                                             include 'config.php';

                                             $sql = "SELECT * FROM applyleave_tb 
                                             INNER JOIN employee_tb ON `applyleave_tb`.`col_req_emp` = `employee_tb`.`empid`
                                             INNER JOIN positionn_tb ON `employee_tb`.`empposition` = `positionn_tb`.`id`
                                             WHERE `applyleave_tb`.`col_status` = 'Pending'";


                                         $result = $conn->query($sql);

                                         if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                    echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . @$row['col_ID'] . "</span></td>";
                                                    echo "<td style='font-weight: 500; font-size: 14px' class='mb-2'>" . $row['fname'] . " " . $row['lname'] . " <br> <span style='color:gray; font-size: 0.835em; font-weight: 700'>". $row['position'] ."</td>";
                                                    echo "<td style='font-weight: 400; font-size: 14px'>" . $row['col_LeaveType'] . "</td>";
                                                    echo "<td><button type='submit' name='view_data' class='btn btn-primary viewrequest'>View</button></td>";
                                                echo "</tr>";

                                            }
                                         }
                                        
                                        ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-borderless p-0 scrollable-content tables" style="width: 100%; overflow-y: scroll; height: 100%; display:none" id="table_loan">
                                        <thead class="" style="background-color: #cecece; border-bottom-left-radius: 10px;width: 100%">
                                            <th class="d-none">ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody style="width: 100%">
                                       
                                        <?php 
                                             include 'config.php';

                                             $sql = "SELECT * FROM payroll_loan_tb 
                                             INNER JOIN employee_tb ON `payroll_loan_tb`.`empid` = `employee_tb`.`empid`
                                             INNER JOIN positionn_tb ON `employee_tb`.`empposition` = `positionn_tb`.`id`
                                             WHERE `payroll_loan_tb`.`status` = 'Pending'";


                                         $result = $conn->query($sql);

                                         if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                    echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . @$row['col_ID'] . "</span></td>";
                                                    echo "<td style='font-weight: 500; font-size: 14px' class='mb-2'>" . $row['fname'] . " " . $row['lname'] . " <br> <span style='color:gray; font-size: 0.835em; font-weight: 700'>". $row['position'] ."</td>";
                                                    echo "<td style='font-weight: 400; font-size: 14px'>" . $row['loan_type'] . "</td>";
                                                    echo "<td><button type='submit' name='view_data' class='btn btn-primary viewrequest'>View</button></td>";
                                                echo "</tr>";

                                            }
                                         }
                                        
                                        ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-borderless p-0 scrollable-content tables" style="width: 100%; overflow-y: scroll; height: 100%; display:none" id="table_under">
                                        <thead class="" style="background-color: #cecece; border-bottom-left-radius: 10px;width: 100%">
                                            <th class="d-none">ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody style="width: 100%">
                                       
                                        <?php 
                                             include 'config.php';

                                             $sql = "SELECT * FROM undertime_tb 
                                             INNER JOIN employee_tb ON `undertime_tb`.`empid` = `employee_tb`.`empid`
                                             INNER JOIN positionn_tb ON `employee_tb`.`empposition` = `positionn_tb`.`id`
                                             WHERE `undertime_tb`.`status` = 'Pending'";


                                         $result = $conn->query($sql);

                                         if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                    echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . @$row['col_ID'] . "</span></td>";
                                                    echo "<td style='font-weight: 500; font-size: 14px' class='mb-2'>" . $row['fname'] . " " . $row['lname'] . " <br> <span style='color:gray; font-size: 0.835em; font-weight: 700'>". $row['position'] ."</td>";
                                                    echo "<td style='font-weight: 400; font-size: 14px'>Undertime Request</td>";
                                                    echo "<td><button type='submit' name='view_data' class='btn btn-primary viewrequest'>View</button></td>";
                                                echo "</tr>";

                                            }
                                         }
                                        
                                        ?>
                                        </tbody>
                                    </table>

                                    <table class="table table-borderless p-0 scrollable-content tables" style="width: 100%; overflow-y: scroll; height: 100%; display:none" id="table_over">
                                        <thead class="" style="background-color: #cecece; border-bottom-left-radius: 10px;width: 100%">
                                            <th class="d-none">ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody style="width: 100%">
                                       
                                        <?php 
                                             include 'config.php';

                                             $sql = "SELECT * FROM overtime_tb 
                                             INNER JOIN employee_tb ON `overtime_tb`.`empid` = `employee_tb`.`empid`
                                             INNER JOIN positionn_tb ON `employee_tb`.`empposition` = `positionn_tb`.`id`
                                             WHERE `overtime_tb`.`status` = 'Pending'";


                                         $result = $conn->query($sql);

                                         if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                    echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . @$row['col_ID'] . "</span></td>";
                                                    echo "<td style='font-weight: 500; font-size: 14px' class='mb-2'>" . $row['fname'] . " " . $row['lname'] . " <br> <span style='color:gray; font-size: 0.835em; font-weight: 700'>". $row['position'] ."</td>";
                                                    echo "<td style='font-weight: 400; font-size: 14px'>Overtime Request</td>";
                                                    echo "<td><button type='submit' name='view_data' class='btn btn-primary viewrequest'>View</button></td>";
                                                echo "</tr>";

                                            }
                                         }
                                        
                                        ?>
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="h-100" style="width:1.5%">

                    </div>
                    <div class="d-flex flex-column justify-content-between" style="width: 43.5%; height: 81%">
                        <div class="w-100 bg-white rounded p-3" style="height: 52.5%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                            <div class="w-100 h-100">
                                <div class="announce-title w-100 d-flex flex-row justify-content-between align-items-center pl-2 pr-2" style="height: 17%;">
                                    <h4 style="font-size: 1.4em; font-weight: 400; margin-top: 0.5em">Announcement</h4>
                                    <div class="w-25 d-flex flex-row justify-content-between">
                                        <div class="" >
                                            <div class="position-absolute border" id="announce-modal" style="display:none; border-radius: 0.4em;height: 5em; width: 12em; z-index: 1000; right: 5.3em; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1);">
                                                <a class="dropdown-item mt-2" data-bs-toggle="modal" data-bs-target="#announcement_modal" style="cursor: pointer;">Add Announcement</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_summary" style="cursor: pointer;">View Summary</a>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-ellipsis-vertical fs-5" id="announcement-btn" style="color: #959595; cursor: pointer;"></i>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div style="height: 83%" class="w-100 swiper">
                                    <div class="swiper-wrapper">
                                        <?php 
                                            include 'config.php';
                                            date_default_timezone_set('Asia/Manila');
                                            $currentDate = date('Y-m-d');

                                            $query = "SELECT announcement_tb.id,
                                                announcement_tb.announce_title,
                                                employee_tb.empid,
                                                CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                announcement_tb.announce_date,
                                                announcement_tb.description,
                                                announcement_tb.date_file,
                                                announcement_tb.file_attachment 
                                            FROM announcement_tb 
                                            INNER JOIN employee_tb ON announcement_tb.empid = employee_tb.empid
                                            WHERE WEEK(announcement_tb.announce_date) = WEEK('$currentDate')
                                            ORDER BY announcement_tb.date_file DESC";
                                                $result = mysqli_query($conn, $query);
                                                $slideIndex = 0;
                                                                            
                                                if (mysqli_num_rows($result) > 0){  
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    if ($slideIndex % 1 === 0) {
                                                        $empid = $row['empid'];
                                                     echo "<div class='swiper-slide bg-white w-100 position-relative '>";
                                                }
                                            ?>
                                            <div class="announce-head w-100 d-flex flex-row pl-4 mb-3" style="height: 20%">
                                                <div class=" d-flex justify-content-center" style="width: 13%; height: 100%">
                                                    <?php
                                                        if(!empty($row['emp_img_url'])) {
                                                            $image_url = $row['emp_img_url'];
                                                        } else {
                                                            $Supervisor_Profile = "SELECT * FROM employee_tb WHERE `empid` = '$empid'";
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
                                    
                                                        }
                                                        if (!empty($image_url)) {
                                                            $file_ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                                        } else {
                                                            $file_ext = ''; // Set a default value or handle the case when $image_url is empty.
                                                        }
                                                    ?>
                                                    <img <?php if(!empty($image_url)){ echo "src='uploads/".$image_url."' "; } else{ echo "src='data:".$image_type.";base64,".$image_data."'";} ?> alt="" srcset="" accept=".jpg, .jpeg, .png" title="<?php echo $image_url; ?>"  class="announce-img rounded-circle" style="width: 100% height: 100%">
                                                </div>
                                                <div class="h-100 w-50 d-flex flex-column">
                                                    <div class="w-100 h-50 pl-2 pt-1">
                                                        <h5 style="font-size: 1.2em; color: blue"><?php echo $row['full_name'] ?></h5>
                                                    </div>
                                                    <div class="w-100 h-50 pl-2 ">
                                                        <?php
                                                      // Current date
                                                            $datefile = $row['date_file'];
                                                            $currentDate = new DateTime();

                                                            // Your specific date
                                                            $specificDate = new DateTime($datefile);

                                                            // Calculate the difference
                                                            $interval = $currentDate->diff($specificDate);

                                                            // Get the number of days
                                                            $daysDifference = $interval->days;

                                                            // echo $daysDifference;
                                                    
                                                        ?>
                                                        <p style="font-size: 1em; color: #959595; font-weight: 500"><?php echo $daysDifference; ?> days(s) ago</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="scrollable-content w-100 pl-4 pr-4 " style="overflow-y: scroll; height: 60%; margin-top: 1em">
                                                <h4 class="mt-1 ml-2"><?php echo $row['announce_title'] ?></h4>
                                                <p class="pl-3 pr-3" ><?php echo $row['description']?></p>  
                                            </div>
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
                                                 echo "<div class='announcement-slide d-flex align-items-center m-auto' style=''>";
                                                    echo "<h4 style=''>No items on whiteboard</h4>";
                                                echo "</div>";
                                             }
                                            ?>
                                    </div>
                                     <div class="swiper-pagination"></div>

                                    <!-- If we need navigation buttons -->
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>

                                </div>
                            </div>
                        </div>
                        <div class="w-100" style="height: 1.5%;"></div>
                        <div class="w-100 bg-white rounded" style="height: 46%; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                            <div class="w-100 h-100 p-2">
                                <div class="announce-title w-100 d-flex flex-row justify-content-between align-items-center pl-2 pr-2" style="height: 17%;">
                                    <h4 style="font-size: 1.4em; font-weight: 400; margin-top: 0.5em">Events</h4>
                                    <div class="w-25 d-flex flex-row justify-content-between">
                                        <div class="" >
                                            <div class="position-absolute border" id="event-modal" style="display:none; border-radius: 0.4em;height: 7.5em; width: 12em; z-index: 100; right: 5.3em; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); background-color: white;">
                                                <a class="dropdown-item mt-1" data-bs-toggle="modal" data-bs-target="#add_event" style="cursor: pointer;">Add Event</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_holiday" style="cursor: pointer;">Add Holiday</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_holiday" style="cursor: pointer;">View Holidays</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_event" style="cursor: pointer;">View Event</a>
                                            </div>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-ellipsis-vertical fs-5" id="event-btn" style="color: #959595; cursor: pointer;"></i>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="w-100" style="height: 83%">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!-- Validations -->

   <!-- Modal HTML for Duplicate Group Name -->
   <div id="duplicateModal" class="modals">
    <span class="close">&times;</span>
    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
        <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
          <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; color: green"></i>
        </div>
        <h4 class="mt-3">Successfully Updated!</h4>
       
    </div>
      <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <button class="btn border border-black btn-closes">OK</button>
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
        <h4 class="mt-3">Successfully Inserted!</h4>
       
    </div>
    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <button class="btn border border-black btn-closes">OK</button>
    </div>
</div>

<!-- deleted -->
<div id="deleteModal" class="modals">
    <span class="close" id="removeParamButton">&times;</span>
    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
      <div class="d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
            <i class="fa-solid fa-trash bouncing-icon" style="font-size: 6em; color: red"></i>
          </div>
          <h4 class="mt-3">Successfully Deleted!</h4>
        
      </div>
      <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <button class="btn border border-black btn-closes">OK</button>
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

        // Function to hide a modal and remove parameters from the URL
        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            var overlay = document.querySelector('.overlay');
            modal.style.display = 'none';
            overlay.style.display = 'none';

            // Remove the parameter from the URL based on modalId
            var urlParams = new URLSearchParams(window.location.search);

            if (modalId === 'duplicateModal') {
                urlParams.delete('updated');
            } else if (modalId === 'deleteModal') {
                urlParams.delete('deleted');
            } else if (modalId === 'insertedModal') {
                urlParams.delete('inserted');
            }

            var newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState({}, document.title, newUrl);
        }

        // Check if the URL contains a parameter and show the modal accordingly
        window.onload = function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('updated')) {
                showModal('duplicateModal', 'Duplicate Group Name!');
            }
            if (urlParams.has('inserted')) {
                showModal('insertedModal', 'Successfully Inserted!');
            }
            if (urlParams.has('deleted')) {
                showModal('deleteModal', 'Successfully Deleted!');
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- <script>
        const buttons = document.querySelectorAll(".req-btn");

         buttons.forEach(button => {
            button.addEventListener("click", () => {
                // Remove the "highlight" class from all buttons
                buttons.forEach(btn => btn.classList.remove("highlight"));
                                    
                // Add the "highlight" class to the clicked button
                button.classList.add("highlight");
            });
        });
    </script> -->
   

    <!-- <script>
       const request = document.getElementById("request");
        const loan = document.getElementById("loan");
        const table_request = document.getElementById("table_request");
        const table_loan = document.getElementById("table_loan");

        request.addEventListener('click', () => {
            table_request.style.display = 'table';
            table_loan.style.display = 'none';
        });

        loan.addEventListener('click', () => {
            table_request.style.display = 'none';
            table_loan.style.display = 'table';
        });

    </script> -->

    <!-- //buttons
    const request = document.getElementById("request");
    const loan = document.getElementById("loan");

    //tables
    const table_request = document.getElementById("table_request");
    const table_loan = document.getElementById("table_loan"); -->





<script>
        const request = document.getElementById("request");
        const leave = document.getElementById("leave");
        const loan = document.getElementById("loan");
        const under = document.getElementById("under");
        const over = document.getElementById("over");
        const table_request = document.getElementById("table_request");
        const tbody_leave = document.getElementById("tbody_leave");
        const table_loan = document.getElementById("table_loan");
        const table_under = document.getElementById("table_under");
        const table_over = document.getElementById("table_over");

        request.addEventListener('click', () => {
            table_request.style.display = 'table';
            table_loan.style.display = 'none';
            table_leave.style.display = 'none';
            table_under.style.display= 'none';
            table_over.style.display= 'none';
            
            loan.classList.remove("highlight");
            request.classList.add("highlight");
            leave.classList.remove("highlight");
            under.classList.remove("highlight");
            over.classList.remove("highlight");
         
            console.log("clicked");
        });

         leave.addEventListener('click', () => {
            table_request.style.display = 'none';
            table_loan.style.display = 'none';
            table_leave.style.display = 'table';
            table_under.style.display= 'none';
            table_over.style.display= 'none';
            
            loan.classList.remove("highlight");
            request.classList.remove("highlight");
            leave.classList.add("highlight");
            under.classList.remove("highlight");
            over.classList.remove("highlight");
         
            console.log("clicked");
        });

        loan.addEventListener('click', () => {
            table_request.style.display = 'none';
            table_loan.style.display = 'table';
            table_leave.style.display = 'none';
            table_under.style.display= 'none';
            table_over.style.display= 'none';

            loan.classList.add("highlight");
            request.classList.remove("highlight");
            leave.classList.remove("highlight");
            under.classList.remove("highlight");
            over.classList.remove("highlight");

            console.log("clicked");
        });

        under.addEventListener('click', () => {
            table_request.style.display = 'none';
            table_loan.style.display = 'none';
            table_leave.style.display = 'none';
            table_under.style.display= 'table';
            table_over.style.display= 'none';

            loan.classList.remove("highlight");
            request.classList.remove("highlight");
            leave.classList.remove("highlight");
            under.classList.add("highlight");
            over.classList.remove("highlight");

            console.log("clicked");
        });

         over.addEventListener('click', () => {
            table_request.style.display = 'none';
            table_loan.style.display = 'none';
            table_leave.style.display = 'none';
            table_under.style.display= 'none';
            table_over.style.display= 'table';

            loan.classList.remove("highlight");
            request.classList.remove("highlight");
            leave.classList.remove("highlight");
            under.classList.remove("highlight");
            over.classList.add("highlight");

            console.log("clicked");
        });
        
    </script>

<script>
        let btn = document.getElementById("announcement-btn");
        let modal = document.getElementById("announce-modal");

        btn.addEventListener('click', function(){
            if (modal.style.display === "none" || modal.style.display === "") {
                modal.style.display = "block";
            } else {
                modal.style.display = "none";
            }
        });
    </script>

<script>
        let btns = document.getElementById("event-btn");
        let modals = document.getElementById("event-modal");

        btns.addEventListener('click', function(){
            if (modals.style.display === "none" || modals.style.display === "") {
                modals.style.display = "block";
            } else {
                modals.style.display = "none";
            }
        });
    </script>

    <script>
        // JavaScript to count and display the number of <tr> tags in the tbody
        const tbody = document.getElementById('wfh_table_body');
        const countSpan = document.getElementById('wfh_count');

        if (tbody && countSpan) {
            const trCount = tbody.getElementsByTagName('tr').length;
            countSpan.textContent = trCount;
        }
    </script>



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
        urlParams.delete(modalId === 'duplicateModal' ? 'updated' : 'inserted');
        var newUrl = window.location.pathname + '?' + urlParams.toString();
        window.history.replaceState({}, document.title, newUrl);
    }

    // Check if the URL contains a parameter and show the modal accordingly
    window.onload = function () {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('updated')) {
            showModal('duplicateModal', 'Duplicate Group Name!');
        }
        if (urlParams.has('inserted')) {
            showModal('insertedModal', 'Successfully Inserted!');
        }
        if(urlParams.has('deleted')){
            showModal('deleteModal', 'Successfully Deleted!');
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
        // Function to remove a specific parameter from the URL
        function removeURLParameter(key) {
            const url = new URL(window.location.href);
            url.searchParams.delete(key);
            history.replaceState(null, null, url.toString());
        }

        // Add an event listener to the button
        document.getElementById("removeParamButton").addEventListener("click", function () {
            removeURLParameter('deleted');
        });
    </script>


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
<!---------------Script para i-check kapag nagclick sa view button-------------------->
<script> 
        $(document).ready(function(){
        $('.viewrequest').on('click', function(){
        $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        console.log(data);
        $('#id_emp').val(data[0]);                  
        $('#id_reqType').val(data[1]);
    });
});
</script>      
<!---------------Script para i-check kapag nagclick sa view button-------------------->

<!------------------------------------Script para sa pag pop-up ng view modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.showmodal').on('click', function(){
                 $('#view_desc_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_description').val(data[3]);
               });
             });
</script>
<!---------------------------------End ng Script para sa pag pop-up ng view modal------------------------------------------>

<!-- Event edit -->
<script>
            $(document).ready(function (){
                $('.edit_event').on('click' , function(){
                    $('#edit_event').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#id').val(data[0]);
                    $('#title').val(data[1]);
                    $('#type').val(data[2]);
                    $('#start').val(data[3]);
                    $('#end').val(data[4]);
                    // $('#hol_title').val(data[1]);
                });
            });
        </script>

<!-- delete event -->

<script>
            $(document).ready(function (){
                $('.deletebtn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id').val(data[0]);
                    $('#delete_title').val(data[1]);
                    
                    

                });
            });
        </script>

    <!---------------------------------------Script sa pagpop-up ng modal para Not-Applicable--------------------------------------------->          
        <script>
            $(document).ready(function (){
                $('.edit').on('click' , function(){
                    $('#edit').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#id_modall').val(data[0]);
                    $('#HolidayDate_id').val(data[2]);
                    // $('#hol_title').val(data[1]);
                });
            });
        </script>
<!---------------------------------------End Script sa pagpop-up ng modal para Not-Applicable--------------------------------------------->

    
<!-------------------Script para sa pagfunction ng tab button------------------->
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Add a click event listener to each button
  document.getElementById("tab-0").addEventListener("click", function () {
    changeTab(0);
  });
  document.getElementById("tab-1").addEventListener("click", function () {
    changeTab(1);
  });
  document.getElementById("tab-2").addEventListener("click", function () {
    changeTab(2);
  });
  document.getElementById("tab-3").addEventListener("click", function () {
    changeTab(3);
  });
});

function changeTab(tabIndex) {
  var tables = document.querySelectorAll('.request-table');
  var buttons = document.querySelectorAll('.emp-request-btn button');

  for (var i = 0; i < tables.length; i++) {
    tables[i].style.display = 'none';
  }

  for (var i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('active-tab');
  }

  tables[tabIndex].style.display = 'block';
  buttons[tabIndex].classList.add('active-tab');
}
</script>
    



<!------------------------------------Script para lumabas ang download modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                $('#download').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table').val(data[6]);
                   $('#name_table').val(data[2]);
               });
             });
</script>
<!---------------------------------End ng Script para lumabas ang download modal------------------------------------------>

<!---------------------------- Script para lumabas ang warning message na PDF File lang inaallow------------------------------------------>
<script>
  document.getElementById('inputfile').addEventListener('change', function(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file.');
      fileInput.value = ''; // Clear the file input field
    }
  });
</script>

<!--------------------End ng Script para lumabas ang Script para lumabas ang warning message na PDF File lang inaallow--------------------->
        
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


  var announceContent = document.querySelector('.announce-content');
  var prevButton = document.querySelector('.prev');
  var nextButton = document.querySelector('.next');

  announceContent.onscroll = function() {
    var scrollPosition = announceContent.scrollTop;

    // Adjust the position of prev and next buttons based on the scroll position
    prevButton.style.top = scrollPosition + announceContent.offsetHeight - prevButton.offsetHeight + 'px';
    nextButton.style.top = scrollPosition + announceContent.offsetHeight - nextButton.offsetHeight + 'px';
  };
</script>
<!------------------------End Script sa function ng Previous and Next Button--------------------------------------->



<!--     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="vendors/js/vendor.bundle.base.js"></script> -->

<!-- endinject -->
<!-- Plugin js for this page-->
<!-- <script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="bootstrap js/template.js"></script> -->
<!-- Custom js for this page-->
<!-- <script src="bootstrap js/data-table.js"></script> -->
<!-- End custom js for this page-->
    <!-- <script src="main.js"></script> -->

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
<!-- <script src="main.js"></script> -->
<script src="bootstrap js/data-table.js"></script>   

<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>