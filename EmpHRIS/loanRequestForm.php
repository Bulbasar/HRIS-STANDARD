<?php
    session_start();
   include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <title>HRIS | Employee List</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    <style>
        input{
            border: #ccc 1px solid !important;    
        }
        select{
            border:#ccc 1px solid !important;    
        }
        textarea{
            border: #ccc 1px solid !important;
        }
    </style>

    <form action="Data Controller/Loan Request/loanRequestFormController.php" method="POST">
    <div class="loan-req-form-container">
        <div class="payroll-loan-title">
            <h1>Payroll Loan Details</h1>
        </div>
        <div class="row" style="width:92%; margin: auto; margin-top:20px;">
            <div class="col-6" style="padding: 0 30px 0 30px;">  


                <!-- hidden type  -->
                <?php 
                    $currentTimestamp = time();
                    $currentDate = date('Y-m-d', $currentTimestamp); 

                    $currentMonthText = date('F');

                    $currentYear = date('Y');
                    ?>

                    <div id="dateError" style="color: red;"></div>
                    <!-- <label for="loan_date">Loan Date</label><br> -->
                    <input type="date" class="d-none" name="loan_date" style="height:50px;" id="loan_date" value="<?php echo $currentDate ?>" >
                    <input type="date" class="d-none" name="start_date" style="height:50px;" value="<?php echo $currentDate ?>" >
                    <input type="text" class="d-none" name="loan_status" value="PENDING">
                    <input type="text" class="d-none" name="month" value="<?php echo $currentMonthText ?>" id="">
                    <input type="text" class="d-none" name="year" value="<?php echo $currentYear ?>" id="">
                    <input type="date" class="d-none" name="end_date" id="endFourMonths">
                    <input type="hidden" name="empid" value="<?php echo $_SESSION['empid'];?>" id="">
                    <input type="hidden" name="status" value="<?php echo 'Pending'; ?>" id="">

                <div class="form-group">
                    <label for="loan_type">Loan Type</label><br>
                    <select name="loan_type" class="form-select" style="height:50px; color: black" required>
                        <option value="" selected="selected" class="selectTag" style="color: gray;" >Select Loan Type</option>
                        <option value="Company Emergency Loan">Company Emergency Loan</option>
                        <option value="Pag-ibig Emergency Loan">Pag-ibig Emergency Loan</option>
                        <option value="Company Loan Car"> Company Loan Car</option>
                        <option value="SSS Salary Loan">SSS Salary Loan</option>
                        <option value="GSIS Emergency Loan">GSIS Emergency Loan</option>
                        <option value="Company Motorcycle Loan">Company Motorcycle Loan</option>
                    </select>
                </div>
                <div class="form-group cutoff-no" style="display:flex; flex-direction: row; height: 100px;">
                <div>
                    <label for="">Cutoff No.</label><br>
                    <select name="cutoff_no" id="cutoff_no" class='form-select' style="width: 378px; height:50px; padding: 10px; color: black" onchange="calculate()" required>
                        <option value="" selected disabled>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="4">4</option>
                    </select>
                </div> 
                    <div style="display:flex; align-items:center; height: 60px; margin-top: 27px;">  
                        <button type="button"  data-bs-toggle="modal" data-bs-target="#loanForm" style="width: 240px; height:50px; margin-left: 10px; outline:none; border: none; border-radius: 5px; background-color: #e6e2e2; color: rgb(128, 55, 224); font-weight: 400; font-size: 20px; letter-spacing: 2px; " id="loanFormBtn">Forecast Payment</button>
                    </div>
                </div>
                <div class="form-group loan-remarks">
                    <label for="remarks">Remarks</label><br>
                    <textarea name="remarks" id="" rows="5" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-6" style="padding: 0 30px 0 30px;">
                <div class="form-group d-none">
                    
                </div>
                <div class="form-group">
                    <label for="payable_amount">Payable Amount</label><br>
                    <input type="number" name="payable_amount" class="form-control" style="height:50px; text-decoration: none;" id="payable_amount" oninput="calculate()" required> 
                </div>
                <style>
                      /* Para mawala ang up and down arrow if input type is number */
                    input[type="number"]::-webkit-inner-spin-button,
                    input[type="number"]::-webkit-outer-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                         }
                </style>

                <div class="form-group">
                    <label for="amortization">Amortization</label><br>
                    <input type="text" name="amortization" class="form-control" id="amortization" style="height:50px" readonly>

                    <!-- hidden type  -->
                    <input type="hidden" name="loan_status" value="PENDING">
                    
                </div>
                <div class="form-group">
                    <label for="applied_cutoff">Applied Cutoff</label><br>
                    <select name="applied_cutoff" class="form-select" style="height:50px; color: black" id="cutoff">
                        <option value="" selected disabled>Cutoff</option>
                        <option value="Every Cutoff">Every Cutoff</option>
                        <option value="First Cutoff">First Cutoff</option>
                        <option value="Last Cutoff">Last Cutoff</option>
                    </select>
                </div>
                <div class="form-group loan-req-btn">
                    <button><a href="loanRequest.php" style="text-decoration: none; color:black;">Cancel</a></button>
                    <button style="color: blue;" id="saveButton">Save</button>
                </div>
            </div>   
        </div>
        </form>
        <div style="border: #ccc 1px solid; width: 95%; margin: auto; margin-top: 50px; margin-bottom: 50px;"></div>
        <div class="amortization-container">
            <div class="amortization-title">
                <h1>Amortization History</h1>
            </div>
            <div class="amortization-table">
                <table class="table-hover table table-borderless" style="width:95%; margin:auto; margin-top: 20px; border:none; ">
                    <thead style="background-color: #f4f4f4;">
                        <th>Year</th>
                        <th>Month</th>
                        <th>Cutoff No.</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 400">2023</td>
                            <td style="font-weight: 400">April</td>
                            <td style="font-weight: 400">2</td>
                            <td style="font-weight: 400">200</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="loanForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 700px;">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title">Loan Forecast</h1>
                </div>
                <div class="modal-body">
                    <div class="loan-forecast-balance">
                        <p>Balance: 0</p>
                    </div>
                    <div class="table-responsive" style="margin-left: 30px;width:600px;">
                        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
                            <thead>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Cutoff No.</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <?php
                                    $conn = mysqli_connect("localhost", "root", "" , "hris_db");
                                    $sql = "SELECT * FROM payroll_loan_tb AS payloan
                                            INNER JOIN employee_tb AS emp
                                            ON(payloan.empid = emp.empid)";
                                    $results = $conn->query($sql);

                                    if($results->num_rows > 0){
                                        while($rows = $results->fetch_assoc()){
                                            echo "<tr>
                                                    <td style='font-weight:400'>".$rows['year']."</td>
                                                    <td style='font-weight:400'>".$rows['month']."</td>
                                                    <td style='font-weight:400'>".$rows['cutoff_no']."</td>
                                                    <td style='font-weight:400'>".$rows['payable_amount']."</td>
                                                    <td style='font-weight:400' >".$rows['loan_status']."</td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No loan payments found</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border: none; background-color: inherit; font-size: 20px;">Close</button>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
//sa col_BAL_amount is if it reaches 0 then get the current date and display it sa end date


function getHalfOfMonth() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the first day of the current month
  const firstDayOfMonth = new Date(currentDate.getFullYear(), currentMonth, 1);

  // Calculate the last day of the current month
  const lastDayOfMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 0);

  // Calculate the middle day of the current month
  const middleDayOfMonth = new Date(
    currentDate.getFullYear(),
    currentMonth,
    Math.floor((firstDayOfMonth.getDate() + lastDayOfMonth.getDate()) / 2)
  );

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    middleDayOfMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (middleDayOfMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    middleDayOfMonth.getFullYear();

  return formattedDate;
}

function getEndOfMonth() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the last day of the current month
  const lastDayOfMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 0);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    lastDayOfMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (lastDayOfMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    lastDayOfMonth.getFullYear();

  return formattedDate;
}

