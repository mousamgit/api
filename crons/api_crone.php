<?php

// Include the ProductApiController class
require_once '/var/www/html/pim/controllers/ProductApiController.php';

// Instantiate the ProductApiController
$productApiController = new \controllers\ProductApiController();


// Call the createProduct method
dd($productApiController->createProduct());


?>
