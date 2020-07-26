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
        $this->connection = mysqli_connect($host, $user, $password, $database, $port);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($query){
        return mysqli_query($this->connection, $query);
    }
}