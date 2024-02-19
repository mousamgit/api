<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$page = $_GET['page'] ?? 1;
$itemsPerPage = 15;
$offset = ($page - 1) * $itemsPerPage;

$attributeName = $data['attributeName'];
$filterCondition = $data['filterCondition'];

if($filterCondition =='')
{
    $filterCondition = 'where 1=1';
}

$result = $con->query("SELECT sku FROM pim ".$filterCondition." and sku like '%".$attributeName."%' LIMIT $offset, $itemsPerPage");
if($attributeName == '')
{
    $result =  $con->query("SELECT sku FROM pim ".$filterCondition." LIMIT $offset, $itemsPerPage");
}

$productValues=[];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productValues[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($productValues);
?>
