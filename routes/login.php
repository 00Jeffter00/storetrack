<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\LoginController;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

LoginController::validate($_POST);