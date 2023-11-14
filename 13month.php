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
<link rel="stylesheet" href="css/dtRecordsResponsives.css">
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
<header>
    <?php
        include 'header.php';
    ?>
</header>


<!-- Modal -->
<div class="modal fade" id="thirteenmonth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">13th Month Cutoff</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Data Controller/13month/13cutoff.php" method="POST">
        <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <label for="" class="form-label">Month</label>
                            <select id="" required name="monthpicker" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option disabled selected value=''>Pick a Month</option>
                                <option value='January'>January</option>
                                <option value='February'>February</option>
                                <option value='March'>March</option>
                                <option value='April'>April</option>
                                <option value='May'>May</option>
                                <option value='June'>June</option>
                                <option value='July'>July</option>
                                <option value='August'>August</option>
                                <option value='September'>September</option>
                                <option value='October'>October</option>
                                <option value='November'>November</option>
                                <option value='December'>December</option>
                            </select>
                    </div>
                    <div class="col-6">
                        <label for="" class="form-label">Year</label>
                        <select id="" required name="yearpicker" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                            <option disabled selected value=''>Pick a Year</option>
                            <option value='2023'>2023</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-6">
                        <label for="" class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="startdatepicker">
                    </div>
                    <div class="col-6">
                        <label for="" class="form-label">End Date</label>
                        <input type="date" class="form-control" name="enddatepicker">
                    </div>
                </div>

                   <div class="row mt-3">
                        <div class="col-6">
                            <label for="" class="form-label">Select Department</label>
                                <?php
                                include 'config.php';

                                $sqls = "SELECT * FROM dept_tb WHERE col_ID != 1";

                                $results = mysqli_query($conn, $sqls);

                                $option = "";
                                while ($rows = mysqli_fetch_assoc($results)) {
                                    $option .= "<option value='" . $rows['col_ID'] . "'>" . $rows['col_deptname'] . "</option> ";
                                }
                            ?>
                                <select name="department" id="departmentDropdown" class="form-select">
                                    <option value selected disabled>Select Department</option>
                                    <option value='All'>All</option>
                                <?php echo $option ?>
                            </select>
                        </div>

                        <div class="col-6" id="employeeDropdown">
                            <label for="" class="form-label">Select Employee</label>
                                <select class="approver-dd dd-hide" name="name_empId[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false">
                            </select>
                        </div>
                    </div>   

                 </div>
                <div class="modal-footer">
                    <button type="submit" name="thirteen-submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--Delete Modal-->
<div class="modal fade" id="delete_cutoffs" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actions/13Month/Delete13.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="cutoffname" id="id-cutoffs">
        Are you sure, You want to Delete the Cutoff?
      </div>
        <div class="modal-footer">
            <button type="submit" name="yesdelete" class="btn btn-primary">Yes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--Add Employee Modal-->
