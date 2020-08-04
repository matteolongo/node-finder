<?php

/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:33
 */

class App
{
    private $router = null;
    private $datasource = null;
    public $nodeRepository = null;

    public function __construct()
    {
        $mysqlHost = getenv('MYSQL_HOST') ? getenv('MYSQL_HOST') : 'mysql';
        $mysqlUser = getenv('MYSQL_USER') ? getenv('MYSQL_USER') : 'admin';
        $mysqlPassword = getenv('MYSQL_PASSWORD') ? getenv('MYSQL_PASSWORD') : 'password';
        $mysqlDbName = getenv('MYSQL_DATABASE') ? getenv('MYSQL_DATABASE') : 'node-finder';

        $this->setupRouter();

        // Init datasource with datasource connection parameters
        $this->datasource = new Datasources\MysqlDatasource($mysqlHost, $mysqlUser, $mysqlPassword, $mysqlDbName);

        // Init repository with proper datasource
        $this->nodeRepository = new \Repositories\NodeRepository($this->datasource);
    }

    protected function setupRouter(){
        // Initialize app router
        $this->router = new Router();

        // Set default router function for 404
        $this->router->pathNotFound(function(){
            header("HTTP/1.0 404 Not Found");
            echo 'Content not found';
        });

        // Set default router function for 405
        $this->router->methodNotAllowed(function(){
            header("HTTP/1.0 405 Method Not Allowed");
            echo 'Method not allowed';
        });
    }

    /**
     * The regex route representation
     * The function to be executed
     * The HTTP method, defaults to GET
     *
     * @param $expression
     * @param $function
     * @param string|null $method
     */
    public function addRoute($expression, $function, $method = 'GET'){
        $this->router->add($expression, $function, $method);
    }

    public function serve($basePath = '/'){
        $this->router->run($basePath);
    }
}