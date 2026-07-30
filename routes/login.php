<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\LoginController;

session_start();

LoginController::validate($_POST);