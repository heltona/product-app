<?php
namespace App\Http;

class Dispatcher
{

    public function run()
    {
        $request = new Request();
        $router = new RouterService();
        $proxyService = new ControllerProxyConfigurer();
        
        $controller = $router->getController($request);
        $controller = $proxyService->getProxy($controller);
        $methodName = $router->getMethod($request);
        
        $controller->$methodName($request);
    }
}

