<?php 
    require_once __DIR__ . "/config/app.php"; 

    $oldName = $oldEmail = "";

    if(isset($_SESSION["oldData"])) {
        $oldName = $_SESSION["oldData"]["name"];
        $oldEmail = $_SESSION["oldData"]["email"];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= APP_NAME ?> - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="resources/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body 
    class="d-flex align-items-center vh-100"
    style="background: url('resources/img/login-bg.png') no-repeat center; background-size: cover;"
>

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                                <img src="resources/img/register-img.png" class="img-fluid" style="max-width: 80%;">
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900">Create an <b>Account!</b></h1>
                                <p>Enter your information</p>
                            </div>

                            <form class="user" action="./routes/register.php" method="POST">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input value="<?= $oldName ?>" type="text" class="form-control form-control-user" id="name" name="name"
                                            placeholder="Your name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input value="<?= $oldEmail ?>" type="email" class="form-control form-control-user" id="email" name="email"
                                        placeholder="Email Address">
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name="password" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="confirmation" name="confirmation" placeholder="Repeat Password">
                                    </div>
                                </div>

                                <?php 
                                    require __DIR__ . "/resources/components/error.php" 
                                ?>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="index.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="resources/js/sb-admin-2.min.js"></script>

</body>

</html>