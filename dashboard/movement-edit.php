<?php
require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/auth.php";

require_once __DIR__ . "/../vendor/autoload.php";

use App\Models\Product;
use App\Models\Movement;
use App\Models\MovementItem;

use App\Helpers\Redirect;

$products = Product::get($_SESSION["auth"]);

$movements = Movement::getByID($_SESSION["auth"], $_GET["id"]);

if(empty($movements)) {
    Redirect::to("/php/storetrack/404.php");
}

$mov_items = MovementItem::getByMovement($_SESSION["auth"], $_GET["id"]);

$readonly = $movements["status"] === "F" ? "readonly" : "";
$hidden = $movements["status"] === "F" ? "hidden" : "";
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
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex align-items-center gap-3">
                            <a href="./movements.php" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                            </a>

                            <h4 class="m-0 pl-2 font-weight-bold text-primary">
                                MOVEMENT EDIT
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="../routes/movement.php" method="POST" id="movementForm">
                                <input hidden value="<?= $_GET["id"] ?>" type="text" name="id">
                                <input hidden value="put" type="text" name="method">

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="status">Status</label>
                                        <select <?= $readonly ?> name="status" id="status" class="form-control">
                                            <option <?= $movements["status"] === "O" ? "selected" : "" ?> value="O">Open</option>
                                            <option <?= $movements["status"] === "F" ? "selected" : "" ?> value="F">Finished</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="type">Type</label>
                                        <select <?= $readonly ?> name="type" id="type" class="form-control">
                                            <option <?= $movements["type"] === "E" ? "selected" : "" ?> value="E">Entry</option>
                                            <option <?= $movements["type"] === "O" ? "selected" : "" ?> value="O">Outflow</option>
                                            <option <?= $movements["type"] === "A" ? "selected" : "" ?> value="A">Adjustment</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input <?= $readonly ?> value="<?= $movements["title"] ?>" type="text" class="form-control" name="title" id="title" placeholder="Insert a main title">
                                </div>

                                <div class="form-group">
                                    <label for="observation">Observation</label>
                                    <textarea <?= $readonly ?> placeholder="Insert details" class="form-control" name="observation" id="observation" rows="3"><?= $movements["obs"] ?></textarea>
                                </div>

                                <div class="card border-left-primary shadow-none">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-boxes"></i> Movement items
                                        </h6>
                                    </div>

                                    <div class="card-body">
                                        <div hidden class="form-row align-items-end">
                                            <div class="form-group col-md-6 mb-0">
                                                <label for="itemProduct">Product</label>
                                                <select id="itemProduct" class="form-control">
                                                    <?php foreach ($products as $product): ?>
                                                        <option
                                                            value="<?= $product["id"] ?>"
                                                            data-description="<?= htmlspecialchars($product["description"]) ?>">
                                                            <?= htmlspecialchars($product["description"]) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3 mb-0">
                                                <label for="itemQuantity">Quantity</label>
                                                <input type="number" min="0.01" step="any" class="form-control" id="itemQuantity" placeholder="0">
                                            </div>

                                            <div class="form-group col-md-3 mb-0">
                                                <button type="button" id="btnAddItem" class="btn btn-success btn-block">
                                                    <i class="fas fa-plus"></i> Add item
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered mb-0" id="itemsTable">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">#</th>
                                                        <th>Product</th>
                                                        <th class="text-center" style="width: 80px;">Unit</th>
                                                        <th style="width: 180px;">Quantity</th>
                                                        <th <?= $hidden ?> class="text-center" style="width: 100px;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($mov_items as $item): ?>
                                                        <tr class="item-row" data-product-id="<?= $item["prd_id"] ?>">
                                                            <td class="item-number text-center align-middle">
                                                                <?= $item["prd_id"] ?>
                                                            </td>
                                                            
                                                            <td class="align-middle">
                                                                <?php
                                                                $produto = Product::getByID($_SESSION["auth"], $item["prd_id"]);

                                                                echo $produto["description"];
                                                                ?>
                                                                <input type="hidden" name="prd_id[]" value="<?= $item["prd_id"] ?>">
                                                            </td>

                                                            <td class="text-center align-middle">
                                                                <?= $produto["abbrv"] ?>
                                                            </td>

                                                            <td>
                                                                <input <?= $readonly ?> type="number" min="0.01" step="any" class="form-control form-control-sm item-quantity" name="quantity[]" value="<?= $item["qtd"] ?>">
                                                            </td>
                                                            <td <?= $hidden ?> class="text-center align-middle">
                                                                <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                require __DIR__ . "/../resources/components/error.php";
                                ?>

                                <div class="w-100 d-flex justify-content-end mt-5">
                                    <a <?= $hidden ?> href="./movements.php" class="btn btn-danger btn-icon-split mr-2">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>

                                    <button <?= $hidden ?> type="submit" class="btn btn-success btn-icon-split">
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
    <script>
        $(function() {

            function renderItemNumbers() {
                $("#itemsTable .item-number").each(function(index) {
                    $(this).text(index + 1);
                });
            }

            function refreshEmptyRow() {
                if ($("#itemsTable .item-row").length === 0) {
                    if ($("#emptyItemsRow").length === 0) {
                        $("#itemsTable tbody").append(
                            '<tr id="emptyItemsRow">' +
                            '<td colspan="4" class="text-center text-secondary">No items added yet.</td>' +
                            "</tr>"
                        );
                    }
                } else {
                    $("#emptyItemsRow").remove();
                }
            }

            $("#btnAddItem").on("click", function() {
                const $product = $("#itemProduct");
                const $quantity = $("#itemQuantity");
                const productId = $product.val();
                const quantity = parseFloat($quantity.val());

                if (!productId) {
                    alert("Select a product.");
                    return;
                }

                if (!quantity || quantity <= 0) {
                    alert("Insert a valid quantity.");
                    return;
                }

                const $existing = $('.item-row[data-product-id="' + productId + '"]');

                if ($existing.length) {
                    $existing.find(".item-quantity").val(quantity);
                } else {
                    const description = $product.find("option:selected").data("description");

                    $("#itemsTable tbody").append(
                        '<tr class="item-row" data-product-id="' + productId + '">' +
                        '<td class="item-number text-center align-middle"></td>' +
                        '<td class="align-middle">' +
                        description +
                        '<input type="hidden" name="prd_id[]" value="' + productId + '">' +
                        "</td>" +
                        '<td>' +
                        '<input type="number" min="0.01" step="any" class="form-control form-control-sm item-quantity" name="quantity[]" value="' + quantity + '">' +
                        "</td>" +
                        '<td class="text-center align-middle">' +
                        '<button type="button" class="btn btn-danger btn-sm btn-remove-item">' +
                        '<i class="fas fa-trash"></i>' +
                        "</button>" +
                        "</td>" +
                        "</tr>"
                    );

                    $product.val("");
                    $quantity.val("");
                }

                renderItemNumbers();
                refreshEmptyRow();
                $product.focus();
            });

            $(document).on("click", ".btn-remove-item", function() {
                $(this).closest(".item-row").remove();
                renderItemNumbers();
                refreshEmptyRow();
            });

            $("#movementForm").on("submit", function(e) {
                if ($("#itemsTable .item-row").length === 0) {
                    e.preventDefault();
                    alert("Add at least one item to the movement.");
                }
            });
        });
    </script>

</body>

</html>