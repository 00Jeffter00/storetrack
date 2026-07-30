<?php

namespace App\Controllers;

use App\Helpers\Redirect;
use App\Models\User;

require_once __DIR__ . "/../../vendor/autoload.php";

class LoginController
{
    public static function validate(array $data)
    {
        // 1. Field validation
        foreach ($data as $key => $value) {
            if ($value == "") {
                $_SESSION["error"] = "Fill all fields!";
                break;
            }
        }

        isset($_SESSION["error"]) ? Redirect::to("../index.php") : "";

        // 2. Find user data by id
        $user = User::findByEmail($data["email"]);

        if (empty($user)) {
            $_SESSION["error"] = "Invalid credentials!";
            Redirect::to("../index.php");
        };

        if (password_verify($data["password"], $user["password"]) === false) {
            $_SESSION["error"] = "Invalid credentials!";
            Redirect::to("../index.php");
        };

        // 3. Remember?
        $tokenHash = null;
        if (isset($data["remember"])) {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash("sha256", $token);

            setcookie(
                "remember_token",
                $token,
                time() + (60 * 60 * 24 * 30), 
                "/",
                "",
                true,  
                true   
            );

            User::activeRemember($user["id"], $user["email"], $tokenHash);
        } else {
            setcookie(
                "remember_token",
                "",
                time() - (60 * 60 * 24 * 30), 
                "/",
                "",
                true, 
                true   
            );

            User::activeRemember($user["id"], $user["email"], null);
        }

        $_SESSION["auth"] = hash("sha256", $user["id"]);

        Redirect::to("../dashboard/index.php");
    }
}
