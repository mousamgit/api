<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$productName = $data['productName'];
$result = $con->query("SELECT * FROM products where name like '%".$productName."%'");

$products=[];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($products);
?>
