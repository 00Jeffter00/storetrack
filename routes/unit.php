<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\UnitController;
use App\Helpers\Redirect;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

if($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["action"] === "delete") {
    UnitController::delete($_GET["id"], $_SESSION["auth"]);
};

if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["method"] === "put") {
    $_SESSION["old"] = $_POST;

    try {
        UnitController::update($_POST, $_SESSION["auth"]);
        unset($_SESSION["old"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a unit with this name or abbreviature!";
        Redirect::to("/php/storetrack/dashboard/unit-edit.php?id=" . $_POST['id']);
    }
};

if($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["method"])) {
    $_SESSION["old"] = $_POST;

    try {
        UnitController::store($_POST, $_SESSION["auth"]);
        unset($_SESSION["old"]);
    } catch (Exception $e) {
        $_SESSION["error"] = "Already exists a unit with this name or abbreviature!";
        Redirect::to("/php/storetrack/dashboard/unit-create.php");
    }
};

Redirect::to("/php/storetrack/dashboard/unities.php");