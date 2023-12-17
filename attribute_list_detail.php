<?php
require_once('connect.php');

// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$channelId = $queryParameters['channel_id'] ?? null;

$page = $_GET['page'] ?? 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

$heads = [];
$columns = [];

$header = $con->query("SELECT DISTINCT output_label, attribute_name FROM channel_attributes WHERE channel_id = $channelId");

if ($header->num_rows > 0) {
    while ($row = $header->fetch_assoc()) {
        array_push($heads, $row['attribute_name']);
    }
}

$heads = array_unique($heads);
$query = $con->query("SELECT " . implode($heads, ',') . " FROM pim LIMIT $offset, $itemsPerPage");

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        if ($row['sku'] != null) {
            array_push($columns, $row);
        }
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode(['heads' => $heads, 'columns' => $columns]);
?>
