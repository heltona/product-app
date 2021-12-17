<?php
namespace App\Controllers\Proxies;

use App\Controllers\ProductController;
use App\DataMapper\DataMapper;
use App\Http\Request;
use App\Utils\Validator;

//it could be used for security and data validation too.
//ideally there would be only one of these, and the heavy work would be done with reflection, but...
class ProductControllerProxy
{
    private $controller;
    private $dataMapper;
    
    public function __construct(ProductController $controller)
    {
        $this->controller = $controller;
        $this->dataMapper = new DataMapper();
        
    }

    // ================= POST ==========================
    public function createNewProduct(Request $req)
    {
        $validator = new Validator($req);
        
        if(!$validator->hasErrors()) {
            $prod = $this->dataMapper->mapProduct($req);
        } else {
            $prod = null; //map only the valid 
        }
        
        $this->controller->createNewProduct($prod, $validator);
    }

    public function editProduct(Request $req)
    {
        //yes no data validation. If the user send a string, all he/she will see is a sad blank screen (depending on config)
        //anyway data type is enforced through html. If he/she sent string, he/she WANTED it, and deserves the sadest blank screen
        //needless to say that a paid app would have validation on the front and back-end
        $this->controller->editProduct($this->dataMapper->mapProduct($req));
    }

    public function deleteProduct(Request $req)
    {
        $this->controller->deleteProduct($req->getRequestAttribute("id"));
    }

  
    // =================== GET ============================
    public function getCreateNewProduct()
    {
        $this->controller->getCreateNewProduct();
    }

    public function getEditProduct(Request $req)
    {        
        $this->controller->getEditProduct(intval($req->getRequestAttribute("id"))); 
    }    

    public function getGetProduct(Request $req)
    {        
        $this->controller->getGetProduct($req->getRequestAttribute("id"));
    }

    public function getGetAllProducts()
    {
        $this->controller->getGetAllProducts();
    }
}
    
    


