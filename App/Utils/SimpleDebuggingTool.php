<?php
namespace App\Utils;

class SimpleDebuggingTool
{
    public static function dump($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        exit();
    }
}

