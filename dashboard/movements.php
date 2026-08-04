<?php
require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/auth.php";

require __DIR__ . "/../vendor/autoload.php";

use App\Models\Movement;

$movements = Movement::get($_SESSION["auth"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= APP_NAME ?> - Movements</title>

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
        $active = "movements";
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

                    <?php
                    require __DIR__ . "/../resources/components/success.php";
                    ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0 font-weight-bold text-primary">
                                <i class="fa-solid fa-people-carry-box"></i> Movements
                            </h4>

                            <a href="./movement-create.php" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add new movement</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Observation</th>
                                            <th>Created At</th>
                                            <th class="bg-light text-secondary">Canceled at</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $id = 0;
                                        foreach ($movements as $movement):
                                        ?>
                                            <?php $id++ ?>
                                            <tr>
                                                <td><?= $id ?></td>
                                                <td><?= htmlspecialchars($movement["status"]) ?></td>
                                                <td><?= htmlspecialchars($movement["type"]) ?></td>
                                                <td><?= htmlspecialchars($movement["title"]) ?></td>
                                                <td><?= htmlspecialchars($movement["obs"]) ?></td>  
                                                <td><?= htmlspecialchars($movement["created_at"]) ?></td>
                                                <td class="bg-light text-secondary"><?= htmlspecialchars($movement["canceled_at"]) ?></td>
                                                <td>
                                                    <a href="./movement-edit.php?id=<?= $movement["id"] ?>" class="btn btn-secondary btn-icon-split">
                                                        <span class="icon text-white">
                                                            <?php if ($movement["status"] === "F"): ?>
                                                                <i class="fas fa-eye"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-pencil"></i>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>

                                                    <?php if ($movement["status"] === "F"): ?>
                                                        <a href="../routes/movement.php?id=<?= $movement["id"] ?>&action=delete" class="btn btn-warning btn-icon-split">
                                                            <span class="icon text-white">
                                                                <i class="fa-solid fa-reply"></i>
                                                            </span>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="../routes/movement.php?id=<?= $movement["id"] ?>&action=delete" class="btn btn-danger btn-icon-split">
                                                            <span class="icon text-white">
                                                                <i class="fas fa-trash"></i>
                                                            </span>
                                                        </a>
                                                    <?php endif; ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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