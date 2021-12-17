<?php
namespace App\Model;

use App\Model\Category;
use App\Utils\Formater;

class Product
{

    private $id;

    private $name;

    private $description;

    private $quantity;

    private $category;

    private $sku;

    private $price;

    /**
     *
     * @return mixed
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     *
     * @param mixed $price
     */
    public function setPrice(?float $price)
    {
        $this->price = $price;
    }

    /**
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     *
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     *
     * @return Category
     */
    public function getCategory(): ?array
    {
        return $this->category;
    }

    /**
     *
     * @return Sku
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param mixed $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param mixed $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     *
     * @param mixed $quantity
     */
    public function setQuantity(?int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     *
     * @param mixed $category
     */
    public function setCategory(?array $category)
    {
        $this->category = $category;
    }

    /**
     *
     * @param mixed $sku
     */
    public function setSku(?string $sku)
    {
        $this->sku = $sku;
    }

    public function printFormatedQuantity(?Formater $fmt = null): string
    {
        if ($fmt) {
            return $fmt->getFormatedString($this->quantity);
        }

        return ($this->quantity > 0) ? $this->quantity . " available" : "Out of stock";
    }
    
    public function printFormatedPrice(string $currency = "R$"): string
    {
        return sprintf($currency . "%.2f", $this->price);
    }
}

