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
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/piece_rate.css">
    <title>HRIS | Work Load</title>
    <script>
        function closeErrorMessage() {
            document.getElementById('error-message').style.display = 'none';
            window.history.replaceState({}, document.title, 'cash_advance');
        }
    </script>
    
</head>
<body>

<header>
        <?php include("header.php")?>
    </header>
    
    <style>
      .odd .dataTables_empty{
        font-weight: 400;
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
    

    <!-- insert modal -->

    <form action="Data Controller/Pakyawan/insert_workload.php" method="POST">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel" >Work Load</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <div class="form-group">
                    <div id="error-msg" class="alert alert-danger mt-2" style="display: none;">Start Date and End Date cannot be the same for Weekly frequency</div>
                    <?php
                        include 'config.php';
                        

                        $sql = "SELECT employee_tb.*, classification_tb.classification FROM employee_tb
                                INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                                WHERE employee_tb.classification = 3";

                        $result = mysqli_query($conn, $sql);
                        $options = "";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $options .= "<option value='".$row['empid']."'>".$row['fname']."  ".$row['lname']."</option>";
                        }
                        ?>

                        <label for="">Employee Name:</label>
                        <select name="employee" id="employeeDropdown" class="form-select" style="color: black" required>
                            <option value="" disabled selected>Select Employee</option> 
                            <?php echo $options; ?>
                        </select><br>

                        

                        <!-- <label for="" class="mt-3">Unit Type:</label>
                        <select name="unit_type"  class="form-select" style="color: black" required>
                            <option value="" disabled selected>Select Unit Type</option>
                            <div id="selectedPieceDisplay"></div>
                        </select><br> -->

                        <div id="unitTypeDropdown">
                        </div>
     
                    <label for="frequency">Frequency</label><br>
                    <input type="text" id="frequencyInput" name="work_frequency" readonly class="form-control"required ><br>

                    <label for="">Start Date</label><br>
                    <input type="date" required name="start_date" class="form-control" id="startDate" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required><br>

                    <label for="">End Date</label><br>
                    <input type="date" required name="end_date" class="form-control" id="endDate" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="endDate" required>   

                    <!-- <label for="">Unit Type</label><br> -->
                    <input class="form-control type_unit d-none" type="text" name="unit_type_text" id="">


                    <label for="" class="mt-3">Work Pay</label><br>
                    <input type="text" name="work_pay" id="unit_work" class="form-control unit_work" readonly required>


                    
                    <!-- <p id="workPay" class="mt-2"></p> -->

                    <script>
                        const employeeDropdown = document.getElementById("employeeDropdown");
                        const unitTypeDropdown = document.getElementById("unitTypeDropdown");
                        const selectedPieceDisplay = document.getElementById("selectedPieceDisplay"); // Added this line

                        employeeDropdown.addEventListener("change", function() {
                            const selectedEmployeeId = employeeDropdown.value;

                            fetch('get_unit_types.php?empid=' + selectedEmployeeId)
                                .then(response => response.text())
                                .then(data => {
                                    unitTypeDropdown.innerHTML = data;

                                    function updateTotal() {
                                    const checkboxes = unitTypeDropdown.querySelectorAll('.checkbox-item:checked');
                                    let netTotal = 0;
                                    let unitTypes = []; // Initialize an array to store selected unit types

                                    checkboxes.forEach(checkbox => {
                                        const quantityInput = checkbox.closest('tr').querySelector('.quantity-item');
                                        const spanElement = checkbox.closest('td').querySelector('.subtotal');
                                        const totalAmountInput = checkbox.closest('tr').querySelector('.total-amount');
                                        const unitTypeElement = checkbox.closest('td').querySelector('.unit_type'); // Get the unit_type element
                                        const unit_type = unitTypeElement.textContent.trim(); // Get the text content and remove leading/trailing whitespace

                                        // Log the unit_type for each checkbox
                                        console.log('Unit type: ' + unit_type);

                                        // Check if quantity is empty or not
                                        const quantityValue = parseFloat(quantityInput.value);
                                        if (isNaN(quantityValue) || quantityValue === 0) {
                                            totalAmountInput.value = ''; // Clear the total-amount if quantity is empty or not a number
                                        } else {
                                            // Calculate the product
                                            const spanContent = parseFloat(spanElement.textContent);
                                            const product = quantityValue * spanContent;

                                            // Set the product as the value of total-amount
                                            totalAmountInput.value = product;

                                            // Add the product to netTotal
                                            netTotal += product;

                                            // Add the unit_type value to the array
                                            unitTypes.push(unit_type);
                                        }
                                    });

                                    // Set the selected unit types as the value of the unitTypesInput
                                    const unitTypesInput = document.querySelector('.type_unit');
                                    unitTypesInput.value = unitTypes.join(', ');

                                    // Set the netTotal value
                                    const unitWorkInput = document.querySelector('.unit_work');
                                    unitWorkInput.value = netTotal.toFixed(2);

                                    // Log the netTotal
                                    console.log('Net Total: ' + netTotal);
                                }

                                    // Add an event listener to the checkboxes
                                    unitTypeDropdown.addEventListener('change', function(event) {
                                        if (event.target.classList.contains('checkbox-item')) {
                                            const quantityInput = event.target.closest('tr').querySelector('.quantity-item');
                                            const spanElement = event.target.closest('td').querySelector('.subtotal'); // Get the <span> element
                                            // const unit_type = event.target.closest('td').querySelector('.unit_type');
                                          
                                            // Enable/disable the quantity input based on checkbox state
                                            if (event.target.checked) {
                                                quantityInput.removeAttribute('disabled');
                                                console.log()
                                                
                                            } else {
                                                quantityInput.setAttribute('disabled', 'disabled');
                                                quantityInput.value = ''; // Clear the value
                                                
                                            }

                                            // console.log(unit_type.textContent);

                                            // Update the total when checkboxes change
                                            updateTotal();
                                        }
                                    });

                                    // Add an event listener to the quantity input fields
                                    unitTypeDropdown.addEventListener('input', function(event) {
                                        if (event.target.classList.contains('quantity-item')) {
                                            // Update the total when quantity input changes
                                            updateTotal();
                                        }
                                    });

                                    // Initial calculation when the page loads
                                    updateTotal();
                                }) 
                                .catch(error => console.error('Error:', error));
                        });
                    </script>



                    
                    <script>
                        const unitTypeDropdowns = document.getElementById("unitTypeDropdown");
                        const unitWorkInput = document.getElementById("unit_work");

                        unitTypeDropdown.addEventListener("change", function() {
                            if (unitTypeDropdown.value !== "") {
                                unitWorkInput.removeAttribute("disabled");
                            } else {
                                unitWorkInput.setAttribute("disabled", "disabled");
                            }
                        });

                        function updateWorkPay(value) {
                            // Your updateWorkPay function logic here
                        }
                    </script>
                      
                    <script>
                          document.getElementById("unit_work").addEventListener("input", function(event) {
                            var inputValue = event.target.value;
                            var sanitizedValue = inputValue.replace(/[-a-zA-Z]/g, ''); // Remove hyphens and alphabetic characters

                            if (inputValue !== sanitizedValue) {
                                event.target.value = sanitizedValue;
                                updateWorkPay(sanitizedValue); // Call your updateWorkPay function with sanitized value
                            }
                        });
                    </script>
                    <script>
                          function updateWorkPay(unit_work) {
                          

                          let unit_type = document.getElementById("unitTypeDropdown").value;
                          let selectedPiece = document.getElementById("selectedPiece");
                          let selectedWork = document.getElementById("selectedWork");
                          let workPay = document.getElementById("workPay");

                          const xhr = new XMLHttpRequest();
                          xhr.onreadystatechange = function() {
                              if (xhr.readyState === 4 && xhr.status === 200) {
                                  var response = this.responseText;
                                  console.log(response);   
                                  workPay.textContent = response;                     
                                  selectedPiece.textContent = unit_type;
                                  selectedWork.textContent = unit_work;
                              }
                          };

                          xhr.open("POST", "process_selected_piece.php", true);
                          var formData = new FormData();
                          formData.append("unit_work", unit_work);
                          formData.append("unit_type", unit_type);
                          xhr.send(formData);
                      }                 

                    </script>
                </div>
                </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" id="btn_save" class="btn btn-primary" disabled>Submit</button>
                      
                  </div>
              </div>
          </div>
      </div>
    </form>



    

