<?php


namespace FOP\DeveloperTools\Employee;


use Doctrine\DBAL\Connection;

class Repository
{
    private $connection;
    private $databasePrefix;

    public function __construct(Connection $connection, string $databasePrefix)
    {
        $this->connection = $connection;
        $this->databasePrefix = $databasePrefix;
    }

    public function getAll()
    {
        return $this->connection->executeQuery('SELECT * from '.$this->databasePrefix.'employee')
            ->fetchAll()
        ;
    }
}
