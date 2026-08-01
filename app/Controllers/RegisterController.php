<?php

namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../Helpers/Redirect.php";

use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Helpers\Redirect;

class RegisterController
{
    public static function register(array $data)
    {
        $_SESSION["oldData"] = $_POST;

        // 1. Field validation
        foreach ($data as $key => $value) {
            if ($value == "") {
                $_SESSION["error"] = "Fill all fields!";
                break;
            }

            switch ($key) {
                case "password":
                    if (strlen($value) < 8) {
                        $_SESSION["error"] = "Password have less then 8 characters!";
                    }
                    break;
                case "confirmation":
                    if ($data["password"] !== $value) {
                        $_SESSION["error"] = "Password doesn't match!";
                    }
                    break;
            }
        }

        isset($_SESSION["error"]) ? Redirect::to("../create-account.php") : "";

        // 2. Email validation
        if (User::emailExists($data["email"])) {
            $_SESSION["error"] = "Email already registered!";
            Redirect::to("../create-account.php");
        }

        unset($_SESSION["oldData"]);

        // 3. Account creation
        $user_id = User::create($data["name"], $data["email"], $data["password"]);     

        Category::insert($user_id, "General");
        Unit::insert($user_id, "Unit", "UN");
    }
}
