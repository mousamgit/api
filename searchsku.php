<?php
include 'functions.php';
require('connect.php');


// Fetch search query from GET parameters
$searchQuery = $_GET['query'];

// Prepare and execute SQL query to search for items in the database
$sql = "SELECT sku, wholesale_aud, product_title FROM pim WHERE sku LIKE '%$searchQuery%'";
$result = $con->query($sql);

// Store results in an array
$searchResults = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Append each row to the search results array
        $searchResults[] = $row;
    }
}

// Close database connection
$con->close();

// Output search results as JSON
header('Content-Type: application/json');
echo json_encode($searchResults);
?>
