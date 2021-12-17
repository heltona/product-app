<?php
namespace App\DataMapper;

use App\Http\Request;
use App\Model\Product;
use App\Model\Category;

class DataMapper
{

    public function mapProduct(Request $req): Product
    {

        $prod = new Product();
        
        $prod->setId(intval($req->getRequestAttribute("id")));
        $prod->setName($req->getRequestAttribute("name"));
        $prod->setDescription($req->getRequestAttribute("description"));
        $prod->setQuantity(intval($req->getRequestAttribute("quantity")));
        $prod->setPrice(floatval($req->getRequestAttribute("price")));
        $prod->setSku($req->getRequestAttribute("sku"));

        $cats = array();
        
        foreach ($req->getRequestAttribute("category") as $sCat) {
            $cat = new Category();
            $cat->setCode($sCat);
            array_push($cats, $cat);
        }

        $prod->setCategory($cats);

        return $prod;
    }

    public function mapCategory(Request $req): Category
    {
        $cat = new Category();
        $cat->setCode($req->getRequestAttribute("code"));
        $cat->setName($req->getRequestAttribute("name"));

        return $cat;
    }
}