<div class="modal fade" id="add_employee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
     
            <div class="mb-3">
                <label for="" class="form-label">Select Department</label>
                <?php
                            include 'config.php';

                            $sqls = "SELECT * FROM dept_tb WHERE col_ID != 1";

                            $results = mysqli_query($conn, $sqls);

                            $option = "";
                            while ($rows = mysqli_fetch_assoc($results)) {
                                $option .= "<option value='" . $rows['col_ID'] . "'>" . $rows['col_deptname'] . "</option> ";
                            }
                        ?>
                        <select name="department" id="departmentDropdowns" class="form-select">
                            <option value selected disabled>Select Department</option>
                            <option value='All'>All</option>
                            <?php echo $option ?>
                        </select>
            </div>
                <!-- <p>Selected Department ID: <span id="selectedDepartment"><?php echo @$selectedDepartment ?></span></p> -->   

            <div class="mb-3">
                <form action="actions/13Month/AddEmployee.php" method="post">
                <input type="hidden" name="cutoffname-add" id="id-cutoffs-add">
                    <label for="" class="form-label">Select Employee</label>
                    <div id="employeeDropdowns">
                        <select class="approver-dd dd-hide" name="name_empId[]" id="multi_options" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;">
                        </select>
                    </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" name="Yes-addEmp" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>


        <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
           <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
               <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
                <div class="card-body">

                                <div class="row">
                                    <div class="col-6">
                                        <h2 style="font-size: 30px; font-family: Poppins, 'Source Sans Pro';">13th Month List</h2>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn-cutoff" data-bs-toggle="modal" data-bs-target="#thirteenmonth">
                                            Create Cut Off
                                        </button>
                                    </div>
                                </div>

                                
                                <div class="main-box-containers">
                                    <div class="box-contents">
                                        <form action="thirteen_pay.php" method="POST">
                                            <?php 
                                                include 'config.php';
                                                $sql = "SELECT * FROM thirteencutoff_tb";
                                                $sql_result = mysqli_query($conn, $sql);

                                                if(mysqli_num_rows($sql_result) > 0){
                                                    $counter = 0;
                                                    echo '<div class="row" style="justify-content: space-evenly">';
                                                    while($row = $sql_result->fetch_assoc()){
                                                        $counter++;
                                                        ?>
                                                        <div class="cutoff-box-contents">
                                                            <div class="month-and-button">
                                                                <div class="tagmonth">
                                                                    <?php echo $row['month'] .' '. $row['year'] ?>
                                                                </div>
                                                                <div class="threedots">
                                                                     <i class="fa-solid fa-ellipsis dropdown-icon" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                <button type="submit" name="view_emp_thirteen" value="<?php echo $row['id']?>" class="dropdown-item">View</button>
                                                                                <button type="button" name="delete_cutoff" class="dropdown-item deletecut" data-bs-toggle="modal" data-bs-target="#delete_cutoffs" value="<?php echo $row['id']?>">Delete</button>
                                                                                <button type="button" name="add_emp" class="dropdown-item addcutoff" data-bs-toggle="modal" data-bs-target="#add_employee" value="<?php echo $row['id']?>">Add Employee</button>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="box-for-dateperiod">
                                                                <div class="rectangle-dateperiod">
                                                                        <div class="calendar-dateperiod">
                                                                            <i class="fa-regular fa-calendar" style="color: #0d0d0d;"></i>
                                                                            Date Period
                                                                        </div>
                                                                        <div class="startdate-enddate">
                                                                            <?php echo $row['start_date'] .' to '. $row['end_date']?>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if($counter % 2 == 0 && $counter != mysqli_num_rows($sql_result)) {
                                                            echo '</div><div class="row" style="justify-content: space-evenly">'; // Isara ang naunang row at buksan ang bagong row
                                                        }
                                                    }
                                                    echo '</div>';
                                                }
                                            ?>
                                        </form>
                                    </div>
                                </div>






                            <div id="duplicateModal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                                    </div>
                                    <h4 class="mt-3">13th Month Cutoff is already existed!</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="insertedModal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                    <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; color: green"></i>
                                    </div>
                                    <h4 class="mt-3">Cutoff for 13th month is created successfully!</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="deletecompletemodal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                    <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; color: green"></i>
                                    </div>
                                    <h4 class="mt-3">Cutoff for 13th month is deleted successfully!</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="notdeletemodal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                                    </div>
                                    <h4 class="mt-3">Error 13th Month not delete</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="existedModal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                                    </div>
                                    <h4 class="mt-3">The employee is already existed on cutoff</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <!-- Overlay div -->
                            <div class="overlay"></div>

                            <div id="employeeinsertedModal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                    <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; color: green"></i>
                                    </div>
                                    <h4 class="mt-3">Employee added on cutoff successfully</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="noattendanceModal" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                                    </div>
                                    <h4 class="mt-3">No attendance found for employee on the cutoff</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>

                            <div id="nofound" class="modals">
                                <span class="close">&times;</span>
                                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                                    </div>
                                    <h4 class="mt-3">No attendance found</h4>
                                </div>
                                <div class="btn-footer w-100 d-flex justify-content-end mt-3">
                                    <button class="btn border border-black btn-closes">Close</button>
                                </div>
                            </div>


                  </div>
               </div>
            </div>
         </div>
                      


