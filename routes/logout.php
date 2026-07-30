<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Helpers\Redirect;

session_status() === PHP_SESSION_ACTIVE ? "" : session_start();

session_destroy();

setcookie(
    "remember_token",
    "",
    time() - (60 * 60 * 24 * 30),
    "/",
    "",
    true,
    true
);

Redirect::to("/php/storetrack/index.php");