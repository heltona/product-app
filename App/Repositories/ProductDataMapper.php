<?php
namespace App\Repositories;

use App\Model\Product;
use App\Model\Category;
use Exception;
use App\Utils\SimpleDebuggingTool;

class ProductDataMapper extends AbstractDataMapper
{

    public function update(Product $prod): bool
    {
        try {
            $this->getDataSource()->beginTransaction();
            $this->getLogger()->log("Saving product: " . $prod->getId());

            $sql = "UPDATE PRODUCT 
                SET NAME = :NAME,
                DESCRIPTION = :DESC, 
                QUANTITY = :QTD, 
                PRICE = :PRICE,
                SKU = :SKU
                WHERE ID = :ID";
            $name = $prod->getName();
            $desc = $prod->getDescription();
            $qtd = $prod->getQuantity();
            $id = $prod->getId();
            $price = $prod->getPrice();
            $sku = $prod->getSku();
            
            $stmt = $this->getDataSource()->prepare($sql);
            $stmt->bindParam(":NAME", $name);
            $stmt->bindParam(":DESC", $desc);
            $stmt->bindParam(":QTD", $qtd);
            $stmt->bindParam(":ID", $id);
            $stmt->bindParam(":PRICE", $price);
            $stmt->bindParam(":SKU", $sku);

            $stmt->execute();
            
            $this->deleteProductCategoryRelationship($prod->getId());
            
            $cats = $prod->getCategory();
            
            foreach ($cats as $cat) {
                $this->persistProductCategoryRelationship($prod, $cat);
            }
            
            $this->getDataSource()->commit();
            $this->getLogger()->log("Product <" . $prod->getId() . "> updated successfully");
            
            return true;
        } catch (Exception $ex) {
            $this->getDataSource()->rollBack();
            $this->getLogger()->log("Failure at saving product: " . $prod->getId());
            $this->getLogger()->log($ex->getTraceAsString());

            return false;
        }
    }
    