<!-- edit modal -->
<form action="Data Controller/Pakyawan/dataGet.php" method="POST">
  <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <input type="text" id="id" name="id" style="display: none;">
          <h5 class="modal-title" id="updateLabel">Work Load</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="" class="">Employee Name:</label>
            <input type="text" id="employee_name" class="form-control" readonly><br>

            <label for="">Frequency</label><br>
            <input type="text" name="work_frequency" id="work_frequency" class="form-control frequency" readonly><br>

            <label for="">Start Date</label><br>
            <input type="date" required name="start_date" class="form-control start_date" id="start_date" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" ><br>

            <label for="">End Date</label><br>
            <input type="date" required name="end_date" class="form-control end_date" id="end_date" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly>

            <input type="hidden" id="employee_ids" name="employee">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatedata" class="btn btn-primary" id="editBtn_save">Submit</button>
        </div>
      </div>
    </div>
  </div>
</form>

   

    <!-- delete modal -->

    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Pakyawan/delete.php" method="POST">
      <div class="modal-body">

        <input type="hidden" name="id" id="delete_id">
       

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


    <div class="pr-container">
        <div class="header-title">
            <h1 style="font-size: 32px">Pakyawan Work Load</h1>
            <button  class="btn btn-primary btn_add" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Work</button>
        </div>
        <div class="pakyawan-validation">
      <!-- Add the validation message container -->
      <?php
        if (isset($_GET['validationFailed'])) {
            echo '<div class="alert alert-danger validation-message d-flex flex-row justify-content-between" role="alert"><p> Validation Failed: The start date is within the range of an existing record.</p><button type="button" class="btn-close" onclick="removeValidationMessage()"></button></div>';
        }
        ?>
         <?php
                if(isset($_GET['error'])){
                    echo ' <div class="error-handler w-100 mb-3 mt-3" style="height: 3em" id="error-message">';
                        echo '<div class="w-100 bg-danger d-flex flex-row align-items-center justify-content-between pl-4 pr-4" style="height: 100%">';
                            echo '<p class="error-message" style="font-size: 0.9em; color: #fff;">There was an error in the input. </p>';
                            echo '<button style="background-color: inherit; border: none; color: #fff; font-size: 1.1em"onclick="closeErrorMessage()"> <i class="fa-solid fa-x"></i> </button>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
    </div>
        <div class="table mt-5">
      <div class="table-responsive" id="table-responsive">
        <table id="order-listing" class="table table-responsive">
          <thead>
            <th>Name</th>
            <th>Unit Type</th>
       
            <th>Start Date</th>
            <th>End Date</th>
            
            <th>Work Pay</th>
            <th>Actions</th>
            <th style='display:none'>Frequency</th>
            <th style='display: none;'>Employee ID</th> 
            <th style='display: none;'>work load id</th>
          
        </thead>
        <tbody>
        <?php
        include 'config.php';

        

        $sql = "SELECT pakyawan_based_work_tb.id AS load_id, pakyawan_based_work_tb.work_frequency, pakyawan_based_work_tb.employee, pakyawan_based_work_tb.start_date, pakyawan_based_work_tb. end_date, pakyawan_based_work_tb.unit_type_text, pakyawan_based_work_tb.work_pay, employee_tb.empid, employee_tb.fname, employee_tb.lname FROM pakyawan_based_work_tb 
                INNER JOIN employee_tb WHERE pakyawan_based_work_tb.employee = employee_tb.empid";
          // $sql ="SELECT * FROM pakyawan_based_work_tb";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td style='font-weight: 400'>" . $row['fname'] . " " . $row['lname'] . "</td>";
                echo "<td style='font-weight: 400'>" . $row['unit_type_text'] . "</td>";
                
                echo "<td style='font-weight: 400'>" . $row['start_date'] . "</td>";
                echo "<td style='font-weight: 400'>" . $row['end_date'] . "</td>";
                echo "<td style='font-weight: 400'> ₱ ".$row['work_pay']."</td>";
                echo "<td style='font-weight: 400'>
                            <button class='editbtn' style='margin-right: 0.6em; border: none; background-color: inherit'>
                                <i class='fa-solid fa-pen-to-square' style='font-size: 1.4em' title='Edit' data-bs-toggle='modal' data-bs-target='#updateModal'></i>
                            </button>

                            <button class='deletebtn' title='Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style='border: none; background-color: inherit'>
                                <i class='fa-sharp fa-solid fa-trash' style='font-size: 1.4em'></i>
                            </button>
                      </td>";
                echo "<td style='display: none'>" . $row['work_frequency'] . "</td>";  
                echo "<td style='display: none'>" . $row['employee'] . "</td>";
                echo "<td style='display: none'>" . $row['load_id'] . "</td>";
                echo "</tr>";
            }
        }


        mysqli_close($conn);
        ?>
    </tbody>
