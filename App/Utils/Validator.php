<?php
namespace App\Utils;

//rules and messages could come from /config/validation.ini
class Validator
{
    //as you can see, all this does is... nothing.
    public function hasErrors(): bool
    {
        return false;
    }
    
    public function getErrors(): array
    {
        return array();
    }
}

