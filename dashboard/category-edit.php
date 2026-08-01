<?php
require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../vendor/autoload.php";

use App\Models\Category;
use App\Helpers\Redirect;

$category = Category::getByID($_SESSION["auth"], $_GET["id"]);

if (empty($category)) {
    Redirect::to("/php/storetrack/404.php");
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

    <title><?= APP_NAME ?> - Categories</title>

    <!-- Custom fonts for this template-->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../resources/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        $active = "categories";
        require __DIR__ . "/../resources/components/navbar.php"
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                require __DIR__ . "/../resources/components/header.php";
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex align-items-center gap-3">
                            <a href="./categories.php" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                            </a>

                            <h4 class="m-0 pl-2 font-weight-bold text-primary">
                                CATEGORY EDIT
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="../routes/category.php" method="POST">

                                <input hidden readonly value="put" name="method" id="method" />
                                <input hidden readonly value="<?= $category["id"] ?>" name="id" id="id" />

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input value="<?= $category["description"] ?>" type="text" class="form-control" name="description" id="description" placeholder="Your category name here">
                                </div>

                                <?php
                                require __DIR__ . "/../resources/components/error.php";
                                ?>

                                <div class="w-100 d-flex justify-content-end ">
                                    <a href="./categories.php" class="btn btn-danger btn-icon-split mr-2">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>

                                    <button type="submit" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Confirm</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require __DIR__ . "/../resources/components/footer.php" ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://kit.fontawesome.com/9b074a5685.js" crossorigin="anonymous"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/components/jquery/jquery.min.js"></script>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../resources/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../resources/js/datatables-demo.js"></script>

</body>

</html>