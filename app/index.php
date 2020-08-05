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
            <li>Full Tree
                <ul>
                    <li>
                        <a href="/full-tree/italian">View full tree ITALIAN</a>
                    </li>
                    <li> 
                        <a href="/full-tree/english">View full tree ENGLISH</a> 
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>Root node
                <ul>
                    <li>
                        <a href="/italian/5">Get root node 5 ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/5">Get root node 5 ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>Sub node
                <ul>
                    <li>
                        <a href="/italian/7">Get sub node 7 ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/7">Get sub node 7 ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>Example with pagination
                <ul>
                    <li>
                        <a href="/italian/5?page_num=2&page_size=2">Example with pagination ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/5?page_num=2&page_size=2">Example with pagination ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>page_num errors
                <ul>
                    <li>
                        <a href="/italian/5?page_num=-1&page_size=2">Error page_num=-1 ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/5?page_num=a&page_size=2">Error page_num=a ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>page_size errors
                <ul>
                    <li>
                        <a href="/italian/5?page_num=0&page_size=0">Error page_size=0 ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/5?page_num=0&page_size=1001">Error page_size=1001 ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
            </li>
            <li>invalid node id
                <ul>
                    <li>
                        <a href="/italian/0">Node id = 0 ITALIAN</a>
                    </li>
                    <li>
                        <a href="/english/789">Node id = 789 ENGLISH</a>
                    </li>
                    <li style="list-style: none">&nbsp;</li>
                </ul>
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
        if ($language === null || $nodeId === "") {
            $errors[] = "Missing mandatory params";
        }
        /* MANDATORY PARAMETERS end */

        /** MANDATORY PARAMETERS DETAILED ERRORS begin
         *
         * if (!$language) {
         * $errors[] = "Parameter language is mandatory";
         * }
         * if (!$nodeId) {
         * $errors[] = "Parameter node_id is mandatory";
         * }
         * MANDATORY PARAMETERS DETAILED ERRORS end **/

        // PAGINATION PARAMETERRS begin
        if (isset($_GET['page_num'])) {
            $pageNum = $_GET['page_num'];
            if (!\Validators\PageNumValidator::validate($pageNum)) {
                $errors[] = "Invalid page number requested";
            }
        } else {
            // Set default if not provided
            $pageNum = 0;
        }

        if (isset($_GET['page_size'])) {
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

        // No need to clean string to prevent sql injection an other nasty things
        // because using prepared statement in repository


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

            // Not the best check ever, but it should work
            if (count($nodes) >= $pageSize) {
                $response['next_page'] = "/$language/$nodeId?page_num=$nextPageNumber&page_size=$pageSize";
            }
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