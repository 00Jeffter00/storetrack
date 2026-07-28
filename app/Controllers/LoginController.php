<?php
namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Models\Login;

class LoginController 
{
    public static function validate(string $username)
    {
        return login::find($username);
    }
}