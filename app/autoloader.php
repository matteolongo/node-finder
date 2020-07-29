<?php

/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:10
 */

spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/' . $className . '.php';
});