</table>

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
        <button class="btn border border-black btn-closes" id="removeParamButton">OK</button>
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



    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
   var today = new Date();

    // Set the max attribute to today's date
    var maxDateFormatted = today.toISOString().split("T")[0];
    document.getElementById("startDate").setAttribute("max", maxDateFormatted);
    document.getElementById("start_date").setAttribute("max", maxDateFormatted);

    // Calculate the date 18 years ago
    var minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

    // Format the minDate as YYYY-MM-DD
    var minDateFormatted = minDate.toISOString().split("T")[0];

    // Set the min attribute of the input element
    document.getElementById("startDate").setAttribute("min", minDateFormatted);
    document.getElementById("start_date").setAttribute("min", minDateFormatted);

</script>


    <script>
document.getElementById("employeeDropdown").addEventListener("change", function() {
    var selectedEmployee = this.value;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_work_frequency.php?empid=" + selectedEmployee, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var workFrequency = xhr.responseText;
                document.getElementById("frequencyInput").value = workFrequency; // Set work_frequency as the value
                handleFrequencyChange(); // Update end date and validation
            } else {
                console.error("Error fetching work frequency.");
            }
        }
    };
    xhr.send();
});
</script>

    <script>
        // Function to remove the validation message
        function removeValidationMessage() {
            window.location.href = 'pakyawan_work'; // Redirect to the same page to remove the validation message from the URL
        }

        // Function to check if the validation message is present and scroll to it
        function checkValidationMessage() {
            if ($('#validation-message').length) {
                var offset = $('#validation-message').offset().top;
                $(window).scrollTop(offset);
            }
        }

        $(document).ready(function() {
            checkValidationMessage();
        });
    </script>



    <script>
