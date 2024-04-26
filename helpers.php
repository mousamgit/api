<?php
function View($viewPath, $data = []) {

    $viewFile = __DIR__ . '/' . str_replace('.', '/', $viewPath) . '.php';

    // Check if the view file exists
    if (file_exists($viewFile)) {
        // Extract data to variables
        extract($data);

        // Include the view file
        include_once $viewFile;
    } else {
        // Handle error: View file does not exist
        echo 'Error: View file not found: ' . $viewFile;
    }
}