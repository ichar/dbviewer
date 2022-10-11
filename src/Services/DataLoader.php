<?php
// src/Services/DataLoader.php
namespace App\Services;

use App\Constants;
#use Doctrine\DBAL\DriverManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;


class DataLoader
{
    protected $logger;
    protected $doctrine;
    protected $constants;
    
    private $connection;
    private $sm;
    private $meta;
    private $views;

    public function __construct(Constants $constants, ManagerRegistry $doctrine, LoggerInterface $logger) {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
        $this->constants = $constants;
        
        $this->__initState();
    }

    protected function __initState() {
        #
        #
        #
        $this->connection = $this->doctrine->getConnection('customer');
        $this->sm = $this->connection->getSchemaManager();
        $this->meta = $this->sm->listViews();

        $this->views = array();

        foreach ($this->meta as $ob) {
            array_push($this->views, strtolower($ob->getName()));
        }
    }

    private function isView(string $view) {
        dump($this->views);
        $ob = strtolower($view);
        $is_view = (in_array($view, $this->views) === true) ? 1: 0;
        echo "$view [$ob]:{$is_view}";
        return $is_view;
    }

    private function getView(string $view) {
        return $this->meta[strtolower($view)];
    }

    private function split_sql(string $sql) {
        $columns = array();
        $items = explode(",", $sql);
        foreach($items as $key => $item) {
            if (preg_match('/[AS]? \"(.*)\"/', $item, $matches)) {
                $x = str_replace('"', '', $matches[1]);
                array_push($columns, $x);
            }
        }
        //dump($columns);
        return $columns;
    }

    private function getViewColumns(string $view) {
        $columns = array();

        $meta = $this->getView($view);
        $sql = $meta->getSql();
        //dump($sql);
        $columns = $this->split_sql($sql);

        return $columns;
    }

    private function getTableColumns($table) {

        $meta = $this->sm->listTableColumns($table);
        #print_r($meta);
        
        $columns = array();
        
        foreach($meta as $key => $value) {
            array_push($columns, str_replace('"', '', $key));
        }
        #print_r($columns);
        
        return $columns;
    }

    public function getMetadata(string $pageid) {

        $view = $this->constants->getView($pageid);
        $columns = $this->constants->getColumListForView($pageid); // , $key = null

        dump($pageid);
        dump($columns);

        $is_table = false;

        if ($columns === null) {
            if ($this->isView($view)) {
                $columns = $this->getViewColumns($view);
            } else {
                $table = str_replace('"', '', $view);
                $columns = $this->getTableColumns($table);
                $is_table = true;
            }
        }
        
        dump($view);
        dump($columns);
        dump($is_table);

        return array($view, $columns, $is_table);
    }

    public function getData(string $view, array $columns, bool $is_table)
    {
        $query = "*";
        if (!$is_table or isset($columns)) {
            $items = [];
            foreach ($columns as $column) {
                array_push($items, "\"{$column}\"");
            }
            $query = implode(',', $items);
        }
        $sql = "SELECT $query FROM $view LIMIT 100";

        dump($sql);

        $this->logger->info("getData: $view SQL: [$sql]");
        
        #$command = $this->connection->prepare($sql);
        #$res = $command->executeQuery();
        #return $res->fetchAllAssociative();
        
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
