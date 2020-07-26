<?php

class App
{
    private $router = null;
    private $datasource = null;
    public $nodeRepository = null;

    public function __construct()
    {
        $this->router = new Router();
        $this->datasource = new Datasources\MysqlDatasource('mysql', 'root', 'password', 'node-finder');
        $this->nodeRepository = new \Repositories\NodeRepository($this->datasource);
    }

    public function addRoute($expression, $function, $method = 'GET'){
        $this->router->add($expression, $function, $method);
    }

    public function serve($basePath = '/'){
        $this->router->run($basePath);
    }
}