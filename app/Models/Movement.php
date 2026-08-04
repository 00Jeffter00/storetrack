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

    public static function getByID(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            SELECT *
            FROM movements
            WHERE user_id = :user_id AND id = :mov_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);

        $result = $stmt->fetch();

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

    public static function update(int $user_id, int $mov_id, string $type, string $title, string $obs, string $status)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE movements 
            SET type = :type, title = :title, obs = :obs, status = :status
            WHERE user_id = :user_id AND id = :mov_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
            "type" => $type,
            "title" => $title,
            "obs" => $obs,
            "status" => $status,
        ]);
    }

    public static function updateStatus(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            UPDATE movements 
            SET status = 'A'
            WHERE user_id = :user_id AND id = :mov_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);
    }

    public static function delete(int $user_id, int $mov_id)
    {
        $conn = Database::connection();

        $sql = "
            DELETE FROM movements
            WHERE id = :mov_id AND user_id = :user_id
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "user_id" => $user_id,
            "mov_id" => $mov_id,
        ]);
    }

    public static function getTotals(int $user_id, ?string $type = null, ?string $start = null, ?string $end = null)
    {
        $conn = Database::connection();

        $sql = "
            SELECT
                COALESCE(SUM(CASE WHEN m.type = 'E' THEN mi.qtd ELSE 0 END), 0) AS entrada,
                COALESCE(SUM(CASE WHEN m.type = 'O' THEN mi.qtd ELSE 0 END), 0) AS saida,
                COALESCE(SUM(CASE WHEN m.type = 'A' THEN mi.qtd ELSE 0 END), 0) AS ajuste
            FROM movements m
                INNER JOIN movements_item mi ON (mi.mov_id = m.id)
            WHERE m.user_id = :user_id
                AND m.status = 'F'
        ";

        $params = ["user_id" => $user_id];

        if ($type !== null && in_array($type, ["E", "O", "A"], true)) {
            $sql .= " AND m.type = :type ";
            $params["type"] = $type;
        }

        if ($start !== null) {
            $sql .= " AND m.created_at >= :start ";
            $params["start"] = $start;
        }

        if ($end !== null) {
            $sql .= " AND m.created_at <= :end ";
            $params["end"] = $end;
        }

        $stmt = $conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetch();
    }

    public static function getMonthly(int $user_id, ?string $type = null, ?string $start = null, ?string $end = null)
    {
        $conn = Database::connection();

        $sql = "
            SELECT
                DATE_FORMAT(m.created_at, '%Y-%m') AS month,
                m.type,
                SUM(mi.qtd) AS total
            FROM movements m
                INNER JOIN movements_item mi ON (mi.mov_id = m.id)
            WHERE m.user_id = :user_id
                AND m.status = 'F'
        ";

        $params = ["user_id" => $user_id];

        if ($type !== null && in_array($type, ["E", "O", "A"], true)) {
            $sql .= " AND m.type = :type ";
            $params["type"] = $type;
        }

        if ($start !== null) {
            $sql .= " AND m.created_at >= :start ";
            $params["start"] = $start;
        }

        if ($end !== null) {
            $sql .= " AND m.created_at <= :end ";
            $params["end"] = $end;
        }

        $sql .= "
            GROUP BY month, m.type
            ORDER BY month ASC
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function getTopProducts(int $user_id, ?string $type = null, ?string $start = null, ?string $end = null, int $limit = 5)
    {
        $conn = Database::connection();

        $sql = "
            SELECT
                p.description,
                SUM(mi.qtd) AS total
            FROM movements_item mi
                INNER JOIN movements m ON (m.id = mi.mov_id)
                INNER JOIN products p ON (p.id = mi.prd_id)
            WHERE m.user_id = :user_id
                AND m.status = 'F'
        ";

        $params = ["user_id" => $user_id];

        if ($type !== null && in_array($type, ["E", "O", "A"], true)) {
            $sql .= " AND m.type = :type ";
            $params["type"] = $type;
        }

        if ($start !== null) {
            $sql .= " AND m.created_at >= :start ";
            $params["start"] = $start;
        }

        if ($end !== null) {
            $sql .= " AND m.created_at <= :end ";
            $params["end"] = $end;
        }

        $sql .= "
            GROUP BY p.id, p.description
            ORDER BY total DESC
            LIMIT " . (int) $limit . "
        ";

        $stmt = $conn->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}