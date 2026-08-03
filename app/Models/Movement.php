<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class Movement
{
    public static function get(int $user_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM movements
            WHERE user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public static function create(int $user_id, string $type, string $title, string $obs, string $status)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO movements (user_id, type, title, obs, status)
            VALUES (:user_id, :type, :title, :obs, :status)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "type" => $type,
            "title" => $title,
            "obs" => $obs,
            "status" => $status,
        ]);

        return (int) $conn->lastInsertId();
    }
}