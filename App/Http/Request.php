<?php
namespace App\Http;

class Request
{
    public function getRequestPath(): string
    {
        $path = explode("?", $_SERVER['REQUEST_URI']);
        return $path[0];
    }
    
    public function getRequestMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }
    
    public function getRequestAttribute(string $attribute)
    {
        $data = array_merge($_GET, $_POST);
        return $data[$attribute] ?? "";
    }
    
    //other methods are out of scope
}

