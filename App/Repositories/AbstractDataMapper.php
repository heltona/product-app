<?php
namespace App\Repositories;

use App\DataSource;
use App\Utils\Logger;
use PDO;

abstract class AbstractDataMapper
{
    private $dataSource;    
    private $logger;
    
    public function __construct()
    {
        $this->dataSource = DataSource::getInstance();
        $this->logger = Logger::getInstance();
    }
    
    /**
     * @return PDO
     */
    protected function getDataSource(): PDO
    {
        return $this->dataSource;
    }

    /**
     * @return \App\Utils\Logger
     */
    protected function getLogger(): Logger
    {
        return $this->logger;
    }

    
    

   
}

