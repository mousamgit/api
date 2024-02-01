<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect_mousam.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$productId = $queryParameters['id'] ?? 2;



$products = [];
$product_filter = [];
$product_values = [];

$product = $con->query("SELECT * FROM products where id=".$productId);


if ($product->num_rows > 0) {
    while ($row = $product->fetch_assoc()) {
        $products[] = $row;
    }
}

$product_filter_q = $con->query("SELECT * FROM product_filter where product_id=".$productId);


if ($product_filter_q->num_rows > 0) {
    while ($row = $product_filter_q->fetch_assoc()) {
        $product_filter[] = $row;
    }
}
$filter_where_value='';
$filter_condition = $con->query("SELECT filter_condition FROM products where id=".$productId);
if ($filter_condition->num_rows > 0) {
    while ($row = $filter_condition->fetch_assoc()) {
        $filter_where_value = $row['filter_condition'];
    }
}

$product_detail_querys = $con->query("SELECT distinct sku FROM pim " .$filter_where_value." limit 25");
if ($product_detail_querys->num_rows > 0) {
    while ($row = $product_detail_querys->fetch_assoc()) {
        $product_values[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode(['products'=>$products,'product_details'=>$product_filter,'product_values'=>$product_values]);
?>
