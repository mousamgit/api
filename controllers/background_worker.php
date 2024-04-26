<?php

require_once '/var/www/html/pim/controllers/ProductApiController.php';

$productApiController = new \controllers\ProductApiController();

$productApiController->processProduct();

file_put_contents('queued_data.json', '');
