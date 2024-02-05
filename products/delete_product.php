<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$productId = $data['productId'];

// Data found in channel_attributes, delete related records first
$deleteProductFilterQuery = "DELETE FROM product_filter WHERE product_id=" . $productId;

$deleteProductQuery = "DELETE FROM products WHERE id=" . $productId;
$con->query($deleteProductFilterQuery);
if($con->query($deleteProductFilterQuery) == TRUE){
    $con->query($deleteProductQuery);
}

if ($con->query($deleteProductQuery) === TRUE ) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}




// Close the database connection
$con->close();
?>