function getHalfOfNextMonth() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the first day of the next month
  const firstDayOfNextMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 1);

  // Calculate the last day of the next month
  const lastDayOfNextMonth = new Date(currentDate.getFullYear(), currentMonth + 2, 0);

  // Calculate the middle day of the next month
  const middleDayOfNextMonth = new Date(
    firstDayOfNextMonth.getFullYear(),
    firstDayOfNextMonth.getMonth(),
    Math.floor((firstDayOfNextMonth.getDate() + lastDayOfNextMonth.getDate()) / 2)
  );

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    middleDayOfNextMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (middleDayOfNextMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    middleDayOfNextMonth.getFullYear();

  return formattedDate;
}
function getEndOfNextMonth() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the first day of the next month
  const firstDayOfNextMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 1);

  // Calculate the last day of the next month
  const lastDayOfNextMonth = new Date(currentDate.getFullYear(), currentMonth + 2, 0);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    lastDayOfNextMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (lastDayOfNextMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    lastDayOfNextMonth.getFullYear();

  return formattedDate;
}

function getHalfOfTwoMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 2
  const targetMonth = currentMonth + 2;

  // Create a Date object for the first day of the target month
  const firstDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth, 1);

  // Create a Date object for the last day of the target month
  const lastDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth + 1, 0);

  // Calculate the middle day of the target month
  const middleDayOfTargetMonth = new Date(
    firstDayOfTargetMonth.getFullYear(),
    firstDayOfTargetMonth.getMonth(),
    Math.floor((firstDayOfTargetMonth.getDate() + lastDayOfTargetMonth.getDate()) / 2)
  );

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    middleDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (middleDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    middleDayOfTargetMonth.getFullYear();

  return formattedDate;
}

function getEndOfTwoMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 2
  const targetMonth = currentMonth + 2;

  // Create a Date object for the first day of the target month
  const firstDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth, 1);

  // Create a Date object for the last day of the target month
  const lastDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth + 1, 0);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    lastDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (lastDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    lastDayOfTargetMonth.getFullYear();

  return formattedDate;
}

function getHalfOfThreeMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 3
  const targetMonth = currentMonth + 3;

  // Create a Date object for the first day of the target month
  const firstDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth, 1);

  // Create a Date object for the last day of the target month
  const lastDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth + 1, 0);

  // Calculate the middle day of the target month
  const middleDayOfTargetMonth = new Date(
    firstDayOfTargetMonth.getFullYear(),
    firstDayOfTargetMonth.getMonth(),
    Math.floor((firstDayOfTargetMonth.getDate() + lastDayOfTargetMonth.getDate()) / 2)
  );

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    middleDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (middleDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    middleDayOfTargetMonth.getFullYear();

  return formattedDate;
}

function getEndOfThreeMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index, so January is 0, February is 1, etc.)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 3
  const targetMonth = currentMonth + 3;

  // Create a Date object for the first day of the target month
  const firstDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth, 1);

  // Create a Date object for the last day of the target month
  const lastDayOfTargetMonth = new Date(currentDate.getFullYear(), targetMonth + 1, 0);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    lastDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (lastDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    lastDayOfTargetMonth.getFullYear();

  return formattedDate;
}

function getHalfOfFourMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 4
  const targetMonth = (currentMonth + 4) % 12; // Ensure it wraps around to stay within 0-11

  // Calculate the target year
  const targetYear = currentDate.getFullYear() + Math.floor((currentMonth + 4) / 12);

  // Create a Date object for the middle day of the target month
  const middleDayOfTargetMonth = new Date(targetYear, targetMonth, 15);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    middleDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (middleDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    middleDayOfTargetMonth.getFullYear();

  return formattedDate;
}

