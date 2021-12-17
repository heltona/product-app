<?php
namespace App\Http;

use App\Controllers\CategoryController;
use App\Controllers\NotFoundController;
use Exception;
use App\Controllers\AbstractController;
use App\Controllers\ProductController;
use App\Controllers\HomeController;

class RouterService
{

    private $routes;

    public function __construct()
    {
        //this could go to a config file
        $this->routes = array(
            "POST" => array(
                "/category/create" => [
                    CategoryController::class,
                    "createNewCategory"
                ],
                "/category/delete" => [
                    CategoryController::class,
                    "deleteCategory"
                ],
                "/category/edit" => [
                    CategoryController::class,
                    "editCategory"
                ],
                
                //================= PRODUCTS ===============
                
                "/product/create" => [
                    ProductController::class,
                    "createNewProduct"
                ],
                "/product/delete" => [
                    ProductController::class,
                    "deleteProduct"
                ],
                "/product/edit" => [
                    ProductController::class,
                    "editProduct"
                ],
            ),
            "GET" => array(
                "/category/show" => [
                    CategoryController::class,
                    "getCategory"
                ],
                "/category/show-all" => [
                    CategoryController::class,
                    "getGetAllCategories"
                ],
                "/category/create" => [
                    CategoryController::class,
                    "getCreateNewCategory"
                ],
                "/category/delete" => [
                    CategoryController::class,
                    "getDeleteCategory"
                ],
                "/category/edit" => [
                    CategoryController::class,
                    "getEditCategory"
                ],
                
                //============ PRODUCTS ==================
                
                "/product/show" => [
                    ProductController::class,
                    "getProduct"
                ],
                "/product/show-all" => [
                    ProductController::class,
                    "getGetAllProducts"
                ],
                "/product/create" => [
                    ProductController::class,
                    "getCreateNewProduct"
                ],
                "/product/delete" => [
                    ProductController::class,
                    "getDeleteProduct"
                ],
                "/product/edit" => [
                    ProductController::class,
                    "getEditProduct"
                ],
                "/" => [
                    HomeController::class,
                    "getDashboard"
                ]
            )
        );
    }

    private function getRoute(Request $req)
    {
        $cMethod = $this->routes[$req->getRequestMethod()];

        if (! $cMethod)
            throw new Exception("Not supported method");

        $cPath = $cMethod[$req->getRequestPath()];

        if (! $cPath)
            throw new Exception("Not supported method");
        
        return $cPath;
    }

    public function getController(Request $req): AbstractController
    {
        try {
            
            $controller = $this->getRoute($req);
            return new $controller[0];
            
        } catch (Exception $ex) {
            return new NotFoundController();
        }
    }

    public function getMethod(Request $req): string
    {
        try {
            
            $route = $this->getRoute($req);
            return $route[1];
        } catch (Exception $ex) {
            return "getNotFounPage";
        }
    }
}

