<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Get the requested URL
$request_uri = $_SERVER['REQUEST_URI'];

// Define routes
$routes = [
    'channel' => 'channel.php',
    '' => 'channel.php',
    // Add more routes as needed
];

// Check if the requested route is defined
if (isset($routes[$request_uri])) {
    include_once $routes[$request_uri];
} else {
    // Handle 404 error
    echo '404 Not Found';
}
