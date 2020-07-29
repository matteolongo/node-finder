<?php

/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 02:49
 */

include('autoloader.php');


$app = new App();

// Home route
$app->addRoute('/', function () use ($app) {
    echo '<h1>Welcome!</h1>';
});

// Show full tree in selected language
$app->addRoute('/full-tree/(italian|english)', function ($language) use ($app) {
    header('Content-Type: application/json');
    $tree = $app->nodeRepository->getAllTree($language);
    echo json_encode($tree);
    die();
});

// Accept only numbers as parameter. Other characters will result in a 404 error
$app->addRoute('/(italian|english)/([0-9]*)', function ($language = null, $nodeId = null) use ($app) {
    try {
        $errors = [];
        $response = [
            'nodes' => [],
        ];

        // more detailed error messages, could replace block: MANDATORY PARAMETERS
        /*
        if (!$language) {
            $errors[] = "Parameter language is mandatory";
        }
        if (!$nodeId) {
            $errors[] = "Parameter node_id is mandatory";
        }
        */

        /* MANDATORY PARAMETERS begin */
        if(!$language || !$nodeId){
            $errors[] = "Missing mandatory params";
        }
        /* MANDATORY PARAMETERS end */

        // Get optional parameters and check they are correct
        // more detailed error messages, could replace block: PAGINATION PARAMETERS
        /*
        if (isset($_GET['page_num'])) {
            $pageNum = $_GET['page_num'];
            if (intval($pageNum) === false) {
                $errors[] = "page_num parameter should be a number \n";
            } elseif ($pageNum < 0) {
                $errors[] = "page_num parameter should be a positive number \n";
            }
        } else {
            // Set default if not provided
            $pageNum = 0;
        }

        if ($_GET['page_size']) {
            $pageSize = $_GET['page_size'];
            if (intval($pageSize) === false) {
                $errors[] = "page_size parameter should be a number between 1 and 1000\n";
            } elseif ($pageSize < 1) {
                $errors[] = "page_size parameter should be a number greater than 0 \n";
            } elseif ($pageSize > 1000) {
                $errors[] = "page_size parameter should be a number with max value of 1000 \n";
            }
        } else {
            // Set default if not provided
            $pageSize = 1000;
        }
        */

        // PAGINATION PARAMETERRS begin
        if (isset($_GET['page_num'])) {
            $pageNum = $_GET['page_num'];
            if (intval($pageNum) === false || $pageNum < 0) {
                $errors[] = "Invalid page number requested";
            }
        } else {
            // Set default if not provided
            $pageNum = 0;
        }

        if ($_GET['page_size']) {
            $pageSize = $_GET['page_size'];
            if (intval($pageSize) === false || 0 > $pageSize ||$pageSize > 1000) {
                $errors[] = "Invalid page size requested";
            }
        } else {
            // Set default if not provided
            $pageSize = 100;
        }
        // PAGINATION PARAMETERRS end

        $keyword = $_GET['search_keyword'];

        // If any error stop execution and send them back to the user
        // otherwise fire the query and get results
        if (!count($errors)) {
            $limit = $pageSize;
            $offset = (($pageNum) * $pageSize);

            // If all parameters are ok execute query an build tree
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
        } else {
            $response['errors'] = $errors;
        }

    } catch (Exception $exception) {

        // If node id does not exists set response status to 404
        if (get_class($exception) == 'Exceptions\NodeIdException') {
            header("HTTP/1.0 404 Not Found");
        }

        // add exception message to errors
        $response['errors'][] = $exception->getMessage();

    } finally {
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
});

$app->serve('/');