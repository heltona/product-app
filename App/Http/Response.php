<?php
namespace App\Http;


class Response
{
    private function __construct(){}
    
    private static $response;
    
    //no sense for reponses (plural) roaming about the app
    public static function getInstance()
    {
        if(!self::$response) {
            self::$response = new Response();
        }
        
        return self::$response;
    }
    
    public function setResponseCode(int $code) {
        http_response_code($code);
    }
    
    //add headers, set cookie are out of scope
}

