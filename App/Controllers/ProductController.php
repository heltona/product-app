<?php
namespace App\Controllers;

use App\Model\Product;
use App\Http\Request;
use App\Repositories\ProductDataMapper;
use App\Repositories\CategoryDataMapper;
use App\Utils\SimpleDebuggingTool;
use App\Utils\CategoryDifferenceComputer;
use App\Utils\Validator;

class ProductController extends AbstractController
{

    private $repository;
    private $catRepository;

    public function __construct()
    {
        $this->repository = new ProductDataMapper();
        $this->catRepository = new CategoryDataMapper();
    }

    // ================= POST ==========================
    public function createNewProduct(Product $prod, Validator $val)
    {
        //this is how one would use if it worked
        if ($val->hasErrors()) {

            $data = $this->catRepository->findAll();
            $this->renderView("addProduct", array(
                "cats" => $data,
                "errors" => $val->getErrors()
            ));
        } else {

            $product = $this->repository->save($prod);

            if ($product) {
                echo "<h1>Product created with success</h1>";
            } else {
                echo "<h1>Failure at creating product</h1>";
            }
        }
    }

    public function editProduct(Product $prod)
    {
        $result = $this->repository->update($prod);

        //yes, I know. It is bad...
        echo "<h1>" . ($result ? "Product updated successfully" : "Failure at updating product") . "</h1>";
    }

    public function deleteProduct(int $id)
    {
        $result = $this->repository->deleteProduct($id);
        echo json_encode(array(
            "success" => $result,
            "id" => $id
        ));
    }

    // =================== GET ============================
    public function getCreateNewProduct()
    {
        $data = $this->catRepository->findAll();
        $this->renderView("addProduct", array(
            "cats" => $data
        ));
    }

    public function getEditProduct(int $id)
    {
        $data = $this->repository->findById($id);
        $cats = $this->catRepository->findAll();
        $diffCats = CategoryDifferenceComputer::computeDifference($cats, $data->getCategory());

        $this->renderView("editProduct", array(
            "prod" => $data,
            "cats" => $diffCats
        ));
    }

    // @todo not implemented
    public function getGetProduct(int $categoryId)
    {
        $this->renderView($viewName, array());
    }

    public function getGetAllProducts()
    {
        $data = $this->repository->findAll();
        $this->renderView("products", array(
            "prods" => $data
        ));
    }
}

