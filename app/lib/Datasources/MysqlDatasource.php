<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:50
 */

namespace Datasources;

class MysqlDatasource implements IDatasource
{
    public $connection;

    public function __construct($host, $user, $password, $database, $port = '3306')
    {
        $this->connection = new \mysqli($host, $user, $password, $database, $port);
        $this->checkConnection();
    }

    protected function checkConnection()
    {
        if ($this->connection->connect_error) {
            throw new \Exception("Error connecting to database");
        }
    }

    // Make connection available for custom use
    public function getConnection()
    {
        return $this->connection;
    }

    // Execute query
    public function query($query)
    {
        return mysqli_query($this->connection, $query);
    }
}