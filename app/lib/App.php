<?php

class App
{
    private $router = null;
    private $datasource = null;
    public $nodeRepository = null;

    public function __construct()
    {
        $this->setupRouter();
        $this->datasource = new Datasources\MysqlDatasource('mysql', 'root', 'password', 'node-finder');
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

    public function addRoute($expression, $function, $method = 'GET'){
        $this->router->add($expression, $function, $method);
    }

    public function serve($basePath = '/'){
        $this->router->run($basePath);
    }
}