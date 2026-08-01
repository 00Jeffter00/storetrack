<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Helpers\Redirect;
use App\Controllers\CategoryController;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["method"] === "put") {
    try {
        CategoryController::update($_POST, $_SESSION["auth"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a category with this description!";
        Redirect::to("/php/storetrack/dashboard/category-edit.php?id=" . $_POST['id']);
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["action"] === "delete") {
    CategoryController::delete($_GET["id"], $_SESSION["auth"]);
}

if($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["method"])) {
    try {
        CategoryController::store($_POST, $_SESSION["auth"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a category with this description!";
        Redirect::to("/php/storetrack/dashboard/category-create.php");
    }
}

Redirect::to("/php/storetrack/dashboard/categories.php");