<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\ProductController;
use App\Helpers\Redirect;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["method"])) {
    $_SESSION["old"] = $_POST;

    try {
        ProductController::store($_POST, $_SESSION["auth"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a product with this name!";
        Redirect::to("/php/storetrack/dashboard/products-create.php");
    }

    $_SESSION["success"] = "Success on create product: " . $_POST["description"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["method"] === "put") {
    try {
        ProductController::update($_POST, $_SESSION["auth"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a product with this name!";
        Redirect::to("/php/storetrack/dashboard/product-edit.php?id=" . $_POST['product_id']);
    }

    $_SESSION["success"] = "Success on change product id " . $_POST["product_id"];
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["action"] === "delete") {
    ProductController::delete($_GET["id"], $_SESSION["auth"]);
    $_SESSION["success"] = "Success on delete product id " . $_GET["id"];
}

Redirect::to("/php/storetrack/dashboard/products.php");