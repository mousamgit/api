<?php
require_once('connect_mousam.php');

// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$channelId = $queryParameters['channel_id'] ?? 2;

$page = $_GET['page'] ?? 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

$heads = [];
$output_labels=[];
$columns = [];

// Fetching distinct attributes for the header
$header = $con->query("SELECT DISTINCT output_label, attribute_name,(select name from channels where channels.id = channel_attributes.channel_id)channel_name FROM channel_attributes WHERE channel_id = $channelId");

$channel_name='';
if ($header->num_rows > 0) {
    while ($row = $header->fetch_assoc()) {
        array_push($heads, $row['attribute_name']);
        array_push($output_labels, $row['output_label']);
        $channel_name = $row['channel_name'];
    }
}

$heads = array_unique($heads);

implode(',',$heads);

// Getting the raw POST data
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

// Check if the data is present
if (!empty($data)) {
    $whereConditions = [];

    // Loop through each filter condition
    foreach ($data as $filter) {
        $column = $con->real_escape_string($filter['column']);
        $type = $con->real_escape_string($filter['type']);

        // Handle different filter types
        switch ($type) {
            case '=':
                $value = $con->real_escape_string($filter['value']);
                $whereConditions[] = "$column = '$value'";
                break;

            case 'between':
                $value = (int)$filter['value'];
                $valueTo = (int)$filter['valueTo'];
                $whereConditions[] = "$column BETWEEN $value AND $valueTo";
                break;

            // Add more cases for other filter types if needed

            default:
                // Handle unknown filter types
                break;
        }
    }

    // Constructing the full WHERE condition
    $whereCondition = !empty($whereConditions) ? ' WHERE ' . implode(' AND ', $whereConditions) : '';

    // Your existing code for pagination
    $query = $con->query("SELECT ".implode(',',$heads)." FROM pim $whereCondition LIMIT $offset, $itemsPerPage");

    // Rest of your code
}
else
{

    $query = $con->query("SELECT ".implode(',',$heads)." FROM pim LIMIT $offset, $itemsPerPage");
}
// Constructing the full SQL query

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        if ($row['sku'] != null) {
            array_push($columns, $row);
        }
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode(['heads'=>$heads,'output_labels' => $output_labels, 'columns' => $columns,'channel_name'=>$channel_name]);
?>
