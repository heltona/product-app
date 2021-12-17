<?php
namespace App\Utils;

class Logger
{
    private static $logger;
    
    private function __construct()
    {
        //change only cli php.ini; apache uses syslog 
        ini_set("error_log", "../log/logs");
    }
    
    public static function getInstance(): Logger
    {
        if(!self::$logger) {
            self::$logger = new Logger();
        }
        
        return self::$logger;
    }
    
    public function log(string $message)
    {
        error_log($message);
    }
}

