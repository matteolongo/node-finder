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
    try {
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

        // Check optional parameters are correct
        if (isset($_GET['page_num'])) {
            $pageNum = $_GET['page_num'];
            if (intval($pageNum) === false) {
                $errors[] = "page_num parameter should be a number \n";
            } elseif ($pageNum < 0) {
                $errors[] = "page_num parameter should be a positive number \n";
            }
        } else {
            $pageNum = 0;
        }

        if ($_GET['page_size']) {
            $pageSize = $_GET['page_size'];
            if (intval($pageSize) === false) {
                $errors[] = "page_size parameter should be a number between 1 and 1000\n";
            } elseif ($pageSize < 1) {
                $errors[] = "page_size parameter should be a number greather than 0 \n";
            } elseif ($pageSize > 1000) {
                $errors[] = "page_size parameter should be a number with max value of 1000 \n";
            }
        } else {
            $pageSize = 1000;
        }

        $keyword = $_GET['keyword'];

        header('Content-Type: application/json');
        if (count($errors)) {
            $response['errors'] = $errors;
            echo json_encode($response);
            die();
        }

        $limit = $pageSize;
        $offset = (($pageNum) * $pageSize);

        // If all parameters are ok execute query an build tree
        //var_dump($limit, $offset);
        $nodes = $app->nodeRepository->get($nodeId, $language, $limit, $offset, $keyword);
        $treeBuilder = new TreeBuilder($nodes);
        $response['nodes'] = $treeBuilder->getTree();
        $nextPageNumber = $pageNum + 1;

        // TODO check if next page is availabe, otherwise don't set the field in response
        $response['next_page'] = "/$language/$nodeId/?page_num=$nextPageNumber&page_size=$pageSize";
        if ($pageNum > 0) {
            $prevPageNumber = $pageNum - 1;
            $response['prev_page'] = "/$language/$nodeId/?page_num=$prevPageNumber&page_size=$pageSize";
        }
        echo json_encode($response);
        die();
    } catch (Exception $exception) {
        if (get_class($exception) == 'Exceptions\NodeIdException') {
            header('Content-Type: text/plain');
            header("HTTP/1.0 404 Not Found");
            echo $exception->getMessage();
            die();
        } else {
            echo $exception->getMessage();
            die();
        }
    }
});

$app->serve('/');