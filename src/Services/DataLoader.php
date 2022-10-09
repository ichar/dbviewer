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

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger) {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }

    public function getData(string $view)
    {
        $connection = $this->doctrine->getConnection('customer');
        $sql = "SELECT * FROM $view";
        
        $this->logger->info("getData: $view SQL: [$sql]");
        
        return $connection->fetchAll($sql);

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