    private function deleteProductCategoryRelationship($prodId)
    {
        $this->getLogger()->log("Deleting categories of product: " . $prodId);
        $sql = "DELETE FROM PRODUCT_CATEGORY WHERE PRODUCT_ID = :ID";
        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":ID", $prodId);
        $stmt->execute();
    }

    public function findAll(): array
    {
        $sql = "SELECT p.ID, p.NAME, p.SKU, p.QUANTITY,p.PRICE,p.DESCRIPTION, c.NAME AS CNAME,c.CODE
    FROM PRODUCT p
    INNER JOIN PRODUCT_CATEGORY pc ON p.ID = pc.PRODUCT_ID
    INNER JOIN CATEGORY c ON c.CODE = pc.CATEGORY_CODE";

        $stmt = $this->getDataSource()->query($sql);
        $stmt->execute();

        $arrData = $stmt->fetchAll();
        $prods = $this->prepareObjects($arrData);

        return $prods;
    }

    // relational database and oop are not really in the best terms.
    // products with two or more category represent two or more rows but shouldn't be represented
    // as two or more distinct objects in the app. Well, that is the sort of thing orm do for us!
    private function prepareObjects($arrData)
    {
        $prods = array();

        foreach ($arrData as $data) {
            $prod = $this->initializeObjectTemplate($data);
            foreach ($prods as $tProd) {
                if ($tProd->getId() == $prod->getId()) {

                    $tCategories = $tProd->getCategory();
                    $prodCategory = $prod->getCategory();

                    $tProd->setCategory(array_merge($tCategories, $prodCategory));
                    continue 2;
                }
            }
            array_push($prods, $prod);
        }

        return $prods;
    }

    public function findById(int $id): ?Product
    {
        $this->getLogger()->log("Searching for product: " . $id);
        
        $sql = "SELECT p.ID, p.NAME, p.SKU, p.QUANTITY,p.PRICE,p.DESCRIPTION, c.NAME AS CNAME,c.CODE
    FROM PRODUCT p 
    INNER JOIN PRODUCT_CATEGORY pc ON p.ID = pc.PRODUCT_ID 
    INNER JOIN CATEGORY c ON c.CODE = pc.CATEGORY_CODE
    WHERE p.ID = :ID";

        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":ID", $id);
        $stmt->execute();

        $arrData = $stmt->fetchAll();
        $prod = $this->prepareObjects($arrData);

        return count($prod) > 0 ? $prod[0] : null;
    }

    //@todo confer numbers
    private function initializeObjectTemplate(array $data): Product
    {
        $cat = new Category();
        $cat->setCode($data['CODE']);
        $cat->setName($data['CNAME']);
        
        $prod = new Product();
        $prod->setId($data['ID']);
        $prod->setName($data['NAME']);
        $prod->setDescription($data['DESCRIPTION']);
        $prod->setQuantity($data['QUANTITY']);
        $prod->setPrice($data['PRICE']);
        $prod->setSku($data['SKU']);
        $prod->setCategory(array(
            $cat
        ));

        return $prod;
    }

    public function deleteProduct(int $id): bool
    {
        try {
            
            $this->getLogger()->log("Deleting product: " . $id);
            $this->getDataSource()->beginTransaction();

            $sqlRel = "DELETE FROM PRODUCT_CATEGORY WHERE PRODUCT_ID = :ID";
            $sqlProd = "DELETE FROM PRODUCT WHERE ID = :ID";

            $this->getLogger()->log("deleting from product_category; product_id: " . $id);
            $this->deleteProductTemplate($id, $sqlRel);

            $this->getLogger()->log("deleting from product; product_id: " . $id);
            $this->deleteProductTemplate($id, $sqlProd);

            $this->getDataSource()->commit();
            $this->getLogger()->log("Product: " . $id . " deleted");
            
            return true;
        } catch (Exception $ex) {

            $this->getLogger()->log("Error at deleting: " . $ex->getMessage());
            $this->getDataSource()->rollBack();
            return false;
        }
    }

    private function deleteProductTemplate(int $id, string $sql)
    {
        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":ID", $id);
        $stmt->execute();
    }

    public function save(Product $prod): ?Product
    {
        try {

            $this->getDataSource()->beginTransaction();

            $this->getLogger()->log("Transaction for product: " . $prod->getName());
            $prod = $this->persistProduct($prod);

            $cats = $prod->getCategory();

            foreach ($cats as $cat) {
                $this->persistProductCategoryRelationship($prod, $cat);
            }

            $this->getDataSource()->commit();

            return $prod;
        } catch (Exception $ex) {

            $this->getDataSource()->rollBack();

            $this->getLogger()->log("Insertion of product {" . $prod->getName() . "} failed:" . $ex->getMessage());
            $this->getLogger()->log($ex->getTraceAsString());

            return null;
        }
    }

    private function persistProduct(Product $prod): Product
    {
        $sql = "INSERT INTO PRODUCT(NAME,DESCRIPTION,QUANTITY,SKU,PRICE) VALUES (:NAME,:DESCRIPTION,:QUANTITY,:SKU,:PRICE)";
        $this->getLogger()->log("Persisting product: " . $prod->getName());

        // why not just pass the value calling the method?
        // because bindParam expect a variable! That is the way to shut up the php logger
        $name = $prod->getName();
        $description = $prod->getDescription();
        $qtd = $prod->getQuantity();
        $price = $prod->getPrice();
        $sku = $prod->getSku();
        
        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":NAME", $name);
        $stmt->bindParam(":DESCRIPTION", $description);
        $stmt->bindParam(":QUANTITY", $qtd);
        $stmt->bindParam(":PRICE", $price);
        $stmt->bindParam(":SKU", $sku);
                
        if (! $stmt->execute()) {
            throw new Exception("Insertion of Product failed");
        }

        $prod->setId($this->getDataSource()
            ->lastInsertId());

        return $prod;
    }

    private function persistProductCategoryRelationship(Product $prod, Category $cat)
    {
        $sql = "INSERT INTO PRODUCT_CATEGORY(PRODUCT_ID,CATEGORY_CODE) VALUES (:PRODUCT, :CATEGORY)";
        $this->getLogger()->log("Persisting relationship product: " . $prod->getId() . "/" . $prod->getName() . "; Category: " . $cat->getCode() . "/" . $cat->getName());

        $prodId = $prod->getId();
        $catCode = $cat->getCode();

        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":PRODUCT", $prodId);
        $stmt->bindParam(":CATEGORY", $catCode);

        if (! $stmt->execute()) {
            throw new Exception("Insertion of Product-Category relationship failed");
        }
    }
}

