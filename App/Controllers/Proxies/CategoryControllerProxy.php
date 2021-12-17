<?php
namespace App\Controllers\Proxies;

use App\Controllers\CategoryController;
use App\Model\Category;
use App\Http\Request;
use App\DataMapper\DataMapper;

class CategoryControllerProxy
{
    private $controller;
    private $dataMapper;
    
    public function __construct(CategoryController $controller)
    {
        $this->controller = $controller;
        $this->dataMapper = new DataMapper();
    }
    
    //================ POST =====================
    
    public function createNewCategory(Request $req)
    {
        $this->controller->createNewCategory($this->dataMapper->mapCategory($req));
    }
    
    public function editCategory(Request $req)
    {
        $this->controller->editCategory($this->dataMapper->mapCategory($req));
    }
    
    public function deleteCategory(Request $req)
    {
        $this->controller->deleteCategory($req->getRequestAttribute("id"));
    }
    
    
    //================ GET ==================================
    
    
    public function getCreateNewCategory()
    {
        $this->controller->getCreateNewCategory();
    }
    
    public function getEditCategory(Request $req)
    {
        $this->controller->getEditCategory($req->getRequestAttribute("code"));
    }
    
    public function getGetCategory(Request $req)
    {
        $this->controller->getGetCategory($req->getRequestAttribute("code"));
    }
    
    public function getGetAllCategories()
    {
        $this->controller->getGetAllCategories();
    }
}

