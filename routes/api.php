<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(__DIR__ . '/../vendor/autoload.php');


function route($method, $path, $controllerMethod) {
    if ($_SERVER['REQUEST_METHOD'] === $method && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $path) {
        $parts = explode('@', $controllerMethod);
        $controllerName = "controllers\\".$parts[0];
        $methodName = $parts[1];
        $controller = new $controllerName;
        $controller->$methodName();
        exit();
    }
}

// Define API routes
route('GET', '/api/fetch_products', 'ProductApiController@getProducts');
route('POST', '/api/create_products', 'ProductApiController@createProduct');

// Handle invalid routes
echo "404 Not Found\n";