<?php
namespace App\Http;

use App\Controllers\ProductController;
use App\Controllers\Proxies\ProductControllerProxy;
use App\Controllers\CategoryController;
use App\Controllers\Proxies\CategoryControllerProxy;
use App\Utils\Logger;

class ControllerProxyConfigurer
{

    private $proxies;
    private $logger;

    public function __construct()
    {
        //this could go to a config file
        $this->proxies = array(
            ProductController::class => ProductControllerProxy::class,
            CategoryController::class => CategoryControllerProxy::class
        );
        
        $this->logger = Logger::getInstance();
    }
    
    public function getProxy(object $obj)
    {   
        if(isset($this->proxies[get_class($obj)]))
        {
            $proxyClass = $this->proxies[get_class($obj)];
            $obj = new $proxyClass($obj);
            $this->logger->log("Proxy found. Using: " . $proxyClass);
        }
        
        return $obj;
    }
}

