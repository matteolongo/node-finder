<?php

/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 02:49
 */

include('autoloader.php');


$app = new App();

// Home route
$app->addRoute('/', function () use ($app) {
    echo <<<EOL
    <h1>Welcome to the tree builder!</h1>
    <p>Some useful links
        <ul>
            <li>
                <a href="/full-tree/italian">View full tree ITALIAN</a>
            </li>
            <li> 
                <a href="/full-tree/english">View full tree ENGLISH</a> 
            </li>
            <li>
                <a href="/italian/5">Get root node 5 ITALIAN</a>
            </li>
            <li>
                <a href="/english/5">Get root node 5 ENGLISH</a>
            </li>
            <li>
                <a href="/italian/7">Get sub node 7 ITALIAN</a>
            </li>
            <li>
                <a href="/english/7">Get sub node 7 ENGLISH</a>
            </li>
        </ul>
    </p>
EOL;
});

// Show full tree in selected language
$app->addRoute('/full-tree/(italian|english)', function ($language) use ($app) {
    header('Content-Type: application/json');
    $nodes = $app->nodeRepository->getAllNodes($language);
    $treeBuilder = new TreeBuilder($nodes);
    $tree = $treeBuilder->getTree();
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

        // the uncommented block is as by requirements,
        // for more detailed error messages replace MANDATORY PARAMETERS block with MANDATORY PARAMETERS DETAILED ERRORS block

        /* MANDATORY PARAMETERS begin */
        if(!$language || !$nodeId){
            $errors[] = "Missing mandatory params";
        }
        /* MANDATORY PARAMETERS end */

        /** MANDATORY PARAMETERS DETAILED ERRORS begin
         *
        if (!$language) {
            $errors[] = "Parameter language is mandatory";
        }
        if (!$nodeId) {
            $errors[] = "Parameter node_id is mandatory";
        }
        MANDATORY PARAMETERS DETAILED ERRORS end **/



        // Get optional parameters and check they are correct
        // the uncommented block is as by requirements,
        // for more detailed error messages replace PAGINATION PARAMETERRS block with PAGINATION PARAMETERRS DETAILED ERRORS block
        /** PAGINATION PARAMETERRS DETAILED ERRORS begin
         *
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
            //if (intval($pageNum) === false || $pageNum < 0) {
            if (!\Validators\PageNumValidator::validate($pageNum)) {
                $errors[] = "Invalid page number requested";
            }
        } else {
            // Set default if not provided
            $pageNum = 0;
        }

        if ($_GET['page_size']) {
            $pageSize = $_GET['page_size'];
            //if (intval($pageSize) === false || 0 > $pageSize ||$pageSize > 1000) {
            if (!\Validators\PageSizeValidator::validate($pageSize)) {
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
            $response['next_page'] = "/$language/$nodeId?page_num=$nextPageNumber&page_size=$pageSize";
            if ($pageNum > 0) {
                $prevPageNumber = $pageNum - 1;
                $response['prev_page'] = "/$language/$nodeId?page_num=$prevPageNumber&page_size=$pageSize";
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