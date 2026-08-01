<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class Unit 
{
    public static function get(int $user_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM unities
            WHERE user_id = :user_id
            ORDER BY id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public static function getByID(int $user_id, int $unit_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM unities
            WHERE user_id = :user_id AND id = :unit_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "unit_id" => $unit_id,
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function checkReferential(int $user_id, int $unit_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM products
            WHERE unit_id = :unit_id AND user_id = :user_id
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "unit_id" => $unit_id,
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function insert(int $user_id, string $description, string $abbrv)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO unities
            (user_id, description, abbrv)
            VALUES (:user_id, :description, :abbrv)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "description" => $description,
            "abbrv" => $abbrv,
        ]);
    }

    public static function update(int $user_id, int $unit_id, string $description, string $abbrv)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE unities
            SET description = :description, abbrv = :abbrv
            WHERE user_id = :user_id AND id = :unit_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "unit_id" => $unit_id,
            "description" => $description,
            "abbrv" => $abbrv,
        ]);
    }

    public static function delete(int $user_id, int $unit_id)
    {
        $conn = Database::connection();

        $sql = "
            DELETE FROM unities
            WHERE user_id = :user_id AND id = :unit_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "unit_id" => $unit_id,
        ]);
    }
}