<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class Category 
{
    public static function get(int $user_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM categories
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

    public static function getByID(int $user_id, int $category_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM categories
            WHERE user_id = :user_id AND id = :category_id
            ORDER BY id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "category_id" => $category_id,
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function checkReferential(int $user_id, int $category_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM products
            WHERE category_id = :category_id AND user_id = :user_id
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "category_id" => $category_id,
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function insert(int $user_id, string $description)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO categories
            (user_id, description)
            VALUES (:user_id, :description)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "description" => $description,
        ]);
    }

    public static function update(int $user_id, int $category_id, string $description)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE categories
            SET description = :description
            WHERE user_id = :user_id AND id = :category_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "category_id" => $category_id,
            "description" => $description,
        ]);
    }

    public static function delete(int $user_id, int $category_id)
    {
        $conn = Database::connection();

        $sql = "
            DELETE FROM categories
            WHERE user_id = :user_id AND id = :category_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $user_id,
            "category_id" => $category_id,
        ]);
    }
}