<?php

include('autoloader.php');


$app = new App();

// Home route
$app->addRoute('/', function () use ($app) {
    echo '<h1>Welcome!</h1>';
});

// Show full tree in selected language
$app->addRoute('/full-tree/(italian|english)', function ($language) use ($app) {
    header('Content-Type: application/json');
    $a = $app->nodeRepository->getAllTree($language);
    $jsonData = json_encode($a);
    echo($jsonData);
});

// Accept only numbers as parameter. Other characters will result in a 404 error
$app->addRoute('/(italian|english)/([0-9]*)', function ($language = null, $nodeId = null) use ($app) {
    $errors = [];
    $response = [
        'nodes' => [],
    ];
    if (!$language) {
        $errors[] = "Parameter language is mandatory \n";
    }
    if (!$nodeId) {
        $errors[] = "Parameter node_id is mandatory \n";
    }

    // Get optional parameters
    $keyword = $_GET['keyword'];
    $from = isset($_GET['page_num']) ? $_GET['page_num'] : 0;
    $offset = isset($_GET['page_size']) ? $_GET['page_size'] : 100;

    // Check optional parameters are correct
    if ($from) {
        if (!intval($from)) {
            $errors[] = "page_num parameter should be a number \n";
        } elseif ($from < 0) {
            $errors[] = "page_num parameter should be a positive number \n";
        }
    }
    if ($offset) {
        if (!intval($offset)) {
            $errors[] ="page_size parameter should be a number between 1 and 1000\n";
        } elseif ($offset < 1) {
            $errors[] = "page_size parameter should be a number greather than 0 \n";
        } elseif ($offset > 1000) {
            $errors[] = "page_size parameter should be a number with max value of 1000 \n";
        }
    }

    header('Content-Type: application/json');
    if(count($errors)){
        $response['errors'] = $errors;
        echo json_encode($response);
        die();
    }

    // If all parameters are ok execute query an build tree
    $nodes = $app->nodeRepository->get($nodeId, $language, $from, $offset);
    $treeBuilder = new TreeBuilder($nodes);
    $response['nodes'] = $treeBuilder->getTree();
    echo json_encode($response);
    die();
});

$app->serve('/');