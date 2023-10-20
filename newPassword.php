<?php 
    include 'config.php';
    @$encodedEmail = $_GET['email'];
    @$email = base64_decode($encodedEmail);

    $sql = "SELECT * FROM employee_tb WHERE `email` = '$email' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    @$empid = $row['empid'];
    @$password = $row['password'];


    session_start();
    if(isset($_POST['submit'])){
        $pass = $_POST['password'];
        $empid = $_POST['empid'];
       
        $passwordHash = mysqli_real_escape_string($conn, md5($pass));

        // echo $pass ,"<br>", $empid , "<br>", $passwordHash;

        $sql = "UPDATE `employee_tb` SET `password` = '$passwordHash' WHERE `empid` = '$empid' "; 

        if($conn->query($sql) === TRUE){
            $encodedEmail = base64_encode($email);
                session_start(); // Start a session
                $_SESSION['success'] = true; // Set the session variable to indicate countdown start
                header("Location: newPassword?email=$encodedEmail");
                exit;
        }else{
            $encodedEmail = base64_encode($email);
            session_start(); // Start a session
            $_SESSION['invalid'] = true; // Set the session variable to indicate countdown start
            header("Location: newPassword?email=$encodedEmail");
            exit;
        }

    }

    if (isset($_SESSION['success']) && $_SESSION['success'] === true) {
        // Display the modal overlay with an error message
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Show the modal overlay
                console.log("hehe");
                var modalOverlays = document.getElementById("modal-overlays");
                modalOverlays.style.display = "block";
            });
        </script>';
        // Reset the session variable to prevent repeated display
        $_SESSION['invalid'] = false;
    }elseif(isset($_SESSION['invalid']) && $_SESSION['invalid'] === true) {
            // Display the modal overlay with an error message
            echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Show the modal overlay
                    console.log("hehe");
                    var modalOverlay = document.getElementById("modal-overlay");
                    modalOverlay.style.display = "block";
                });
            </script>';
            // Reset the session variable to prevent repeated display
            $_SESSION['invalid'] = false;
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">

    <!-- skydash -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    
  
    <link rel="stylesheet" href="backup/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>HRIS | Forgot Password</title>
</head>
<body class="login-container" style="overflow:hidden; background-color: #000">

<style>
    .login-pass-container{
    position: relative;
    

}
    .login-pass-container #eye{
       font-size: 23px !important;
       position: absolute !important;
       bottom: 0 !important;
        right: 7% !important;
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
    .form-container button{
        width: 100% !important;
    }

    .form-containers{
    height: 400px;
    width: 680px;
    display: flex;
    flex-direction: column; 
    align-items: center;    
  
    background-color: #f4f4f4;
    border-radius: 10px;
    
}
.signin-container .remember-forgot{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 595px;
    height: 30px;
    margin-left: 27px;
    margin-top: 10px;
    align-items: center;
    
    
}
.input-box {
    display: inline-block;
    width: 30px; /* Adjust the size as needed */
    text-align: center;
    margin-right: 5px;
}

.input-group {
    display: flex;
    justify-content: space-around;
}


