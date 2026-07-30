<?php 
    require_once __DIR__ . "/../config/app.php"; 
    require_once __DIR__ . "/../config/auth.php"; 
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= APP_NAME ?> - Products</title>

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
            $active = "products";
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
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Description</th>
                                            <th>Group</th>
                                            <th>Unit</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <?php

                                            require __DIR__ . "/../vendor/autoload.php";

                                            use App\Config\Database;

                                            $conn = Database::connection();

                                            $sql = "
                                                SELECT 
                                                    p.*,
                                                    c.description as category,
                                                    u.description as unit
                                                FROM products p
                                                    LEFT JOIN categories c ON (c.id = p.category_id)
                                                    LEFT JOIN unities u ON (u.id = p.unit_id)
                                                WHERE p.user_id = :user_id
                                            ";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute([
                                                ":user_id" => $_SESSION["auth"]
                                            ]);

                                            $products = $stmt->fetchAll();

                                            for($i = 0; $i < 25; $i++) {
                                                foreach ($products as $product) {
                                                    echo "
                                                        <tr>
                                                            <td> $i </td>
                                                            <td> Produto $i </td>
                                                            <td>". $product["category"] ."</td>
                                                            <td>". $product["unit"] ."</td>
                                                            <td>". $product["quantity"] ."</td>
                                                            <td>". $product["price"] ."</td>
                                                            <td>
                                                                <a>Change</a>
                                                                <a>Delete</a>
                                                            </td>
                                                        </tr>
                                                    ";
                                                };
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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