// Get the frequency select element
var frequencySelect = document.getElementById('frequencyInput');

// Get the start date and end date input fields
var startDateInput = document.getElementById('startDate');
var endDateInput = document.getElementById('endDate');

// Get the Save button element
var saveButton = document.getElementById('btn_save');

// Function to handle changes in the frequency select element
function handleFrequencyChange() {
  var selectedFrequency = frequencySelect.value;
  if (selectedFrequency === '') {
    startDateInput.disabled = true;
    endDateInput.disabled = true;
    endDateInput.readOnly = false;
    endDateInput.value = '';
    // console.log("haha");
  } else if (selectedFrequency === 'Daily') {
    startDateInput.disabled = false;
    endDateInput.disabled = false;
    endDateInput.readOnly = true;
    updateEndDate(); // Update the end date initially
    // console.log("hehe");
  } else {
    startDateInput.disabled = false;
    endDateInput.disabled = false;
    endDateInput.readOnly = false;
    endDateInput.value = '';
    console.log("hihi");
  }

  validateEndDate(); // Run validation when frequency changes
}

// Function to handle the validation and disable dates before the selected start date in the end date field
function validateEndDate() {
  var startDate = new Date(startDateInput.value);
  var endDate = new Date(endDateInput.value);

  if (startDateInput.value) {
    endDateInput.min = startDateInput.value; // Set the minimum date for the end date
  }

  if (frequencySelect.value === 'Weekly' && startDate.getTime() === endDate.getTime()) {
    saveButton.disabled = true; // Disable the Save button

    var errorMessage = document.getElementById('error-msg');

    if (!errorMessage) {
      errorMessage = document.createElement('div');
      errorMessage.id = 'error-msg';
      errorMessage.className = 'alert alert-danger mt-2';
      errorMessage.innerText = 'Start Date and End Date cannot be the same for Weekly frequency';

      var frequencyDiv = document.getElementById('frequencyInput').parentNode;
      frequencyDiv.parentNode.insertBefore(errorMessage, frequencyDiv);
    }
  } else {
    saveButton.disabled = false; // Enable the Save button

    var errorMessage = document.getElementById('error-msg');

    if (errorMessage) {
      errorMessage.remove();
    }
  }
}

