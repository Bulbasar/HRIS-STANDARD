<?php
 include 'config.php';
 @$encodedEmail = $_GET['email'];
 @$email = base64_decode($encodedEmail);

  $sql = "SELECT * FROM employee_tb WHERE `email` = '$email' ";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  @$empid = $row['empid'];


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'phpmailer/src/Exception.php';

require 'phpmailer/src/PHPMailer.php';

require 'phpmailer/src/SMTP.php';


// error_reporting(0);
    session_start();
    if(isset($_POST['submit'])){
        @$empid = $_POST['empid'];
        @$email = $_POST['email'];
        @$digit1 = $_POST['digit1'];
        @$digit2 = $_POST['digit2'];
        @$digit3 = $_POST['digit3'];
        @$digit4 = $_POST['digit4'];

        @$otp = $digit1 . $digit2 . $digit3 . $digit4;

        $sql = "SELECT * FROM forgot_pass_tb WHERE `email` = '$email' AND `empid` = '$empid' AND `otp` = '$otp' ";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){
            $encodedEmail = base64_encode($email);
            header("Location: newPassword?email=$encodedEmail");
            exit;
        }else{
            $encodedEmail = base64_encode($email);
            session_start(); // Start a session
            $_SESSION['invalid'] = true; // Set the session variable to indicate countdown start
            header("Location: forgot otp?email=$encodedEmail");
            exit;
        }
    }

    if(isset($_POST['resend'])){
        $email = $_POST['email'];

        $sql = "SELECT * FROM employee_tb WHERE `email` = '$email' ";
        $result = mysqli_query($conn, $sql);
    
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $mname = $row['mname'];
            $username = $row['username'];
            $empid = $row['empid'];
            
            $mail = new PHPMailer(true);
      
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hris.payroll.mailer@gmail.com'; //gmail name
            $mail->Password = 'ndehozbugmfnhmes'; // app password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
          
            $mail->setFrom('hris.payroll.mailer@gmail.com'); //gmail name
        
            
          
            $mail->addAddress($email);
          
            $mail->isHTML(true);
            $mail->Subject = 'Forgot Password'; // Set the email subject
            $otp = mt_rand(1000, 9999);
          
            // $imgData = file_get_contents('../../img/panget.png');
            $imgData64 = base64_encode($imgData);
            $cid = md5(uniqid(time()));
            // $mail->addEmbeddedImage('../../img/panget.png', $cid, 'panget.png');
          
            // $mail->Body .= '<img src="cid:' . $cid . '" style="height: 100px; width: 200px;">';
            $mail->Body .= '<h1>Hello, ' . $fname . ' '. $lname . '</h1>';
            $mail->Body .= '<p>This is your reset password OTP. <span style="font-weight: 500">' . $otp. ' </span> </p>';
          
            $mail->send();
    
           $sql = "UPDATE forgot_pass_tb SET `otp` = '$otp' WHERE `email` = '$email' AND `empid` = '$empid' ";

            if ($conn->query($sql) === TRUE) {
                $encodedEmail = base64_encode($email);
                session_start(); // Start a session
                $_SESSION['start_countdown'] = true; // Set the session variable to indicate countdown start
                header("Location: forgot otp?email=$encodedEmail");
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }
             
            // if(mysqli_query($conn, $sql)){
            //     $encodedEmail = base64_encode($email);
            //     header("Location:forgotPassword?email=$encodedEmail");
            //     exit;
            // }else{
            //     echo "Error inserting data ". mysqli_error($conn);
            // }    
        }else{
            $encodedEmail = base64_encode($email);
            header("Location: forgot otp?email=$encodedEmail");
            exit;
        }
    }

    if (isset($_SESSION['start_countdown']) && $_SESSION['start_countdown'] === true) {
        // Execute the countdown script
        echo '
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var button = document.getElementById("resend_otp");
                var span = document.getElementById("resend_no");
        
                // Display the span
                span.style.display = "block";
        
                // Disable the button
                button.disabled = true;
        
                // Change the button\'s color to gray
                button.style.color = "gray";
        
                // Start a 30-second countdown
                var countdown = 30;
                var countdownInterval = setInterval(function() {
                    var minutes = Math.floor(countdown / 60);
                    var seconds = countdown % 60;
                    span.innerHTML = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
                    countdown--;
                    if (countdown < 0) {
                        // Countdown has finished, re-enable the button and reset the span
                        button.disabled = false;
                        button.style.color = "blue";
                        span.style.display = "none";
                        clearInterval(countdownInterval);
                    }
                }, 1000); // Update every 1 second
            });
        </script>';
        
        // Reset the session variable to prevent repeated execution
        $_SESSION['start_countdown'] = false;
    } elseif (isset($_SESSION['invalid']) && $_SESSION['invalid'] === true) {
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
                    <div class="w-100 p-3">
                        <form action="" method="POST">
                            <!-- <label for="" class="ml-1 fs-5">Enter your email</label> -->
                            <h3 class="d-flex mt-4">OTP Verification</h3><br>

                            <div class="form-group w-100">

                                <div class="w-100 mb-1 d-flex justify-content-center">
                                    <label for="" class="">Enter the OTP that sent to <span style="color:blue; font-weight:600"><?php echo $email ?></span></label>
                                </div>
                                <div class="input-group mx-auto" style="width: 60%" id="otp-input">
                                    <div class="input-box">
                                        <input type="text" class="form-control" id="digit1" maxlength="1" style="width: 4em; height: 4em" name="digit1">
                                    </div>
                                    <div class="input-box">
                                        <input type="text" class="form-control" id="digit2" maxlength="1" style="width: 4em; height: 4em" name="digit2">
                                    </div>
                                    <div class="input-box">
                                        <input type="text" class="form-control" id="digit3" maxlength="1" style="width: 4em; height: 4em" name="digit3">
                                    </div>
                                    <div class="input-box">
                                        <input type="text" class="form-control" id="digit4" maxlength="1" style="width: 4em; height: 4em" name="digit4">
                                        <input type="hidden" name="empid" value="<?php echo @$empid; ?>" id="">
                                        <input type="hidden" name="email" value="<?php echo @$email; ?>"  id="">
                                    </div>
                                </div>
                            </div>    
                                <button type="submit" name="submit" class="btn mx-0 w-100" id="">Submit</button> 
                        </form>

                        <!-- <div style="margin: 0;" class="remember-forgot mt-3 w-100">

                            <div class="chkbox-container w-75 " style="margin: 0; padding: 0">
                                <form action="" class="border" style="width: 70%;">
                                <div class="d-flex flex-row w-100 border ">
                                    <p class=" w-50 mt-4" style="font-size: 16px;margin-top: 3px">Didin't receive code? </p>

                                    <button type="submit" name="resend" style="border:none; background-color: inherit; color: black; box-shadow: none" class="p-0 w-50 mb-2" id="resend_otp">Resend OTP   
                                    </button>
                                    <span id="resend_no" style="display: none;" class="ml-3"></span>
                                    
                                </div>
                                </form>
                               
                            </div>

                            <a href="login" class="p-3 fs-5 mt-2">Login?</a>
                        </div> -->

                        <div class="cons w-100  h-25 d-flex flex-row justify-content-between">
                            <form action="" method="POST" class="w-100 h-100">
                                <input type="hidden" name="email" value="<?php echo @$email ?>" id="">
                                <!-- <input type="hidden" name="" value="<?php echo @$empid ?>" id=""> -->
                                <div class="w-100 h-100  d-flex flex-row flex-direction-between align-items-center justify-content-between">
                                    <div class="h-100  d-flex flex-row align-items-center justify-content-center" style="width: 35%;">
                                        <p class="mt-3 ml-1" style="font-size:19px">Didin't receive code?</p>
                                    </div>
                                    <div class="h-100 d-flex flex-row align-items-center" style="width:65%">
                                        <button type="submit" name="resend"  id="resend_otp" style="border: none; background-color: inherit; box-shadow:none; color: blue; width: 10em; padding: 0; margin-top: -1px; margin-left:-1em; text-decoration-line: underline;" class="">Resend OTP   
                                        </button>
                                        <span id="resend_no" style="display: none; margin-right: 2em" class="ml-3"></span>
                                    </div>
                                </div>
                            </form>
                            <div class="w-25 h-100  d-flex align-items-center justify-content-center">
                                <a href="login" class="p-3 fs-5 ">Login?</a>
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
<script>
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

</script>

<!-- resent otp -->
<script>
// // Function to format seconds as "MM:SS"
// function formatTime(seconds) {
//     const minutes = Math.floor(seconds / 60);
//     const remainingSeconds = seconds % 60;
//     return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
// }

// // Function to handle the countdown
// function startCountdown(seconds) {
//     const resendButton = document.getElementById('resend_otp');
//     const resendSpan = document.getElementById('resend_no');

//     resendButton.disabled = true; // Disable the button
//     resendSpan.style.display = 'inline'; // Make the countdown visible

//     let remainingSeconds;
//     let countdownInterval;

//     // Check if a countdown is already in progress
//     if (localStorage.getItem('countdownStartTime')) {
//         const startTime = parseInt(localStorage.getItem('countdownStartTime'));
//         const currentTime = new Date().getTime();
//         const elapsedTime = Math.floor((currentTime - startTime) / 1000);
//         remainingSeconds = seconds - elapsedTime;
//     } else {
//         remainingSeconds = seconds;
//     }

//     resendSpan.textContent = formatTime(remainingSeconds);

//     countdownInterval = setInterval(function () {
//         remainingSeconds--;

//         if (remainingSeconds <= 0) {
//             clearInterval(countdownInterval); // Stop the countdown
//             resendButton.disabled = false; // Enable the button
//             resendButton.style.color = 'blue';
//             resendSpan.style.display = 'none'; // Hide the span
//         } else {
//             resendSpan.textContent = formatTime(remainingSeconds);
//         }
//     }, 1000);
// }

// document.getElementById('resend_otp').addEventListener('click', function () {
//     // Clear the existing countdown start time to ensure it starts fresh
//     localStorage.removeItem('countdownStartTime');
//     startCountdown(30); // Start or resume the 30-second countdown when the button is clicked
// });

// // Initialize or resume the countdown on page load
// startCountdown(30);

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