<?php

namespace App\Models;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Config\Database;

class MovementItem
{
    public static function get(int $user_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM movements_item
            WHERE user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public static function getByID(int $user_id, int $item_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM movements_item
            WHERE user_id = :user_id AND id = :item_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "item_id" => $item_id,
        ]);

        $result = $stmt->fetch();

        return $result;
    }

    public static function getByMovement(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM movements_item
            WHERE user_id = :user_id AND mov_id = :mov_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public static function create(int $user_id, int $mov_id, int $product_id, float $quantity)
    {
        $conn = Database::connection();

        $sql = "
            INSERT INTO movements_item (user_id, mov_id, prd_id, qtd)
            VALUES (:user_id, :mov_id, :prd_id, :qtd)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
            "prd_id" => $product_id,
            "qtd" => $quantity,
        ]);
    }

    public static function updateQuantity(int $user_id, int $id, float $quantity)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE movements_item
            SET qtd = :qtd
            WHERE user_id = :user_id AND id = :id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "id" => $id,
            "qtd" => $quantity,
        ]);
    }

    public static function deleteByMovement(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            DELETE FROM movements_item
            WHERE mov_id = :mov_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);
    }

    public static function deleteByID(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            DELETE FROM movements_item
            WHERE id = :mov_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);
    }
}