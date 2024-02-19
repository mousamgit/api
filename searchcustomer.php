<?php
include 'functions.php';
require('connect.php');

// Fetch search query from GET parameters
$searchQuery = $_GET['query'];

// Prepare and execute SQL query to search for items in the database
$sql = "SELECT CONCAT(code, ' - ', company) AS customer   FROM customer WHERE CONCAT(code, ' - ', company) LIKE '%$searchQuery%'";

$searchResults = searchdata($sql);

    // Output search results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
?>