</style>

    <!-- Modal Overlay with Error Message -->
         <div id="modal-overlays" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); text-align: center; z-index: 9999">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; height: 30%; width: 25%" class="rounded"> 
                <div class="invalid-body h-100 w-100">
                    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                        <div class="border border-success d-flex justify-content-center align-items-center bouncing-icon" style="height: 10em; width: 10em; border-radius: 50%;">
                            <!-- <i class="fa-solid fa-check bouncing-icon" style="font-size: 6em; font-weight: 400; color: green"></i> -->
                            <img src="img/check-no bg.png" class="bouncing-icon" style="height: 13em; width: 12em;" alt="">
                        </div>
                        <h4 class="mt-3">Change Password Success!</h4>
                    </div> 
                    <div class="w-100 d-flex flex-row justify-content-between">
                        <div class="w-50">

                        </div>
                        <div class="w-50 d-flex justify-content-end align-items-end p-3">
                            <!-- <button class="mr-2 btn btn-secondary" id="close_otp">Close</button> -->
                            <a href="login" style="color: blue">Go back to Login</a>
                        </div>
                    </div>
                </div>
                <!-- Add any additional styling or error message here -->
            </div>
        </div>


    <!-- Modal Overlay with Error Message -->
    <div id="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); text-align: center; z-index: 9999">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; height: 30%; width: 25%" class="rounded"> 
            <div class="invalid-body h-100 w-100">
                <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
                    <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
                        <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
                    </div>
                    <h4 class="mt-3">Invald OTP!</h4>
                </div> 
                <div class="w-100 d-flex flex-row justify-content-between">
                    <div class="w-50">

                    </div>
                    <div class="w-50 d-flex justify-content-end align-items-end p-3">
                        <button class="mr-2 btn btn-secondary" id="close_otp">Close</button>
                    </div>
                </div>
            </div>
            <!-- Add any additional styling or error message here -->
        </div>
    </div>

    <script>
        let closeBtn = document.getElementById("close_otp");

        closeBtn.addEventListener("click", function(){
            let modal_overlay = document.getElementById("modal-overlay");

            modal_overlay.style.display = "none";
        });
    </script>


    <div class="container-fluid" style="display:flex; flex: row; height: 100vh; width: 100vw">
        <div class="logo-img" >
            <img src="img/login-img5.jpg" alt="" srcset="">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#000" fill-opacity="1" d="M0,64L48,74.7C96,85,192,107,288,101.3C384,96,480,64,576,74.7C672,85,768,139,864,133.3C960,128,1056,64,1152,64C1248,64,1344,128,1392,160L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
        </div>

        <div class="signin-container">
            <div class="signin-card">

                <div class="signin-logo-img">
                    <img src="img/Slash Tech Solutions.png" class="logo" alt="" srcset="" >
                </div>   
                
                <div class="form-containers">
                    <div class="w-100 p-4">
                        <form action="" method="POST">
                            <!-- <label for="" class="ml-1 fs-5">Enter your email</label> -->
                            <h3 class="d-flex mt-4 justify-content-center align-items-center">New Password</h3><br>

                            <div class="form-group w-100">

                            <input type="hidden" name="empid" value="<?php echo @$empid ?>" id="">
                                
                                <div class="d-flex flex-row" style="position: relative">
                                    <input type="password"  pattern="[a-zA-Z0-9]{5,}" title="Must be at least 5 characters." oninput="Pass()" oninput="showPasswordIcon(this, 'eye')" name="password" id="pass" placeholder="Password" required class="form-control" style="height: 3em; font-size: 19px">
                                    <i class="fas fa-eye show-pass" aria-hidden="true" id="eye" style="position: absolute; right: 4%; top: 50%; font-size: 1.7em; display:none" onclick="togglePassword()"></i>
                                </div><br>
                                

                                
                                <div class="d-flex flex-row w-100" style="position: relative">
                                        <input type="password" class="form-control" pattern="[a-zA-Z0-9]{5,}" title="Must be at least 5 characters." disabled oninput="matchPass()" oninput="showPasswordIcon(this, 'confirm-eye')" name="cpassword" id="cpass" placeholder="Confirm Password" required style="height: 3em; font-size: 19px">
                                        <i class="fas fa-eye show-pass" aria-hidden="true" id="confirm-eye" style="position: absolute; right: 4%; top: 25%; font-size: 1.7em; display: none" onclick="toggleConfirmPassword()"></i>
                                    </div>
                                    <p id="id_pValidate" style="color: red; display: none;" class="mt-2">Passwords don't match!</p>
                               
                            </div>    
                                <button type="submit" name="submit" class="btn mx-0 w-100" id="">Submit</button> 
                        </form>

                        <div class="remember-forgot mt-3">

                            <div class="chkbox-container">
                                <input class="checkbox" type="hidden" name="" id="">
                                <!-- <p>Remember me</p> -->
                            </div>

                            <a href="login">Login?</a>
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
        <div class="border border-danger d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%;">
            <i class="fa-solid fa-exclamation bouncing-icon" style="font-size: 6em; font-weight: 400; color: red"></i>
        </div>
        <h4 class="mt-3">The e-mail is not on our records!</h4>
    </div>
    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <button class="btn fs-5 btn-closes" style="color: blue; background-color: inherit">Close</button>
    </div>
</div>

   <!-- Overlay div -->
<div class="overlay"></div>

<!-- Modal HTML for Successfully Inserted -->
<div id="insertedModal" class="modals">
    <span class="close">&times;</span>
    <div class="mt-4 d-flex justify-content-center align-items-center flex-column" style="height: 70%">
        <div class="mb-3 d-flex justify-content-center align-items-center bouncing-icon" style="height: 9em; width: 9em; border-radius: 50%; ">
        <!-- <i class="fa-regular fa-envelope" style="font-size: 6em; color: black"></i> -->
        <img src="img/351-3516873_email-symbol-black-and-white.png" alt="" srcset="" style="height: 4.3em; width: 7em">
        </div>
        <h5 class="">OTP Code has been to your email.</h5>
       
    </div>
    <div class="btn-footer w-100 d-flex justify-content-end mt-3">
        <!-- <button class="btn btn-closes">Close</button> -->
        <a href="#" style="text-decoration: none;" class="btn btn-primary w-100  fs-4">Go</a>
    </div>
</div>

