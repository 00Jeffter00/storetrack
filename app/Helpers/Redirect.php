<?php

namespace App\Helpers;

class Redirect
{
    public static function to(string $url) {
        header("Location: " . $url);
        exit;
    }
}