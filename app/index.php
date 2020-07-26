<?php

include('autoloader.php');


$app = new App();

// Add base Router (startpage)
$app->addRoute('/', function () use ($app) {
    echo '<h1>Welcome!</h1>';
});

$app->addRoute('/test', function () use ($app) {
    header('Content-Type: application/json');
    $a = $app->nodeRepository->getAllTree('italian');
    $jsonData = json_encode($a);
    echo($jsonData);
});

$app->addRoute('/tree', function () use ($app) {
    header('Content-Type: application/json');
    $nodes = $app->nodeRepository->get(5,'italian');
    $treeBuilder = new TreeBuilder($nodes);

    echo( json_encode($treeBuilder->getTree()));
});

// Simple test Router that simulates static html file
$app->addRoute('/test.html', function () {
    echo 'Hello from test.html';
});

// Post Router example
$app->addRoute('/contact-form', function () {
    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
}, 'get');

// Post Router example
$app->addRoute('/contact-form', function () {
    echo 'Hey! The form has been sent:<br/>';
    print_r($_POST);
}, 'post');

// Accept only numbers as parameter. Other characters will result in a 404 error
$app->addRoute('/foo/([0-9]*)/bar', function ($var1) {
    echo $var1 . ' is a great number!';
});

$app->serve('/');