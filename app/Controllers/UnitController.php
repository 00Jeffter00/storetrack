<?php

namespace App\Controllers;

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Helpers\Redirect;
use App\Models\Unit;

class UnitController
{
    public static function store(array $data, int $userId)
    {
        if(empty($data["description"])) {
            $_SESSION["error"] = "Fill a description!";
            Redirect::to("/php/storetrack/dashboard/unit-create.php");
        };

        if(empty($data["abbrv"])) {
            $_SESSION["error"] = "Fill a abbreviation!";
            Redirect::to("/php/storetrack/dashboard/unit-create.php");
        };

        Unit::insert($userId, $data["description"], $data["abbrv"]);
        $_SESSION["success"] = "Success on create a new unit type!";
    }

    public static function update(array $data, int $userId)
    {
        if(empty($data["description"])) {
            $_SESSION["error"] = "Fill a description!";
            Redirect::to("/php/storetrack/dashboard/unit-edit.php?id=" . $data["id"]);
        };

        if(empty($data["abbrv"])) {
            $_SESSION["error"] = "Fill a abbreviation!";
            Redirect::to("/php/storetrack/dashboard/unit-edit.php?id=" . $data["id"]);
        };

        Unit::update($userId, $data["id"], $data["description"], $data["abbrv"]);
        $_SESSION["success"] = "Success on update unit type!";
    }

    public static function delete(int $unitId, int $userId)
    {
        $ref = Unit::checkReferential($userId, $unitId);

        if (!empty($ref)) {
            $_SESSION["error"] = "Can't delete! This category was used in a product!";
            Redirect::to("/php/storetrack/dashboard/unities.php");
        }

        Unit::delete($userId, $unitId);
        $_SESSION["success"] = "Category updated successfully!";
    }
}