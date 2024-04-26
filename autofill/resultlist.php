<?php
include '../functions.php';
require('../connect.php');

// Fetch search query from GET parameters
$col1 = $_GET['col1'];
$col2 = $_GET['col2'];
$db =  $_GET['db'];
$searchQuery = $_GET['query'];
$cola = $_GET['cola'];
$colb = $_GET['colb'];
$colc = $_GET['colc'];
$cold = $_GET['cold'];
$cole = $_GET['cole'];
$colf = $_GET['colf'];


$xcol='';
if ($cola) { $xcol .= ",$cola"; }
if ($colb) { $xcol .= ",$colb"; }
if ($colc) { $xcol .= ",$colc"; }
if ($cold) { $xcol .= ",$cold"; }
if ($cole) { $xcol .= ",$cole"; }
if ($colf) { $xcol .= ",$colf"; }


$val = '';

// Check if $col2 is set and not empty
if ($col2 && $col2 !== '') {
    $val = "CONCAT($col1, ' - ', $col2)";
} else {
    $val = $col1;
}


$sql = "SELECT $val AS val $xcol FROM $db WHERE $val LIKE '%$searchQuery%'";

$searchResults = searchdata($sql);
header('Content-Type: application/json');

echo json_encode($searchResults);

?>
