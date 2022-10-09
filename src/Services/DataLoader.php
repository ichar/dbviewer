<?php
// src/Services/DataLoader.php
namespace App\Services;

#use Doctrine\DBAL\DriverManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;


class DataLoader
{
    protected $logger;
    protected $doctrine;
    
    private $connection;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger) {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
        
        $this->connection = $this->doctrine->getConnection('customer');
    }

    public function getMetadata(string $table) {
        $ob = str_replace('"', '', $table);

        $sm = $this->connection->getSchemaManager();
        $meta = $sm->listTableColumns($ob);

        #print_r($meta);
        
        $columns = array();
        
        foreach($meta as $key => $value) {
            array_push($columns, str_replace('"', '', $key));
        }

        #print_r($columns);
        
        return $columns;
    }

    public function getData(string $view)
    {
        $sql = "SELECT * FROM $view LIMIT 10";
        
        $this->logger->info("getData: $view SQL: [$sql]");
        
        return $this->connection->fetchAllAssociative($sql);

    }
}
/*
$connectionParams = [
    'dbname' => 'mydb',
    'user' => 'user',
    'password' => 'secret',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];

$config= 'customer';
$conn = DriverManager::getConnection($connectionParams, $config);

$sql = "SELECT * FROM articles";
$stmt = $conn->query($sql); // Simple, but has several drawbacks
*/
