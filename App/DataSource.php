<?php
namespace App;

use PDO;

class DataSource
{

    private static $instance;
    
    private function __construct(){}

    public static function getInstance(): PDO
    {
        if (! self::$instance) {
            $config = self::getConfiguration();
            self::$instance = new PDO("mysql:host=" . $config["host"] .";dbname=". $config["database"], $config["user"], $config["password"]);
            
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        }
        
        return self::$instance;
    }
    
    private static function getConfiguration():array 
    {
        $config = parse_ini_file("../config/datasource.ini");
        return $config;
    }
    
    
}

