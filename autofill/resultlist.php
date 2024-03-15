<?php
include '../functions.php';
require('../connect.php');

// Fetch search query from GET parameters
$col1 = $_GET['col1'];
$col2 = $_GET['col2'];
$db =  $_GET['db'];
$searchQuery = $_GET['query'];
if($col2){
    $sql = "SELECT CONCAT($col1, ' - ', $col2) AS val FROM $db WHERE CONCAT($col1, ' - ', $col2) LIKE '%$searchQuery%'";
}
else{
    $sql = "SELECT $col1 AS val FROM $db WHERE $col1 LIKE '%$searchQuery%'";
}

$searchResults = searchdata($sql);
header('Content-Type: application/json');

echo json_encode($searchResults);

?>
