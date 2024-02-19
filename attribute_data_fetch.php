<?php
require_once('connect.php');

// Get the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';


// Use parse_url to extract query parameters
$urlParts = parse_url($currentUrl);


parse_str($urlParts['query'] ?? '', $queryParameters);


// Extract the channel_id parameter
$channelId = $queryParameters['channel_id'] ?? null;

$attributes=[];


$result = $con->query("SELECT * FROM channel_attributes where channel_id =".$channelId);



if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attributes[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($attributes);
?>
