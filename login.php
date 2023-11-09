<?php

include 'config.php';

// error_reporting(0);
session_start();

if(isset($_POST['signIn'])){
    $username = $_POST['username'];
    $passwordnot = $_POST['password'];
    $password = mysqli_real_escape_string($conn, md5($_POST["password"]));
    
    $Superadmin = "SELECT * FROM user_tb WHERE BINARY `username` = '$username' AND BINARY `password` = '$passwordnot'";
    $superAdminResult = mysqli_query($conn, $Superadmin);
    
    if (mysqli_num_rows($superAdminResult) > 0){
        $row_Superadmin = mysqli_fetch_assoc($superAdminResult);
        $_SESSION['username'] = $row_Superadmin['username'];
        $_SESSION['password'] = $row_Superadmin['password'];
        $_SESSION['userType'] = $row_Superadmin['userType'];
        $_SESSION['role'] = $row_Superadmin['role'];
        // $_SESSION['empid'] = $row['empid'];
        
        header("Location: Dashboard");
        exit();
    } else {
        $select_users = mysqli_query($conn, "SELECT * FROM employee_tb WHERE BINARY `username`='$username' AND `password`='$password'");
  
        if(mysqli_num_rows($select_users) > 0){
            $row = mysqli_fetch_assoc($select_users);
            if($row['status'] == 'Inactive'){
                echo '<script type="text/javascript">';
                echo 'alert("Your Account is already inactive!");';
                echo '</script>';
            } else {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['empid'] = $row['empid'];
                $_SESSION['role'] = $row['role'];
                
                if($row['role'] == 'admin'){
                    header("Location: Dashboard"); // Redirect to admin dashboard
                    exit();
                } else if($row['role'] == 'Employee'){
                    header("Location: EmpHRIS/Dashboard"); // Redirect to employee dashboard
                    exit();
                } else if ($row['role'] == 'Supervisor'){
                    header("Location: Supervisor HRIS/Dashboard"); // Redirect to supervisor dashboard
                    exit();
                }
            }
        } else {
            $errorMessage = "Invalid username or password";
            // echo '<script type="text/javascript">';
            // echo 'alert("Wrong Email or Password!");';
            // echo '</script>';
            header("Location: login?error");
        }
    }
}


// if(isset($_POST['signIn'])){
//     $username = $_POST['username'];
//     $passwordnot = $_POST['password'];
//     $password = mysqli_real_escape_string($conn, md5($_POST["password"]));
   

//                             $Superadmin = "SELECT * FROM user_tb WHERE BINARY `username` = '$username' AND BINARY `password` = '$passwordnot'";
//                             $superAdminResult = mysqli_query($conn, $Superadmin);
//                             // Check if employee login is successful

//                             if (mysqli_num_rows($superAdminResult) > 0){
//                                 $row_Superadmin = mysqli_fetch_assoc($superAdminResult);
//                                 $_SESSION['username'] = $row_Superadmin ['username'];
//                                 $_SESSION['password'] = $row_Superadmin['password'];
//                                 $_SESSION['userType'] = $row_Superadmin['userType'];
//                                 $_SESSION['role'] = $row_Superadmin['role'];
//                                 $_SESSION['empid'] = $row['empid'];
                            
//                                 header("Location: Dashboard"); // Redirect to admin dashboard
//                                 exit();
//                             } else {
                              
//                                 $select_users = mysqli_query($conn, "SELECT * FROM employee_tb WHERE `username`='$username' AND `password`='$password' AND `status` = 'Active'");
  
  
//                                 if(mysqli_num_rows($select_users) > 0){
                                  
//                                   $row = mysqli_fetch_assoc($select_users);
                              
//                                   if($row['role'] == 'admin'){
                              
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: Dashboard"); // Redirect to employee dashboard
//                                     exit();
                              
//                                   }else if($row['role'] == 'Employee'){
                              
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: EmpHRIS/Dashboard"); // Redirect to employee dashboard
//                                     exit();
                              
//                                   }else if ($row['role'] == 'Supervisor'){
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: Supervisor HRIS/Dashboard"); // Redirect to employee dashboard
//                                     exit();
//                                 }//else{
//                                 //       $message[] = 'no user found!';
//                                 //   }
                              
//                                 }else{
//                                     $errorMessage = "Invalid username or password";
//                                 echo '<script type="text/javascript">';
//                                     echo 'alert("Wrong Email or Password!");';
//                                 echo '</script>';
//                                 }
//                             }


// }

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
    <title>HRIS | LOG IN</title>
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
</style>

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
                
                <div class="form-container">
                    <form action="" method="POST">
                        <input class="input-text" type="text" name="username" id="username" placeholder="Username" value="<?php echo @$username; ?>" required maxlength="50">
                        
                        <div class="login-pass-container">   
                            <input class="input-text" id="login-pass" type="password" name="password" placeholder="Password" required maxlength="50">
                            <i class="fas fa-eye show-pass" aria-hidden="true" id="eye" onclick="toggle()"></i>
                        </div>


                        <div class="remember-forgot">

                            <div class="chkbox-container">
                                <input class="checkbox" type="hidden" name="" id="">
                                <!-- <p>Remember me</p> -->
                            </div>
        
                            <a href="forgotPassword">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" name="signIn" class="signin-btn" id="signin-btn">Sign in </button> 
                    </form>
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
        <h4 class="mt-3">Wrong Password or Username!</h4>
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
    <!-- <script src="main.js"></script> -->
</body>
</html>