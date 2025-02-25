<?php
/**
 * Gracefully crafted by LongoDB
 * 26/07/2020 03:41
 */

class Router
{
    private static $routes = [];
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    // Resister route
    public static function add($expression, $function, $method = 'GET')
    {
        self::$routes[] = [
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ];
    }

    // Set pathNotFound function
    public static function pathNotFound($function)
    {
        self::$pathNotFound = $function;
    }

    // Set methodNotAllowed function
    public static function methodNotAllowed($function)
    {
        self::$methodNotAllowed = $function;
    }

    // Dispatch
    public static function run($basepath = '/')
    {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

        // If path is not defined fallback on basepath
        if (isset($parsed_url['path'])) {
            $path = $parsed_url['path'];
        } else {
            $path = '/';
        }

        // Get current request method
        $method = $_SERVER['REQUEST_METHOD'];

        // Check flags definition
        $path_match_found = false;
        $route_match_found = false;

        foreach (self::$routes as $route) {
            // If the method matches check the path
            // add basepath to matching string
            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }

            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];
            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] . '$';

            // Check path match
            if (preg_match('#' . $route['expression'] . '#', $path, $matches)) {
                $path_match_found = true;

                // Check method match
                if (strtolower($method) == strtolower($route['method'])) {
                    // Always remove first element 'cause it contains the whole string
                    array_shift($matches);

                    if ($basepath != '' && $basepath != '/') {
                        // Remove basepath
                        array_shift($matches);
                    }

                    call_user_func_array($route['function'], $matches);

                    $route_match_found = true;

                    // Do not check other routes
                    break;
                }
            }
        }

        // No matching route was found
        if (!$route_match_found) {

            // But a matching path exists
            if ($path_match_found) {
                header("HTTP/1.0 405 Method Not Allowed");
                if (self::$methodNotAllowed) {
                    call_user_func_array(self::$methodNotAllowed, array($path, $method));
                }
            } else {
                header("HTTP/1.0 404 Not Found");
                if (self::$pathNotFound) {
                    call_user_func_array(self::$pathNotFound, array($path));
                }
            }
        }
    }
}