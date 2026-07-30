<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Helpers\Redirect;
use App\Controllers\RegisterController;

session_start();

RegisterController::register($_POST);

Redirect::to("../dashboard/index.php");