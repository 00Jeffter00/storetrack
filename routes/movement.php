<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\MovementController;
use App\Helpers\Redirect;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["method"])) {
    MovementController::store($_SESSION["auth"], $_POST);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["method"] === "put") {
    MovementController::update($_SESSION["auth"], $_POST);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["action"] === "delete") {
    MovementController::delete($_SESSION["auth"], $_GET["id"]);
}

Redirect::to("/php/storetrack/dashboard/movements.php");