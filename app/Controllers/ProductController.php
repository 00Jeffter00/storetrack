<?php

namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Models\Product;
use App\Helpers\Redirect;

class ProductController
{
    public static function store(array $data, int $userId)
    {
        if(empty($data["description"])) {
            $_SESSION["error"] = "Fill a description!";
            Redirect::to("/php/storetrack/dashboard/products-create.php");
        };

        if(empty($data["price"])) {
            $_SESSION["error"] = "Fill a price!";
            Redirect::to("/php/storetrack/dashboard/products-create.php");
        };

        unset($_SESSION["old"]);
        Product::insert($userId, $data["category"], $data["unit"], $data["description"], $data["price"]);
    }

    public static function update(array $data, int $userId)
    {
        $_SESSION["old"] = $data;

        if(empty($data["description"])) {
            $_SESSION["error"] = "Fill a description!";
            Redirect::to("/php/storetrack/dashboard/product-edit.php?id=". $data["product_id"]);
        };

        if(empty($data["price"])) {
            $_SESSION["error"] = "Fill a price!";
            Redirect::to("/php/storetrack/dashboard/product-edit.php?id=". $data["product_id"]);
        };

        if(!is_numeric($data["price"])){
            $_SESSION["error"] = "Fill a valid numerical price!";
            Redirect::to("/php/storetrack/dashboard/product-edit.php?id=". $data["product_id"]);
        };

        Product::update($userId, $data["product_id"], $data["unit"], $data["category"], $data["description"], $data["price"]);
    }

    public static function delete(int $prdId, int $userId)
    {
        Product::delete($userId, $prdId);
    }
}