function getEndOfFourMonthsAhead() {
  // Get the current date
  const currentDate = new Date();

  // Get the current month (0-based index)
  const currentMonth = currentDate.getMonth();

  // Calculate the target month, which is the current month + 4
  const targetMonth = (currentMonth + 4) % 12; // Ensure it wraps around to stay within 0-11

  // Calculate the target year
  const targetYear = currentDate.getFullYear() + Math.floor((currentMonth + 4) / 12);

  // Create a Date object for the last day of the target month
  const lastDayOfTargetMonth = new Date(targetYear, targetMonth + 1, 0);

  // Format the date as "dd/mm/yyyy"
  const formattedDate =
    lastDayOfTargetMonth.getDate().toString().padStart(2, '0') +
    '/' +
    (lastDayOfTargetMonth.getMonth() + 1).toString().padStart(2, '0') +
    '/' +
    lastDayOfTargetMonth.getFullYear();

  return formattedDate;
}


// const endOfFourMonthsAhead = getEndOfFourMonthsAhead; // No parentheses here

// // Log the constant
// console.log(endOfFourMonthsAhead);
// Example usage:

const currentDate = new Date();
const currentDay = currentDate.getDate();

const halfMonth = getHalfOfMonth();
const endMonth = getEndOfMonth();
const halfOneMonth = getHalfOfNextMonth();
const endOneMonth = getEndOfNextMonth();
const halfTwoMonth = getHalfOfTwoMonthsAhead();
const endTwoMonth = getEndOfTwoMonthsAhead();
const halfThreeMonth = getHalfOfThreeMonthsAhead();
const endThreeMonth = getEndOfThreeMonthsAhead();
const halfFourMonth = getHalfOfFourMonthsAhead();
const endFourMonth = getEndOfFourMonthsAhead();
const endFourMonthsInput = document.getElementById("endFourMonths");

// Set the value of the input element to the endDate
// endFourMonthsInput.value = halfMonth;

// console.log("haha", currentDay);


const cutoff_no = document.getElementById("cutoff_no");

const cutoff = document.getElementById("cutoff");

