<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');

$products = [];
$product_attribute = [];

$result = $con->query("SELECT * FROM products");


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($products);
?>
