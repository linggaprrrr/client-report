<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Report Management System | Login Page</title>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="/assets/login/fonts/icomoon/style.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="/assets/login/css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/login/css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="/assets/login/css/style.css">
    <!-- /theme JS files -->
    <style>
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            }

            /* The actual popup */
            .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
            }

            /* Popup arrow */
            .popup .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
            }

            /* Toggle this class - hide and show the popup */
            .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
            }

            /* Add animation (fade in the popup) */
            @-webkit-keyframes fadeIn {
            from {opacity: 0;} 
            to {opacity: 1;}
            }

            @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity:1 ;}
            }
    </style>
</head>



<body>
    <div class="content" style="padding-top: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="login-logo">
                        <img src="/assets/images/fba-logo.png" alt="Image" class="img-fluid">
                    </div>
                    <div class="login-background">
                        <img src="/assets/login/images/login-min.png" alt="Image" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-6 contents" style="align-self: center;">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Sign In</h3>
                            </div>
                            <form class="login-form" method="POST" action="<?= base_url('/login-proccess') ?>">


                                <?php if (session()->getFlashdata('error')) : ?>
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        <div class="alert-body">

                                            <b>Error !</b>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <div class="form-group first mb-2">
                                    
                                    <?php if (isset($_COOKIE["sw-username"])) : ?>
                                        <input type="text" class="form-control" name="username" value="<?= $_COOKIE["sw-username"] ?>" id="username">
                                    <?php else :  ?>
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" value="" id="username">
                                    <?php endif ?>

                                </div>
                                <div class="form-group last mb-4">
                                    <?php if (isset($_COOKIE["sw-pw"])) : ?>                                        
                                        <input type="password" class="form-control" name="password" value="<?= $_COOKIE["sw-pw"] ?>" id="password">
                                    <?php else :  ?>
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password">
                                    <?php endif ?>

                                </div>


                                <div class="d-flex mb-3 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" name="rememberme" checked="checked" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="<?= base_url('/forgot-password') ?>" class="forgot-pass">Forgot Password</a></span>
                                </div>
                                <input type="hidden" name="current" id="" value="<?= base_url(uri_string()) ?>">
                                <input type="submit" value="Log In" class="btn btn-block btn-primary">
                                <div style="text-align: center; padding-top: 10px;padding-left: 5px;">
                                    <a href="https://apps.apple.com/id/app/smart-fba-client-portal/id1618568127" target="_blink"><img src="/assets/images/appstore.png" style="max-width: 160px; max-height: 53px"></a>
                                    <a href="https://play.google.com/store/apps/details?id=smartfba.app.smartfbaclientportal" target="_blink"><img src="/assets/images/available-google-play.png" style="max-width: 172px; max-height: 53px"></a>                                    
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/login/js/jquery-3.3.1.min.js"></script>
    <script src="/assets/login/js/popper.min.js"></script>
    <script src="/assets/login/js/bootstrap.min.js"></script>
    <script src="/assets/login/js/main.js"></script>
    <script>
        function myFunction() {
            var popup = document.getElementById("myPopup");
            popup.classList.toggle("show");
        }
    </script>
</body>

</html>