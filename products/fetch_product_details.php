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

$page = $_GET['page'] ?? 1;
$itemsPerPage = 15;
$offset = ($page - 1) * $itemsPerPage;



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
if($filter_where_value == '')
{
    $filter_where_value = 'where 1=1';
}
$column_values_row=['sku'];
$column_values = 'sku';


$check_if_columns = $con->query("select attribute_name from product_filter where product_id =".$productId);
if ($check_if_columns->num_rows > 0) {
    while ($row = $check_if_columns->fetch_assoc()) {
        if(!in_array($row['attribute_name'], $column_values_row))
        {
            $column_values .= ','.$row['attribute_name'];
            $column_values_row[] = $row['attribute_name'];
        }

    }
}

$product_detail_querys = $con->query("SELECT DISTINCT " .$column_values." FROM pim " .$filter_where_value." AND sku !='' LIMIT $offset,$itemsPerPage");

$total_rows_q= $con->query("select DISTINCT sku FROM pim " .$filter_where_value);


$total_rows=$total_rows_q->num_rows;


if ($product_detail_querys->num_rows > 0) {
    while ($row = $product_detail_querys->fetch_assoc()) {
        $product_values[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode(['products'=>$products,'product_details'=>$product_filter,'product_values'=>$product_values,'total_rows'=>$total_rows,'column_values_row'=>$column_values_row]);
?>
