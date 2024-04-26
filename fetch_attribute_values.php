<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php
require_once('connect.php');

$attribute_name = $_GET['attribute_name'];
$attribute_condition = strtolower($_GET['attribute_condition']);

$result = $con->query("SELECT DISTINCT ".$attribute_name." from pim where ".$attribute_name." IS NOT NULL AND lower(".$attribute_name.") like '%$attribute_condition%' ");

$attribute_values=[];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attribute_values[] = $row[$attribute_name];
    }
}


$con->close();

header('Content-Type: application/json');
echo json_encode($attribute_values);
?>
