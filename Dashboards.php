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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
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
    </style>
    
    <div class="dashboard-container" style="position: absolute; width:85%; height: 100%; top: 9%; left: 15.5% ">
        <div class="dashboard-content pl-4 pr-4 d-flex flex-column justify-content-between h-100 mx-auto " style="width: 97%;">
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
                                <div style="width: 90%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); " class="border rounded mb-2 d-flex flex-column justify-content-between">
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
                                        <div class="d-flex flex-row position-relative w-100 h-100">
                                            <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em;" name="present" value="<?php echo $employeePresent; ?>" readonly>
                                            <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <!-- Absent -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center  ">
                                <div style="width: 90%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); " class="border rounded mb-2">
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
                                        <div class="d-flex flex-row position-relative w-100 h-100">
                                            <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em;" name="present" value="<?php echo $employeeAbsent; ?>" readonly>
                                            <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <!-- On Leave -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center ">
                                <div style="width: 90%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); " class="border rounded mb-2">
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
                                        <div class="d-flex flex-row position-relative w-100 h-100">
                                            <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em;" name="present" value="<?php echo $employeeOnleave; ?>" readonly>
                                            <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Working home  -->
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center ">
                                <div style="width: 90%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); " class="border rounded mb-2">
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
                                        <div class="d-flex flex-row position-relative w-100 h-100">
                                            <span id="wfh_count" style="margin-top: 3px; font-size: 2.8em; margin-right: 2em">0</span>
                                            <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <div class="w-25 h-100 d-flex justify-content-center align-items-center  ">
                                <div style="width: 90%; height: 80%; box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.1); " class="border rounded mb-2">
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
                                        <div class="d-flex flex-row position-relative w-100 h-100">
                                            <input type="text" style="border:none;padding: 0;height: 90%; width: 33%; font-size: 2.8em;" name="present" value="<?php echo $employeeLate; ?>" readonly>
                                            <p class="d-flex align-items-end" style="margin-bottom:1em">Of <span class="ml-2" style="color:red"><?php echo $employee_count; ?></span></p>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-body w-100 border pt-2" style="height:73%; ">
                
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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