<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class Product
{
    public static function get(int $userId)
    {
        $conn = Database::connection();

        $sql = "
            SELECT 
                p.*,
                c.description as category,
                u.description as unit,
                u.abbrv
            FROM products p
                LEFT JOIN categories c ON (c.id = p.category_id)
                LEFT JOIN unities u ON (u.id = p.unit_id)
            WHERE p.user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":user_id" => $userId
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public static function getByID(int $userId, int $productId)
    {
        $conn = Database::connection();

        $sql = "
            SELECT 
                p.*,
                c.description as category,
                u.description as unit,
                u.abbrv

            FROM products p
                LEFT JOIN categories c ON (c.id = p.category_id)
                LEFT JOIN unities u ON (u.id = p.unit_id)

            WHERE 
                p.user_id = :user_id 
                AND p.id = :prd_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":user_id" => $userId,
            ":prd_id" => $productId
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function insert(int $userId, int $category, int $unit, string $description, float $price)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO products
            (user_id, category_id, unit_id, description, price, old_price)
            VALUES
            (:user_id, :category_id, :unit_id, :description, :price, :old_price)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "user_id" => $userId,
            "category_id" => $category,
            "unit_id" => $unit,
            "description" => $description,
            "price" => $price,
            "old_price" => $price,
        ]);
    }

    public static function update(
        int $userId, 
        int $prdId, 
        int $unit, 
        int $category, 
        string $description, 
        float $price)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE products
            SET old_price = price
            WHERE id = :prd_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "prd_id" => $prdId,
            "user_id" => $userId,
        ]);

        $sql = "
            UPDATE products

            SET 
                category_id = :category_id, 
                unit_id = :unit_id, 
                description = :description, 
                price = :price

            WHERE id = :prd_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "category_id" => $category,
            "unit_id" => $unit,
            "description" => $description,
            "price" => $price,
            "prd_id" => $prdId,
            "user_id" => $userId,
        ]);
    }

    public static function updateQuantity(
        int $user_id, 
        int $prd_id, 
        float $quantity, 
        string $movement
    ) {
        $conn = Database::connection();

        $sql = "
            UPDATE products
            SET quantity = $movement
            WHERE id = :prd_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "prd_id" => $prd_id,
            "user_id" => $user_id,
            "qtd" => $quantity,
        ]);
    }

    public static function delete(
        int $userId, 
        int $prdId, 
    )
    {
        $conn = Database::connection();
        $result = true;

        $sql = "
            SELECT *
            FROM movements_item
            WHERE prd_id = :prd_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            "prd_id" => $prdId,
            "user_id" => $userId,
        ]);

        $movements = $stmt->fetch();

        if(empty($movements)) {
            $sql = "
                DELETE FROM products
                WHERE id = :prd_id AND user_id = :user_id
            ";
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                "prd_id" => $prdId,
                "user_id" => $userId,
            ]);
        } else {
            $result = false;
        }

        return $result;
    }
}
