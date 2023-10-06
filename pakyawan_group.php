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

            #multi_option{
	        max-width: 100%;
	        width: 100%;
            margin-bottom: 1em !important;
        }
        .multiselect-dropdown-list-wrapper span.placeholder{
        display: none !important;
        cursor: default !important;
        background-color: #fff !important;
        color: #fff !important;
        display:none !important; 
    } 

    .multiselect-dropdown{
        width: 100% !important;
        border: 1px solid #CED4DA;
    }


    .placeholder{
        background-color: #fff !important;
        color: #fff !important;
        cursor: default;
    }

/* Style for the overlay */
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
    
    <div class="container-fliud p-3 pt-4" style="position: absolute; left: 18.5%; top: 14.3%; width: 78%; height: 80%; background-color: #fff; border-radius: 0.5em;  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
        <div class="header-title w-100 d-flex flex-row justify-content-between">
            <h1 class="fs-2">Pakyawan Group</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Group</button>
        </div>
        <div class="content mt-5">
            <div class="table-responsive p-2" id="table-responsiveness">
                <table id="order-listing" class="table" style="width: 100%">
                    <thead>
                        <th>Group Name</th>
                        <th>Designation</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php 
                            include 'config.php';

                            $sql = "SELECT group_name, COUNT(*) AS count FROM pakyawan_group_tb GROUP BY group_name";

                            $result = mysqli_query($conn, $sql);

                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>";
                                    echo "<td style='font-weight: 400'>".$row['group_name']."</td>";
                                    echo "<td style='font-weight: 400'>".$row['count']."</td>";
                                    echo "<td> 
                                    <a href='pakyaw_group_designation?group_name=".$row['group_name']."' style='color: black' title='View' ><i class='fa-solid fa-eye fs-5 me-3'></i></a>

                                    <button type='button' class= 'border-0 editbtn' title = 'Edit' data-bs-toggle='modal' data-bs-target='#update_deptMDL' style=' background: transparent;'>
                                    <i class='fa-solid fa-pen-to-square fs-5 me-3' title='Edit'></i>
                                    </button>
                                    <button type='button' class= 'border-0 deletebtn' title = 'Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style=' background: transparent;'>
                                    <i class='fa-solid fa-trash fs-5 me-3 title='Delete'></i>
                                    
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>




    <!-- insert -->
    <form action="Data Controller/Pakyawan/insert_group.php" method="POST">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Pakyawan Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Group Name</label>
                        <input type="text" name="group_name" id="" placeholder="Enter Group Name" class="form-control" required><br>

                        <label for="">Frequency</label>
                        <select name="frequency" id="" class="form-select" placeholder="Select Frequency">
                            <option value="" selected disabled>Select Frequency</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                        </select><br>

                        <?php 
                            include 'config.php';

                            $sql = "SELECT * FROM employee_tb WHERE classification = 3";
                            $result = mysqli_query($conn, $sql);

                            $options = "";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $empid = $row['empid'];
                                $fname = $row['fname'];
                                $lname = $row['lname'];

                                $options .= "<option value='".$empid."'>".$fname." ".$lname." </option>";
                            }

                        ?> 
                        <label for="">Select Employee</label>
                        <select class="approver-dd dd-hide" name="empid[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false" >
                            <?php echo $options ?>                  
                        </select>
                    

                        <?php
                            include 'config.php';
                            $sql = "SELECT * FROM piece_rate_tb";
                            $result = mysqli_query($conn, $sql);

                            $options = "";
                            while ($row = mysqli_fetch_assoc($result)) {
                                            
                            $options .= "<option value='" . $row['id'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['unit_type']."</option>";
                        }
                        ?>

                                        
                        <label for="pakyawan" >Group Work Type</label>
                        <select class="pakyawan-dd" name="piece_rate_id[]" id="piece_rate_id" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2">
                            <?php echo $options ?>     
                        </select>
                        <input type="hidden" name="piece_rate_id_hidden" id="piece_rate_id_hidden" value="<?php echo @$row['id']?>">

                                        
                        <script>
                            $(document).ready(function() {
                            $('.pakyawan-dd').change(function() {
                                var selectedValues = $(this).val();
                                $('#piece_rate_id_hidden').val(JSON.stringify(selectedValues));
                            });
                            });
                        </script>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" style='border:rgba(0, 0, 0, 0.2) 1px solid' data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Update -->
    <div class="modal fade" id="update_deptMDL" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="actions/Pakyawan/deleteGroup.php" method="POST">
            <div class="modal-body form-group">

                <!-- <input type="hidden" name="id" id="delete_id"> -->
                <label for="">Group Name</label>
                <input type="text" name="group_name" id="designates" class="form-control" required><br>

                

               

            </div> <!--Modal body div close tag-->
            <div class="modal-footer">
                
                <button type="button" class="btn border border-seconday" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
            </div>
            </form>


            </div>
        </div>
    </div>
    
     <!-- View -->
     <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="actions/Pakyawan/deleteGroup.php" method="POST">
            <div class="modal-body">

                <!-- <input type="hidden" name="id" id="delete_id"> -->
                <input type="text" name="group_name" id="designatess">

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



    <!-- delete -->
    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="actions/Pakyawan/deleteGroup.php" method="POST">
            <div class="modal-body">

                <!-- <input type="hidden" name="id" id="delete_id"> -->
                <input type="hidden" name="group_name" id="designate">

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




    <!-- Validations -->

   <!-- Modal HTML for Duplicate Group Name -->
<div id="duplicateModal" class="modals">
    <span class="close">&times;</span>
    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
        <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
            <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
        </div>
        <h4 class="mt-3">Error Input!</h4>
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
        <h4 class="mt-3">Successfully Inserted!</h4>
       
    </div>
    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <button class="btn border border-black btn-closes">Close</button>
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
        urlParams.delete(modalId === 'duplicateModal' ? 'duplicate' : 'inserted');
        var newUrl = window.location.pathname + '?' + urlParams.toString();
        window.history.replaceState({}, document.title, newUrl);
    }

    // Check if the URL contains a parameter and show the modal accordingly
    window.onload = function () {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('duplicate')) {
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


    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_option' 
	});
</script>



    <!-- delete -->
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

        

    <!-- update -->
    <script>
            $(document).ready(function (){
                $('.editbtn').on('click' , function(){
                    $('#update_deptMDL').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    // $('#delete_id').val(data[0]);
                    $('#designates').val(data[0]);
                    

                });
            });
        </script>



<script>
            $(document).ready(function (){
                $('.viewbtn').on('click' , function(){
                    $('#viewModal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    // $('#delete_id').val(data[0]);
                    $('#designatess').val(data[0]);
                    

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