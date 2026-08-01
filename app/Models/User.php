<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class User
{
    public static function create(string $name, string $email, string $password)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) $conn->lastInsertId();
    }

    public static function emailExists(string $email): bool
    {
        $conn = Database::connection();

        $sql = "
            SELECT email
            FROM users
            WHERE email = :email
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([   
            "email"=> $email
        ]);

        $result = $stmt->fetch();

        return $result !== false;
    }

    public static function findByEmail(string $email)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM users
            WHERE email = :email
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([   
            "email"=> $email
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function activeRemember(int $id, string $email, $token): void
    {
        $conn = Database::connection();

        $sql = "
            UPDATE users
            SET remember_token = :token
            WHERE email = :email AND id = :id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([   
            "id"=> $id,
            "email"=> $email,
            "token"=> $token,
        ]);
    }
}