cutoff.addEventListener("change", function(){
    appliedCutoff = cutoff.value; //cutoff type
    cutoffNo = cutoff_no.value; //cutoff number

    // console.log(appliedCutoff);

    if(cutoffNo == '1' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = halfMonth;
        if (currentDay >= 15){
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfMonth;
          endFourMonthsInput.value = `${halfMonth.split('/').reverse().join('-')}`;
        }

    }else if(cutoffNo == '2' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = endMonth;
        if (currentDay >= 15){
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endMonth;
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }
    }else if (cutoffNo == '4' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = endOneMonth;
        if (currentDay >= 15){
          // endFourMonthsInput.value = halfTwoMonth;
          endFourMonthsInput.value = `${halfTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '1' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfMonth;
          endFourMonthsInput.value = `${halfMonth.split('/').reverse().join('-')}`;
        }

    } else if (cutoffNo == '2' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfTwoMonth;
          endFourMonthsInput.value = `${halfTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '4' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfFourMonth;
          endFourMonthsInput.value = `${halfFourMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfThreeMonth;
          endFourMonthsInput.value = `${halfThreeMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '1' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endMonth;
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '2' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endTwoMonth;
          endFourMonthsInput.value = `${endTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '4' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endFourMonth;
          endFourMonthsInput.value = `${endFourMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endThreeMonth;
          endFourMonthsInput.value = `${endThreeMonth.split('/').reverse().join('-')}`;
        }
    }
});


cutoff_no.addEventListener("change", function(){
    appliedCutoff = cutoff.value; //cutoff type
    cutoffNo = cutoff_no.value; //cutoff number

    // console.log(appliedCutoff);

    if(cutoffNo == '1' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = halfMonth;
        if (currentDay >= 15){
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfMonth;
          endFourMonthsInput.value = `${halfMonth.split('/').reverse().join('-')}`;
        }

    }else if(cutoffNo == '2' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = endMonth;
        if (currentDay >= 15){
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endMonth;
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }
    }else if (cutoffNo == '4' && appliedCutoff == 'Every Cutoff'){
        // endFourMonthsInput.value = endOneMonth;
        if (currentDay >= 15){
          // endFourMonthsInput.value = halfTwoMonth;
          endFourMonthsInput.value = `${halfTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '1' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfMonth;
          endFourMonthsInput.value = `${halfMonth.split('/').reverse().join('-')}`;
        }

    } else if (cutoffNo == '2' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfTwoMonth;
          endFourMonthsInput.value = `${halfTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfOneMonth;
          endFourMonthsInput.value = `${halfOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '4' && appliedCutoff == 'First Cutoff'){
      if (currentDay >= 15){
          // endFourMonthsInput.value = halfFourMonth;
          endFourMonthsInput.value = `${halfFourMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = halfThreeMonth;
          endFourMonthsInput.value = `${halfThreeMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '1' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endMonth;
          endFourMonthsInput.value = `${endMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '2' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endTwoMonth;
          endFourMonthsInput.value = `${endTwoMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endOneMonth;
          endFourMonthsInput.value = `${endOneMonth.split('/').reverse().join('-')}`;
        }

    }else if (cutoffNo == '4' && appliedCutoff == 'Last Cutoff'){
      if (currentDay >= 20 && currentDay <= 30) {
          // endFourMonthsInput.value = endFourMonth;
          endFourMonthsInput.value = `${endFourMonth.split('/').reverse().join('-')}`;
        }else{
          // endFourMonthsInput.value = endThreeMonth;
          endFourMonthsInput.value = `${endThreeMonth.split('/').reverse().join('-')}`;
        }
    }
});






// // Example usage:
// console.log(`Half of four months ahead is: ${getHalfOfFourMonthsAhead()}`);


// // Example usage:
// console.log(`End of three months ahead is: ${getEndOfThreeMonthsAhead()}`);


// // Example usage:
// console.log(`Half of three months ahead is: ${getHalfOfThreeMonthsAhead()}`);

// // Example usage:
// console.log(`Half of two months ahead is: ${getHalfOfTwoMonthsAhead()}`);

// // Example usage:
// console.log(`End of the next month is: ${getEndOfNextMonth()}`);

// // Example usage:
// console.log(`Half of the next month is: ${getHalfOfNextMonth()}`);

// // Example usage:
// console.log(`End of the current month is: ${getEndOfMonth()}`);

// // Example usage:
// console.log(`Half of the current month is: ${getHalfOfMonth()}`);


    </script> 

    <script>
  var loanDateInput = document.getElementById('loan_date');
  var saveButton = document.getElementById('saveButton');
  var dateError = document.getElementById('dateError');

  loanDateInput.addEventListener('change', function() {
    var currentDate = new Date().toLocaleDateString('en-PH', { timeZone: 'Asia/Manila' });
    var selectedDate = new Date(loanDateInput.value);

    if (selectedDate < new Date(currentDate)) {
      dateError.textContent = 'Past Date is not Allow in loan Date, Please select present date to remove disable in save.';
      saveButton.disabled = true;
    } else {
      dateError.textContent = '';
      saveButton.disabled = false;
    }
  });
</script>

   
        <script>
    function calculate() {
        // Get values from the input and dropdown
        const payableAmount = document.getElementById("payable_amount").value;
        const cutoffNo = document.getElementById("cutoff_no").value;

        // Check if payableAmount is empty or 0
        if (!payableAmount || payableAmount == 0) {
            // Set amortization to 0 or empty
            document.getElementById("amortization").value = "";
            return;
        }

        // Calculate the amortization amount
        let amortization = 0;
        if (cutoffNo != 0) {
            amortization = (payableAmount / cutoffNo).toFixed(2).replace(/\.00$/, '');
        }

        // Display the result
        document.getElementById("amortization").value = `${amortization}`;
    }
</script>

<script>
// sched form modal

let Modal = document.getElementById('loanFormModal');

//get open modal
let modalBtn = document.getElementById('loanFormBtn');

//get close button modal
let closeModal = document.getElementsByClassName('loanFormClose')[0];

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
                                    $('#empid').val(data[8]);
                                    $('#sched_from').val(data[5]);
                                    $('#sched_to').val(data[6]);
                                });
                            });
            
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
