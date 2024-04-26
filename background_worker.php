<?php
// Load queued data and process it
$queuedData = file_get_contents('queued_data.json');
$lines = explode(PHP_EOL, $queuedData);

foreach ($lines as $line) {
    if (!empty($line)) {
        $data = json_decode($line, true);
        dd($data);
        // Process the data here
        // Call your `createProduct()` function or the logic inside it
        // You can call the function directly here or include the file containing the function
    }
}
// Include the ProductApiController class
require_once '/var/www/html/pim/controllers/ProductApiController.php';

// Instantiate the ProductApiController
$productApiController = new \controllers\ProductApiController();


// Call the createProduct method
$productApiController->processProduct();

// Clear the queue file after processing
file_put_contents('queued_data.json', '');
