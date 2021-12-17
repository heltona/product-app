<?php
namespace App\Controllers;

use App\Repositories\ProductDataMapper;
use App\Utils\SimpleDebuggingTool;

class HomeController extends AbstractController
{
    private $repository;
    
    public function __construct()
    {
        $this->repository = new ProductDataMapper();
    }
    public function getDashboard()
    {
        $prods = $this->repository->findAll();
        
        $this->renderView("dashboard", array("prods" => $prods));
    }
}

