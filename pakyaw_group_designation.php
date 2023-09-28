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

    
    <link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
    <script type="text/javascript" src="js/multi-select-dd.js"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/piece_rate.css">
    <title>HRIS | Pakyawan Group</title>
    
</head>
<body>

<header>
        <?php include("header.php")?>
</header>

    <style>
        table {
            display: block;
            overflow-x: hidden;
            white-space: nowrap;
            max-height: 450px;
            height: 450px;
                    
                    
        }
        tbody {
            display: table;
            width: 100%;
        }
        tr {
            width: 100% !important;
            display: table !important;
            table-layout: fixed !important;
        }
    
        .pagination{
        margin-right: 63px !important;

        
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

    <?php 
    $group_name = $_GET['group_name'];

    ?>

    <div class="container-fliud p-3 pt-4" style="position: absolute; left: 18.5%; top: 14.3%; width: 78%; height: 80%; background-color: #fff; border-radius: 0.5em;  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
        <div class="header-title w-100 d-flex flex-row justify-content-between">
            <h1 class="fs-2"><?php echo $group_name; ?></h1>
            <a href="pakyawan_group" class="btn btn-primary">Back</a>
        </div>
        <div class="content mt-5">
            <div class="table-responive p-2" id="table-responsiveness" style="overflow: hidden;">
                <table id="order-listing" class="table" style="width: 100%">
                    <thead>
                        <th class="d-none">empid</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                        <!-- <th>Action</th> -->
                    </thead>
                    <tbody>
                        <?php 
                            include 'config.php';
                            $sql = "SELECT * FROM `employee_tb`
                                    INNER JOIN `pakyawan_group_tb` ON `employee_tb`.`empid` = `pakyawan_group_tb`.`empid` WHERE `pakyawan_group_tb`.`group_name` = '$group_name' ";

                            $result = mysqli_query($conn, $sql);

                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    $empid = $row['empid'];
                                    $sql = "SELECT employee_tb.company_code, 
                                    employee_tb.empid, 
                                    assigned_company_code_tb.company_code_id, 
                                    assigned_company_code_tb.empid, 
                                    company_code_tb.id, 
                                    company_code_tb.company_code AS company_code_name 
                                    FROM assigned_company_code_tb 
                                    INNER JOIN company_code_tb ON assigned_company_code_tb.company_code_id = company_code_tb.id 
                                    INNER JOIN employee_tb ON assigned_company_code_tb.empid = employee_tb.empid 
                                    WHERE assigned_company_code_tb.empid = '$empid' ";
                                    
                                    $cmpny_result = mysqli_query($conn, $sql); // Corrected parameter order
                                    $cmpny_row = mysqli_fetch_assoc($cmpny_result);

                                    echo "<tr>";
                                    echo "<td class='d-none'>".$empid."</td>";
                                    echo "<td style='font-weight: 400;'>";

                                    $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                    echo $cmpny_code !== null ? $cmpny_code . " - " . $row["empid"] : $row["empid"];

                                    echo "</td>";
                                    echo "<td style='font-weight: 400'>".$row['fname']." ".$row['lname']."</td>";
                                    echo "<td> <button type='button' class= 'border-0 deletebtn' title = 'Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style=' background: transparent;'>
                                    <i class='fa-solid fa-trash fs-5 me-3 title='Delete'></i></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

      <!-- delete -->
      <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="actions/Pakyawan/deleteEmpGroup.php" method="POST">
            <div class="modal-body">

                <!-- <input type="hidden" name="id" id="delete_id"> -->
                <input type="text" name="empid" id="designate">

                <h4>Do you want to delete?</h4>

            </div> <!--Modal body div close tag-->
            <div class="modal-footer">
                
                <button type="button" class="btn border border-seconday" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
            </div>
            </form>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
            $(document).ready(function (){
                $('.deletebtn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    // $('#delete_id').val(data[0]);
                    $('#designate').val(data[0]);
                    

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