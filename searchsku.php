<?php
include 'functions.php';
require('connect.php');

// Fetch search query from GET parameters
$searchQuery = $_GET['query'];

// Prepare and execute SQL query to search for items in the database
$sql = "SELECT sku, wholesale_aud, product_title FROM pim WHERE sku LIKE '%$searchQuery%'";

$searchResults = searchdata($sql);

    // Output search results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
?>
