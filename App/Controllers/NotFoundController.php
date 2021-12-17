<?php
namespace App\Controllers;

use App\Http\Response;

class NotFoundController extends AbstractController
{
    public function getNotFounPage()
    {
        $response = Response::getInstance();
        $response->setResponseCode(404);
        
        echo "<h1>Not found<h1>";
    }
}

