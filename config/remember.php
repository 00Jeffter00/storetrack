<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Config\Database;
use App\Helpers\Redirect;

if(isset($_SESSION["auth"])) {
    Redirect::to("/php/storetrack/dashboard/index.php");
}

if (isset($_COOKIE["remember_token"])) {
    $token = hash("sha256", $_COOKIE["remember_token"]);

    $conn = Database::connection();

    $sql = "
            SELECT * 
            FROM users
            WHERE remember_token = :token
        ";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":token" => $token,
    ]);

    $result = $stmt->fetch();

    if (!empty($result)) {
        $_SESSION["auth"] = hash("sha256", $result["id"]);
        Redirect::to("/php/storetrack/dashboard/index.php");
    }
}