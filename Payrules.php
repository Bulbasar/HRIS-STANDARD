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
include ("config.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    

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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/payrule.css"/>
    <link rel="stylesheet" href="css/branchResponsive.css"/>

    <?php 
      
      include 'configHardware.php';
      
      
      ?>

    <title>Pay Rule</title>

</head>
<body>
<header>
<?php
include 'header.php';
?>
</header>

<style>
    html{
      overflow: hidden !important;
    }
    body{ 
      overflow: hidden !important;
      background-color: #f4f4f4 !important;
    }

    .card-body{
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
      border-radius: 25px;
    }

    .content-wrapper{
          width: 80%;
    }

    .table {
         width: 99.7%;
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

    h2{
      font-size: 23px;
      font-weight: bold;
    }
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    
</style>


<!------------------------------------------------------ADD NEW BRANCH MODAL-------------------------------------------------------->
<div class="modal fade" id="addnew_btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Pay Rule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="Data Controller/Employee List/add_rulespay.php" method="POST">
         <div class="modal-body">

                  <div class="mb-3">
                     <label for="tele_phone" class="form-label">Pay Rule</label>
                     <input type="text" id="id_rulePay" name="payingrule" class="form-control">
                  </div>


      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="submit" name="add_payrule" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!-------------------------------------------------END OF ADD NEW BRANCH MODAL-------------------------------------------------------->

<!-------------------------------------------------------------------EDIT BRANCH INFO MODAL-------------------------------------------------------->
<div class="modal fade" id="editPayrules" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Position</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Employee List/edit_rulepay.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="name_pay" id="id_edit_rules">
                <div class="mb-3">
                     <label for="rulepay" class="form-label">Pay Rule</label>
                     <input type="text" id="edit_id_rulepay" name="payofrule" class="form-control">
                  </div>

      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
            <button type="submit" name="update_data" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!---------------------------------------------------END OF EDIT BRANCH INFO MODAL------------------------------------------------------------------->

<!-------------------------------------------------------------------DELETE BRANCH INFO MODAL-------------------------------------------------------->
<div class="modal fade" id="deletemodalpay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Employee List/delete_rulepay.php" method="POST">
      <div class="modal-body">

        <input type="text" name="delete_id" id="delete_id_pay">
        <input type="text" name="designation" id="designated">

        <h4>Do you want to delete?</h4>

      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!---------------------------------------------------END OF DELETE BRANCH INFO MODAL------------------------------------------------------------------->


<div class="main-panel" style="width: 100%; height: 100vh; position: absolute; top: 53px; margin-left: 16.7%;">
        <div class="content-wrapper mt-5" >
          <div class="card" style="width:1500px; height: 780px;">
            <div class="card-body">
            <div class="row">
                        <div class="col-6">
                            <h2>Pay Rules</h2>
                        </div>
                        <div class="col-6 mt-2 text-end">
                        <!-- Button trigger modal -->
                        <button type="button" class="add_new_btn" data-bs-toggle="modal" data-bs-target="#addnew_btn" style="background-color: black; padding: 10px; height: 45px; width: 120px; border-radius: 10px;">
                        Add
                        </button>
                        </div>
                    </div> <!--ROW END  -->


<!-------------------------------------------------------MESSAGE ALERT------------------------------------------------------------------->
        <?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }

        ?>
<!------------------------------------------------------- END NG MESSAGE ALERT------------------------------------------------------------>


<!-------------------------------------------------------ERROR MESSAGE ALERT------------------------------------------------------------------->
<?php
    if (isset($_GET['error'])) {
        $err = $_GET['error'];
        echo '<div id="alert-message" class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        '.$err.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
?>
<!------------------------------------------------------- END NG ERROR MESSAGE ALERT------------------------------------------------------------>


            <div class="row" >
                <div class="col-12 mt-5">
                    <div class="table-responsive" style="overflow: hidden;">
                      <form action="View_payrule.php" method="post">
                        <input type="hidden" id="id_rulename" name="name_rulename">
                        <input type="hidden" id="id_payname" name="name_payname">
                        <table id="order-listing" class="table" >
                        <thead style="background-color: #ececec;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th>Pay Rules</th>
                                <th>Employee</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    <tbody>
                           <?php
                                include 'config.php';

                                $payquery = "SELECT * FROM Payrule_tb";
                                $payResult = mysqli_query($conn, $payquery);
                                
                                while ($payRow = mysqli_fetch_assoc($payResult)) {
                                    $pay_id = $payRow['id'];
                                    $pay_name = $payRow['rule_name'];
                                    $emp_query = "SELECT COUNT(*) as pay_count FROM employee_tb WHERE payrules = '$pay_id'";
                                    $emp_result = mysqli_query($conn, $emp_query);
                                    $emp_row = mysqli_fetch_assoc($emp_result);
                                    $pay_count = $emp_row['pay_count'];

                                    // Generate the HTML table row
                                    echo "<tr>
                                            <td style= 'display: none;'>$pay_id</td>
                                            <td style='font-weight: 400'>$pay_name</td>
                                            <td style='font-weight: 400'>$pay_count</td>
                                            <td>
                                                <button style='background-color: inherit;' type='submit'  name='view_data_Pay' class='link-dark border-0 viewpaybtn' title = 'View'><i class='fa-solid fa-eye fs-5 me-3'></i></button>
                                                <button style='background-color: inherit;' type='button' class='link-dark editpay border-0' data-bs-toggle='modal' data-bs-target='#editPayrules'><i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i></button> 
                                                <button style='background-color: inherit;' type='button' class='link-dark deletepay border-0' data-bs-toggle='modal' data-bs-target='#deletemodalpay'><i class='fa-solid fa-trash fs-5 me-3 title='delete'></i></button> 
                                            </td>
                                        </tr>";
                                }
                                mysqli_close($conn);
                            ?>
        
                         </tbody>
                      </table>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    


<!----------------------------------------------Script sa pagpop-up ng modal para maedit------------------------------------------------------->        
<script>
            $(document).ready(function (){
                $('.editpay').on('click' , function(){
                    $('#editPayrules').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#id_edit_rules').val(data[0]);
                    $('#edit_id_rulepay').val(data[1]);

                });
            });
        </script>
<!----------------------------------------------End ng Script sa pagpop-up ng modal para maedit------------------------------------------------------->


<!---------------------------------------Script sa pagpop-up ng modal para madelete--------------------------------------------->          
<script>
            $(document).ready(function (){
                $('.deletepay').on('click' , function(){
                    $('#deletemodalpay').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id_pay').val(data[0]);
                    $('#designated').val(data[2]);
                    

                });
            });
        </script>
<!---------------------------------------End Script sa pagpop-up ng modal para madelete--------------------------------------------->


<script>
            $(document).ready(function(){
            $('.viewpaybtn').on('click', function(){
                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);
                //id_colId
                $('#id_rulename').val(data[0]);
                $('#id_payname').val(data[1]);
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