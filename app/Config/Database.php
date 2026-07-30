<?php

namespace App\Config;

use PDO;

class Database
{
    private static ?PDO $conn = null;

    public static function connection(): PDO
    {
        if (self::$conn === null) {
            self::$conn = new PDO(
                "mysql:host=localhost;dbname=storetrack;charset=utf8mb4",
                "root",
                "masterkey"
            );
        }

        return self::$conn;
    }
}