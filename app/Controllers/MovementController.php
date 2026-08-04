<?php

namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helpers\Redirect;
use App\Models\Product;
use App\Models\Movement;
use App\Models\MovementItem;

class MovementController
{
    public static function store(int $user_id, array $data)
    {
        if(empty($data["type"]) || empty($data["title"]) || empty($data["observation"]) || empty($data["status"])) {
            $_SESSION["error"] = "Fill all fields!";
            Redirect::to("/php/storetrack/dashboard/movement-create.php");
        }

        $movement = Movement::create($user_id, $data["type"], $data["title"], $data["observation"], $data["status"]);

        $items = [];

        foreach ($data["prd_id"] as $index => $prd_id) {
            $items[] = [
                "prd_id"   => $prd_id,
                "quantity" => $data["quantity"][$index]
            ];
        }

        $quantity_type = " quantity + :qtd ";
        switch ($data["type"]) {
            case "O":
                $quantity_type = " quantity - :qtd ";
                break;
            case "A":
                $quantity_type = " :qtd ";
                break;
        }

        foreach ($items as $item) {
            MovementItem::create($user_id, $movement, $item["prd_id"], $item["quantity"]);

            if ($data["status"] === "F") {
                Product::updateQuantity($_SESSION["auth"], $item["prd_id"], $item["quantity"], $quantity_type);
            }
        }

        $_SESSION["success"] = "Movement created successfully";
    }

    public static function update(int $user_id, array $data)
    {
        if(empty($data["type"]) || empty($data["title"]) || empty($data["observation"]) || empty($data["status"])) {
            $_SESSION["error"] = "Fill all fields!";
            Redirect::to("/php/storetrack/dashboard/movement-edit.php?id=".$data["id"]);
        }

        $already_finished = Movement::getByID($user_id, $data["id"]);

        if($already_finished["status"] === "F") {
            Redirect::to("/php/storetrack/404.php");
        }

        Movement::update($user_id, $data["id"], $data["type"], $data["title"], $data["observation"], $data["status"]);
        $mov_items = MovementItem::getByMovement($user_id, $data["id"]);

        $movement = " quantity + :qtd ";
        switch ($data["type"]) {
            case "O":
                $movement = " quantity - :qtd ";
                break;
            case "A":
                $movement = " :qtd ";
                break;
        }

        $post_products = array_combine($data["prd_id"], $data["quantity"]);
        $db_products = [];

        for ($i = 0; $i < count($mov_items); $i++) {
            if (!in_array($mov_items[$i]["prd_id"], $data["prd_id"])) {
                MovementItem::deleteByID($user_id, $mov_items[$i]["id"]);
            };

            MovementItem::updateQuantity($user_id, $mov_items[$i]["id"], $post_products[$mov_items[$i]["prd_id"]]);

            $db_products[$mov_items[$i]["prd_id"]] = $mov_items[$i]["qtd"];
        }

        foreach ($post_products as $key => $value) {
            if (!array_key_exists($key, $db_products)) {
                MovementItem::create($user_id, $data["id"], $key, $value);
            }

            if ($data["status"] === "F") {
                Product::updateQuantity($user_id, $key, $value, $movement);
            }
        }

        $_SESSION["success"] = "Movement updated successfully";
    }

    public static function delete(int $user_id, int $movement_id)
    {
        $mov = Movement::getByID($user_id, $movement_id);

        if ($mov["status"] === "F") {
            $mov_item = MovementItem::getByMovement($user_id, $movement_id);

            $movement = " quantity + :qtd ";
            if ($mov["type"] === "E" || $mov["type"] === "A") {
                $movement = " quantity - :qtd ";
            }

            foreach ($mov_item as $item) {
                Product::updateQuantity($user_id, $item["prd_id"], $item["qtd"], $movement);
            }

            Movement::updateStatus($user_id, $movement_id);
            $_SESSION["success"] = "Movement reopened successfully";
        } else {

            Movement::delete($user_id, $movement_id);
            MovementItem::deleteByMovement($user_id, $movement_id);
    
            $_SESSION["success"] = "Movement deleted successfully";
        }

    }
}
