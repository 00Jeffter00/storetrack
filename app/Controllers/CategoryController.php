<?php

namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helpers\Redirect;

use App\Models\Category;

class CategoryController
{
    public static function store(array $data, int $userId)
    {
        if (empty($data["description"])) {
            $_SESSION["error"] = "Fill a description";
            Redirect::to("/php/storetrack/dashboard/category-create.php");
        }

        Category::insert($userId, $data["description"]);
        $_SESSION["success"] = "New category inserted!";
    }

    public static function update(array $data, int $userId)
    {
        if (empty($data["description"])) {
            $_SESSION["error"] = "Fill a description";
            Redirect::to("/php/storetrack/dashboard/category-create.php");
        }

        Category::update($userId, $data["id"], $data["description"]);
        $_SESSION["success"] = "Category updated successfully!";
    }

    public static function delete(int $categoryId, int $userId)
    {
        $ref = Category::checkReferential($userId, $categoryId);

        if (!empty($ref)) {
            $_SESSION["error"] = "Can't delete! This category was used in a product!";
            Redirect::to("/php/storetrack/dashboard/categories.php");
        }

        Category::delete($userId, $categoryId);
        $_SESSION["success"] = "Category updated successfully!";
    }
}