// Function to update the end date with the start date value when the frequency is "Daily"
function updateEndDate() {
  var selectedFrequency = frequencySelect.value;
  if (selectedFrequency === 'Daily') {
    endDateInput.value = startDateInput.value;
  }
}

// Add event listeners to the frequency select element and start date input field
frequencySelect.addEventListener('change', handleFrequencyChange);
startDateInput.addEventListener('change', function () {
  updateEndDate();
  validateEndDate();
});
endDateInput.addEventListener('change', validateEndDate);

// Call the function initially to set the initial state of the input fields
handleFrequencyChange();

</script>



    <script>
            $(document).ready(function (){
                $('.deletebtn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id').val(data[8]);
                    
                    

                });
            });
        </script>

    <script> //FOR UPDATE TRANSFER MODAL 
        $(document).ready(function(){
                                $('.editbtn').on('click', function(){
                                    $('#updateModal').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    // console.log(data);
                                    //id_colId
                                    $('#id').val(data[8]);
                                    $('#employee_ids').val(data[7]);
                                    // $('#unit_types').val(data[2]);
                                    $('#employee_name').val(data[0]);
                                    // $('#unit_type').val(data[4]);
                                    // $('#unit_work').val(data[5]);
                                    $('#start_date').val(data[2]);
                                    $('#end_date').val(data[3]);
                                    $('#work_frequency').val(data[6]);
                                   
                                    const frequency = document.querySelector(".frequency").value;
                                    const start_date = document.querySelector(".start_date");
                                    const end_date = document.querySelector(".end_date");


                                    if(frequency !== 'Daily'){
                                        end_date.removeAttribute("readonly");
                                        console.log("haha"); 
                                    }else{
                                      console.log("hehe");
                                      start_date.addEventListener("change", function(){
                                        end_date.value = start_date.value;
                                      });
                                    
                                    }
                                   
                                });
                            });
            //FOR UPDATE TRANSFER MODAL END
    </script> 
 
    <script>
// sched form modal

let Modal = document.getElementById('schedules-modal-update');

//get open modal
let modalBtn = document.getElementById('sched-update');

//get close button modal
let closeModal = document.getElementsByClassName('sched-update-close')[0];

//event listener
modalBtn.addEventListener('click', openModal);
closeModal.addEventListener('click', exitModal);
window.addEventListener('click', clickOutside);

//functions
function openModal(){
    Modal.style.display ='block';
}

function exitModal(){
    Modal.style.display ='none';
}

function clickOutside(e){
    if(e.target == Modal){
        Modal.style.display ='none';    
    }
}
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

<script> 
        $(document).ready(function(){
                $('.sched-update').on('click', function(){
                                    $('#schedUpdate').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    console.log(data);
                                    //id_colId
                                    $('#empid').val(data[9]);
                                    $('#sched_from').val(data[6]);
                                    $('#sched_to').val(data[7]);
                                    $('#empName').val(data[0]);
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