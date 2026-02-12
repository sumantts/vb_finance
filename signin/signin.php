<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from coderthemes.com/EziDesk_2/saas/pages-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 31 Aug 2024 16:02:40 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Log In | VB Finance </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Feedback Management Panel" name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/<?=$logo?>">

    <!-- Theme Config Js -->
    <script src="assets/js/EziDesk-config.js"></script>

    <!-- App css -->
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg pb-0">

    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-3">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-start">
                    <a href="#" class="logo-dark">
                        <span><img src="assets/images/<?=$logo?>" alt="dark logo" style="width: 100%;height: 70px;"></span>
                    </a>
                    <a href="#" class="logo-light">
                        <span><img src="assets/images/<?=$logo?>" alt="logo"  style="width: 100%;height: 70px;"></span>
                    </a>
                </div>

                <div class="my-auto">
                    <!-- title-->
                    <h4 class="mt-0">Sign In</h4>
                    <p class="text-muted mb-4">Enter your User Name and password to access account.</p>

                    <!-- form -->
                    <form action="#" method="post" id="myForm">
                        <div class="mb-3">
                            <label for="emailaddress" class="form-label">User Name</label>
                            <input class="form-control" type="text" id="emailaddress" required >
                        </div>
                        <div class="mb-3">
                            <!-- <a href="pages-recoverpw-2.html" class="text-muted float-end"><small>Forgot your password?</small></a> -->
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control" type="password" required id="password" >
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                        </div>
                        <div class="d-grid mb-0 text-center">
                            <button class="btn btn-primary" type="submit" ><i class="mdi mdi-login"></i> Log In </button>
                        </div>
                        <!-- social-->
                        <!-- <div class="text-center mt-4">
                            <p class="text-muted font-16">Sign in with</p>
                            <ul class="social-list list-inline mt-3">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                                </li>
                            </ul>
                        </div> -->
                    </form>
                    <!-- end form-->
                </div>

                <!-- Footer-->
                <!-- <footer class="footer footer-alt">
                    <p class="text-muted">Don't have an account? <a href="pages-register-2.html" class="text-muted ms-1"><b>Sign Up</b></a></p>
                </footer> -->

            </div> <!-- end .card-body -->
        </div>
        <!-- end auth-fluid-form-box-->

        <!-- Auth fluid right content -->
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3"><?=$tag_line?></h2>
                <!-- <p class="lead"><i class="mdi mdi-format-quote-open"></i> Tune in to the serene rhythm of nature...<i class="mdi mdi-format-quote-close"></i> -->
                </p>
                <!-- <p> - EziDesk</p> -->
            </div> <!-- end auth-user-testimonial-->
        </div>
        <!-- end Auth fluid right content -->
    </div>
    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script src="signin/function.js"></script>
    

</body>
<?php
if(isset($_GET["log"]) == 'out'){
    session_unset();
    //header("location:?p=signin");
}
?>

<!-- Mirrored from coderthemes.com/EziDesk_2/saas/pages-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 31 Aug 2024 16:02:40 GMT -->
</html>