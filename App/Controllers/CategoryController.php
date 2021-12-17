<?php
namespace App\Controllers;

use App\Model\Category;
use App\Http\Request;
use App\Repositories\CategoryDataMapper;

class CategoryController extends AbstractController
{

    private $repository;

    public function __construct()
    {
        $this->repository = new CategoryDataMapper();
    }

    // ================ POST =====================
    public function createNewCategory(Category $cat)
    {
        $cat = $this->repository->save($cat);

        if ($cat) {
            echo "<h1>Category created with success<h1>";
            
        } else {
            echo "<h1>Failure at creating category<h1>";
        }
    }

    public function editCategory(Category $cat)
    {
        $result = $this->repository->update($cat);
        
        echo "<h1>" . ($result ? "Category updated with success" : "Failure at updating category") . "<h1>" ;
    }

    public function deleteCategory(string $categoryId)
    {
        $result = $this->repository->delete($categoryId);
        echo json_encode(array("success" => $result, "id" => $categoryId));
    }

    // ================ GET ==================================
    public function getCreateNewCategory()
    {
        $this->renderView("addCategory", array());
    }

    public function getEditCategory(string $code)
    {
        $cat = $this->repository->findById($code);        
        $this->renderView("editCategory", array("cat" => $cat));
    }

    public function getGetCategory()
    {
        $this->renderView("addCategory", array());
    }

    public function getGetAllCategories()
    {
        $cats = $this->repository->findAll();
        $this->renderView("categories", array("cats" => $cats));
    }
}

