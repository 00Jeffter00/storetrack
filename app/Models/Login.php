<?php
namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";
class Login
{
    public static function find(string $data)
    {
        return "All data $data";
    }
}