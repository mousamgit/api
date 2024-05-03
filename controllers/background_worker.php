<?php

require_once '/var/www/html/pim/controllers/ProductApiController.php';
require_once '/var/www/html/pim/models/PimShopify.php';

use models\PimShopify;


$products = PimShopify::where('status', 'pending')
                      ->orderBy('id', 'ASC')
                      ->take(5) // Fetch 5 rows at a time
                      ->get();
                      
if (count($products) > 0) {
    foreach ($products as $product) {
        $productApiController = new \controllers\ProductApiController();
        $productApiController->processProduct($product);
    }
} 



