<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\LoginController;

echo LoginController::validate($_POST["name"]);