<!-- OTP js -->
<!-- <script>
document.querySelectorAll('.form-control').forEach(function (input, index, inputs) {
    input.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, ''); // Allow only digits
        if (this.value.length > 1) {
            this.value = this.value.charAt(0); // Only keep the first character
        }
        if (this.value.length === 0 && index > 0) {
            // On Backspace press, clear the current input and focus on the previous input
            this.value = '';
            inputs[index - 1].focus();
        }
        if (this.value.length === 1 && index < inputs.length - 1) {
            // On typing a digit, focus on the next input
            inputs[index + 1].focus();
        }
    });
});

</script> -->

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
        urlParams.delete(modalId === 'duplicateModal' ? 'error' : 'inserted');
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

<!-- show password -->
<script>
  function togglePassword() {
    var passwordInput = document.getElementById("pass");
    var eyeIcon = document.getElementById("eye");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }

  function toggleConfirmPassword() {
    var confirmPasswordInput = document.getElementById("cpass");
    var confirmEyeIcon = document.getElementById("confirm-eye");

    if (confirmPasswordInput.type === "password") {
      confirmPasswordInput.type = "text";
      confirmEyeIcon.classList.remove("fa-eye");
      confirmEyeIcon.classList.add("fa-eye-slash");
    } else {
      confirmPasswordInput.type = "password";
      confirmEyeIcon.classList.remove("fa-eye-slash");
      confirmEyeIcon.classList.add("fa-eye");
    }
  }

  function showPasswordIcon(input, iconId) {
    var eyeIcon = document.getElementById(iconId);
    if (input.value !== "") {
      eyeIcon.style.display = "inline-block";
    } else {
      eyeIcon.style.display = "none";
    }
  }
</script>

<script>
 // Calculate the date 18 years ago
var today = new Date();
var maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());


var minDate = new Date(today.getFullYear() - 70, today.getMonth(), today.getDate());

// Format the maxDate and minDate as YYYY-MM-DD
var maxDateFormatted = maxDate.toISOString().split("T")[0];
var minDateFormatted = minDate.toISOString().split("T")[0];

// Set the max and min attributes of the input element
document.getElementById("empdob").setAttribute("max", maxDateFormatted);
document.getElementById("empdob").setAttribute("min", minDateFormatted);

 // Get references to the date hired, start date, and end date input fields
 const dateHiredInput = document.querySelector('[name="empdate_hired"]');
    const startDateInput = document.querySelector('[name="sched_from"]');
    const endDateInput = document.querySelector('[name="sched_to"]');

    // Function to enable/disable the start date and end date fields
    function toggleDateFields() {
        if (dateHiredInput.value !== '') {
            const selectedDate = dateHiredInput.value;
            startDateInput.min = selectedDate;
            endDateInput.min = selectedDate;
            startDateInput.disabled = false;
            endDateInput.disabled = false;
        } else {
            startDateInput.disabled = true;
            endDateInput.disabled = true;
        }
    }

    // Disable the start date and end date fields initially
    toggleDateFields();

    // Add an event listener to the date hired field
    dateHiredInput.addEventListener('change', toggleDateFields);

function Pass(){
    let pass = document.getElementById('pass').value;
    let cpass = document.getElementById('cpass').value;
   
    if(pass === ""){
        document.getElementById('cpass').disabled = true;
    }
    else{
        document.getElementById('cpass').disabled = false;

        
    if(cpass != pass){
        
        document.getElementById('id_pValidate').style.display = "";
        document.getElementById('btn_save').style.cursor = "no-drop";
        document.getElementById('btn_save').disabled = true;
    }
    else{
        document.getElementById('id_pValidate').style.display = "none";
        document.getElementById('btn_save').style.cursor = "pointer";
        document.getElementById('btn_save').disabled = false;
    }
    }
}
function matchPass(){
    let pass = document.getElementById('pass').value;
    let cpass = document.getElementById('cpass').value;

    if(pass != cpass){
        
        document.getElementById('id_pValidate').style.display = "";
        document.getElementById('btn_save').style.cursor = "no-drop";
        document.getElementById('btn_save').disabled = true;
    }
    else{
        document.getElementById('id_pValidate').style.display = "none";
        document.getElementById('btn_save').style.cursor = "pointer";
        document.getElementById('btn_save').disabled = false;
    }
}


</script>

    <script>
        let pass = document.getElementById("pass");
        let cpass = document.getElementById("cpass");
              
        pass.addEventListener('focus', function(){
            let eye = document.getElementById("eye");
            eye.style.display = "block";

            cpass.addEventListener('focus', function(){
                let confirm_eye = document.getElementById("confirm-eye");
                confirm_eye.style.display="block"; 
            });

        });
    </script>
             



    <script>
  function toggle() {
    var passwordInput = document.getElementById("login-pass");
    var eyeIcon = document.getElementById("eye");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }
</script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="main.js"></script>
</body>
</html>