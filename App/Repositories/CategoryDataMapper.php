<?php
namespace App\Repositories;

use App\Model\Category;
use Exception;

class CategoryDataMapper extends AbstractDataMapper
{

    public function update(Category $cat): bool
    {
        try {
            
            $this->getLogger()->log("Updating category: " . $cat->getCode());
            
            $sql = "UPDATE CATEGORY SET NAME = :NAME WHERE CODE = :CODE";
            $name = $cat->getName();
            $code = $cat->getCode(); //to shut up logger's complaint

            $stmt = $this->getDataSource()->prepare($sql);
            $stmt->bindParam(":NAME", $name);
            $stmt->bindParam(":CODE", $code);
            
            return $stmt->execute();
            
        } catch (Exception $ex) {
            $this->getLogger()->log("Failure at updating category: " . $cat->getCode());
            $this->getLogger()->log($ex->getTraceAsString());
            
        }
    }

    public function delete(string $code): bool
    {
        try {

            $this->getLogger()->log("starting transaction to delete category: " . $code);
            $this->getDataSource()->beginTransaction();

            $sqlCat = "DELETE FROM CATEGORY WHERE CODE = :CODE";
            $sqlRel = "DELETE FROM PRODUCT_CATEGORY WHERE CATEGORY_CODE = :CODE";

            $this->deleteCategoryTemplate($sqlRel, $code);
            $this->getLogger()->log("category relationship deleted for: " . $code);

            $this->deleteCategoryTemplate($sqlCat, $code);

            $this->getDataSource()->commit();
            $this->getLogger()->log("Category <" . $code . "> deleted successfully");

            return true;
        } catch (Exception $ex) {

            $this->getDataSource()->errorCode();
            $this->getLogger()->log("failure at deleting category: " . $code);
            $this->getLogger()->log($ex->getTraceAsString());

            return false;
        }
    }

    private function deleteCategoryTemplate(string $sql, string $code)
    {
        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":CODE", $code);
        $stmt->execute();
    }

    public function findById(string $code): ?Category
    {
        $this->getLogger()->log("finding category: " . $code);

        $sql = "SELECT * FROM CATEGORY WHERE CODE = :CODE";

        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->bindParam(":CODE", $code);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (count($result) > 0) {
            $cat = $this->initializeObjectTemplate($result[0]);
            $this->getLogger()->log("Category < " . $code . "> found.");

            return $cat;
        }

        return null;
    }

    public function findAll(): array
    {
        $this->getLogger()->log("finding all categories");

        $sql = "SELECT * FROM CATEGORY";

        $stmt = $this->getDataSource()->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        $cats = array();

        if (count($result) > 0) {
            foreach ($result as $data) {
                $cat = $this->initializeObjectTemplate($data);
                array_push($cats, $cat);
            }
        }

        return $cats;
    }

    private function initializeObjectTemplate(array $data): Category
    {
        $cat = new Category();
        $cat->setCode($data[0]);
        $cat->setName($data[1]);

        return $cat;
    }

    public function save(Category $cat): ?Category
    {
        try {

            $this->getLogger()->log("Saving category: " . $cat->getCode());

            $sql = "INSERT INTO CATEGORY(CODE,NAME) VALUES (:CODE, :NAME)";

            $code = $cat->getCode();
            $name = $cat->getName();

            $stmt = $this->getDataSource()->prepare($sql);
            $stmt->bindParam("CODE", $code);
            $stmt->bindParam("NAME", $name);
            $stmt->execute();

            $this->getLogger()->log("Category: " . $cat->getCode() . " saved");

            return $cat;
        } catch (Exception $ex) {

            $this->getLogger()->log("Insertion of Category" . $cat->getCode() . "failed");
            $this->getLogger()->log($ex->getTraceAsString());

            return null;
        }
    }
}

