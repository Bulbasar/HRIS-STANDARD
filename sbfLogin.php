<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');
    </style>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">

    <link rel="stylesheet" href="login.css">

    <title>SBF LOGIN</title>
  </head>
  <body>
    <!-- <div class="container-fliud d-flex flex-row justify-content-between" id="main-container">
        <div class="login-img w-50 " id="login-img">
            <img src="img/sample.jpg" alt="" class="w-100 h-100">
        </div>
        <div class="login-container w-50 border d-flex align-items-center" id="login-container">
            <div class="login-credential h-75 mx-auto d-flex flex-column shadow p-3">
                <div class="login-logo w-100 mt-3">
                    <div class="logo-container bg-dark h-100 mx-auto d-flex align-items-center p-1 rounded mt-2">
                        <img src="img/Slash Tech Solutions.png" class="w-100 h-100" alt="" srcset="">
                    </div>
                </div>
                <div class="credential-container w-100 h-100 mt-5 ">
                    <div class="form-group mx-auto mt-3" id="login-welcome" >
                        <div class="cred-title">
                            <span class="welcome-text" >Welcome back</span><br>
                            <span class="fs-2 fw-bold">Login to your account</span>
                        </div>
                        <div class="form-group mx-auto mt-4 form-container">
                            <label for="">Email</label><br>
                            <input type="email" name="" id="" class="form-control" placeholder="Enter email" required><br>

                            <label for="">Password</label><br>

                            <div class="password">
                                <input type="password" name="" id="" class="form-control" placeholder="Enter password" required><br>
                                <i class="fa-regular fa-eye"></i>
                            </div><br>

                            <div class="d-flex justify-content-between">
                                <div class="w-50">

                                </div>
                                <div class="w-50 d-flex flex-direction-end justify-content-end forgot-pass">
                                    <a href="#">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="login-btn mt-3">
                                <button class="btn btn-primary w-100">Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<!-- 
    <style>
        
    </style> -->

    <div class="container-fluid" id="container">
        <div class="form-container sign-up-container">
            <form action="" method="POST">
                <div class="shadow rounded login-cred p-3 d-flex flex-column">
                    <h1 class="mt-4">Forgot Password</h1>

                    <div class="d-flex align-items-center justify-content-center w-100 h-75 ">
                        <div class="form-group d-flex flex-column flex-direction-start align-items-start mx-auto forgot-credential h-50"> 
                            <label for="">Email</label>
                            <input type="email" name="" id="" class="form-control p-2" placeholder="Please fill in your address" ><br>
                            <button name="submit" class="sign-up w-100">Submit</button>

                            </form>

                            <div class="forgot-btn-container d-flex flex-column justify-content-start align-items-start mt-3 w-100">
                                <span class="forgot-btn p-0 ghost" id="signIn">Remember your password?</span>
                                <!-- <button class="forgot-btn p-0 ghost" id="signIn">Back to login</button> -->
                                <!-- <span class="forgot-btn p-0 ghost" id="signIn">Back to login</span> -->
                                <a href="sbfLogin">Back to login</a>
                            </div>
                        </div> 
                    </div>
                   
                </div>
          
        </div>
        <div class="form-container sign-in-container">
            <form action="" method="POST">
                <div class="shadow rounded login-cred p-3 d-flex flex-column">
                        <div class="login-logo w-100 mt-3">
                            <div class="logo-container h-100 mx-auto d-flex align-items-center p-1 rounded mt-2">
                                <!-- <img src="img/header-logo-small.jpg" class="w-50 h-100" alt="" srcset=""> -->
                            </div>
                        </div>
                    <h1 class="mt-1 mb-2">Sign in</h1><br>
                    <!-- <div class="social-container">
                        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your account</span><br> -->
                    <div class="form-group d-flex flex-column justify-content-start flex-direction-start align-items-start mx-auto form-credential">
                        <label for="">Email</label>
                        <input type="email" placeholder="Enter Email" name="email" class="form-control p-2" required/><br>

                        <label for="">Password</label>
                        <input type="password" placeholder="Enter Password" name="password" class="form-control p-2" required /><br>

                        <button name="signIn" class="sign-in w-100">Sign In</button>
                        </form>

                        <div class="d-flex flex-row justify-content-between w-100">
                        <div class="w-50 d-flex flex-row justify-content-start">
                            <!-- <a href="#">Forgot your password?</a> -->
                                <span class="ghost forgot-pass" id="signUp">Forgot Password?</span>
                            </div>

                            <div class="w-50">

                            </div>

                        </div>
                        
                    </div>         
                </div>  
           
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left p-0">
                <img src="img/sample2.jpg" class="w-100 h-100"  alt="">
                    <!-- <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p> -->
                    <!-- <button class="ghost" id="signIn">Sign In</button> -->
                </div>
                <div class="overlay-panel overlay-right p-0">
                    <img src="img/sample2.jpg" class="w-100 h-100"  alt="">
                    <!-- <h1>Welcome to Sinned Gaming Gears!</h1>
                    <p>Enter your personal details and start journey with us</p> -->
                    <div class="landing-container">

                        <!-- <button class="ghost" id="signUp">Sign Up</button> -->
                    </div>
                    
                </div>
            </div>
        </div>
    </div> 
    
    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
   
  </body>
</html>