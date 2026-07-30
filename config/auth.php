<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Helpers\Redirect;

if(!isset($_SESSION["auth"])) {
    Redirect::to("/php/storetrack/index.php");
}