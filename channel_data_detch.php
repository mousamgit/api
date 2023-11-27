<?php

require_once ('connect.php');
$channels = [];
$result = $con->query("SELECT * FROM channels");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
    }
}

// Close the database connection
$con->close();

// Sample data, replace this with your actual data retrieval logic
$channels = $channels;

// Set the response header to JSON
header('Content-Type: application/json');

// Echo the JSON-encoded data
echo json_encode($channels);
?>
