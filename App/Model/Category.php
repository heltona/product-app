<?php
namespace App\Model;

class Category
{
    private $code;
    private $name;
    /**
     * @return mixed
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $code
     */
    public function setCode(?string $code)
    {
        $this->code = $code;
    }

    /**
     * @param mixed $name
     */
    public function setName(?string $name)
    {
        $this->name = $name;
    }

}