<script>
    $(document).ready(function() {
        $('.deletecut').click(function() {
            var id = $(this).val();
            $('#id-cutoffs').val(id);
        });
    });
    $(document).ready(function() {
        $('.addcutoff').click(function() {
            var id = $(this).val();
            $('#id-cutoffs-add').val(id);
        });
    });
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
    urlParams.delete(modalId === 'duplicateModal' ? 'error' : modalId === 'insertedModal' ? 'inserted' : modalId === 'deletecompletemodal' ? 'deleted' :  modalId === 'notdeletemodal' ? 'notdeleted' : modalId === 'existedModal' ? 'existed' : modalId === 'employeeinsertedModal' ? 'employee' :  modalId === 'noattendanceModal' ? 'noattendance' : 'notfound');
    var newUrl = window.location.pathname + '?' + urlParams.toString();
    window.history.replaceState({}, document.title, newUrl);
  }

  // Check if the URL contains a parameter and show the modal accordingly
  window.onload = function () {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
      showModal('duplicateModal', 'Duplicate Group Name!');
    }
    if (urlParams.has('inserted')) {
      showModal('insertedModal', 'Successfully Inserted!');
    }
    if (urlParams.has('deleted')) {
      showModal('deletecompletemodal', 'Successfully Deleted!');
    }
    if (urlParams.has('notdeleted')) {
      showModal('notdeletemodal', 'Error Deleted!');
    }
    if (urlParams.has('existed')) {
      showModal('existedModal', 'Error Existing!');
    }
    if (urlParams.has('employee')) {
      showModal('employeeinsertedModal', 'Success Employee!');
    }
    if (urlParams.has('noattendance')) {
      showModal('noattendanceModal', 'No Employee attendance!');
    }
    if (urlParams.has('notfound')) {
      showModal('nofound', 'No Data Found!');
    }
  };

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
      $(document).ready(function() {
    $('#departmentDropdown').change(function() {
        var selectedValue = $(this).val();
        // Send selectedValue to a PHP script via AJAX
        $.ajax({
            type: 'POST',
            url: 'update_selected_department.php', // Create this PHP file to handle the AJAX request
            data: { department: selectedValue },
            success: function(response) {
                $('#selectedDepartment').text(response);

                // Fetch employee options based on the selected department
                $.ajax({
                    type: 'POST',
                    url: 'create_cutoff_getEmp.php', // Create this PHP file to generate employee options
                    data: { department: response },
                    success: function(employeeOptions) {
                        var updatedEmployeeOptions = '<label for="" class="form-label">Select Employee</label>' + employeeOptions;

                        // Update the employee dropdown with the label and new options
                        $('#employeeDropdown').html(updatedEmployeeOptions);
                        console.log('Employee options updated successfully.');

                        var selectedEmployeeIDs = $('#multi_option').val();
                        console.log('Selected Employee IDs:', selectedEmployeeIDs);
                    }
                });
            }
        });
    });
});
</script>

<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_option' 
	});
</script>


<script>
    $(document).ready(function() {
    $('#departmentDropdowns').change(function() {
        var selectedValue = $(this).val();
        
        // Send selectedValue to a PHP script via AJAX
        $.ajax({
            type: 'POST',
            url: 'update_selected_department.php', // Create this PHP file to handle the AJAX request
            data: { department: selectedValue },
            success: function(response) {
                $('#selectedDepartment').text(response); // Update the value in the <p> tag

                // Fetch employee options based on the selected department
                $.ajax({
                    type: 'POST',
                    url: 'get_employee_options.php', // Create this PHP file to generate employee options
                    data: { department: response },
                    success: function(employeeOptions) {
                        // Update the employee dropdown with new options
                        $('#employeeDropdowns').html(employeeOptions);
                        console.log('Employee options updated successfully.');

                        // Collect selected employee IDs
                        var selectedEmployeeIDs = $('#multi_options').val();
                        console.log('Selected Employee IDs:', selectedEmployeeIDs);

                        // Now submit the form with the selected employee IDs
                      
                    }
                });
            }
        });
    });
});
</script>

<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_options' 
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
</body>
</html>