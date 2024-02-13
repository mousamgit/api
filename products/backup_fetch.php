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

$product_filter_q = $con->query("SELECT * FROM product_filter where product_id=".$productId." order by index_no ASC");


if ($product_filter_q->num_rows > 0) {
while ($row = $product_filter_q->fetch_assoc()) {
$product_filter[] = $row;
}
}
$filter_fetch = $con->query("SELECT * FROM product_filter WHERE product_id=".$productId." ORDER BY index_no ASC");
$filterConditions = array();
$groupedConditions = array();
$filterConditionCombined='';
$where_value = '';
if ($filter_fetch->num_rows > 0) {
$where_value = 'WHERE 1=1 AND';
while ($prev_attribute_value = $filter_fetch->fetch_assoc()) {
switch ($prev_attribute_value["filter_type"]) {
case "=":
case "!=":
case ">":
case "<":
$condition = $prev_attribute_value['attribute_name'] . ' ' . $prev_attribute_value["filter_type"] . ' "' . $prev_attribute_value['attribute_condition'] . '"';
break;
case "includes":
$condition = $prev_attribute_value['attribute_name'] . ' LIKE "%' . $prev_attribute_value['attribute_condition'] . '%"';
break;
case "between":
$condition = $prev_attribute_value['attribute_name'] . ' BETWEEN ' . $prev_attribute_value['range_from'] . ' AND ' . $prev_attribute_value['range_to'];
break;
default:
$condition = 'LENGTH(' . $prev_attribute_value['attribute_name'] . ') > 0';
break;
}

if ($prev_attribute_value['op_value'] == 'OR') {
// If we encounter 'OR', group the conditions to the left and start a new grouping
$filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
$groupedConditions = array($condition);
} else {
// Otherwise, add the condition to the current grouping
$groupedConditions[] = $condition;
}
}

// Add the last group of conditions
if (!empty($groupedConditions)) {
$filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
}

$filterCondition = implode(' OR ', $filterConditions);
$filterConditionCombined= $where_value.' '.$filterCondition;

}
if(empty($filterConditionCombined))
{
$filterConditionCombined = 'where 1=1';
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
$product_detail_querys = $con->query("SELECT DISTINCT " .$column_values." FROM pim " .$filterConditionCombined." AND sku !='' LIMIT $offset,$itemsPerPage");

$total_rows_q= $con->query("select DISTINCT sku FROM pim " .$filterConditionCombined);

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