<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Report Managmenet System | Login Page</title>
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

</head>

<body>
    <div class="content" style="padding-top: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="login-logo">
                        <img src="/assets/images/<?= $logo ?>" alt="Image" class="img-fluid">
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
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" id="username">

                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">

                                </div>


                                <div class="d-flex mb-3 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" checked="checked" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                                </div>
                                <input type="hidden" name="current" id="" value="<?= base_url(uri_string()) ?>">
                                <input type="submit" value="Log In" class="btn btn-block btn-primary">
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
</body>

</html>