<?php
require_once('connect_mousam.php');

// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$channelId = $queryParameters['channel_id'] ?? 88;


$heads = [];
$output_labels = [];
$columns = [];

// Fetching distinct attributes for the header
$header = $con->query("SELECT DISTINCT output_label, attribute_name,(select name from channels where channels.id = channel_attributes.channel_id)channel_name FROM channel_attributes WHERE channel_id = $channelId");

if ($header->num_rows > 0) {
    while ($row = $header->fetch_assoc()) {
        array_push($heads, $row['attribute_name']);
        array_push($output_labels, $row['output_label']);
    }
}

$heads = array_unique($heads);

// Handling filter conditions from query parameters
$whereConditions = [];

if($_GET['filter_column_1_column'] != NULL)
{
    foreach ($_GET as $key => $value) {
        if (strpos($key, 'filter_column') === 0) {
            $index = explode('_', $key)[2];
            $column = $con->real_escape_string($_GET["filter_column_${index}_column"]);
            $type = $con->real_escape_string($_GET["filter_column_${index}_type"]);
            $filterValue = $con->real_escape_string($_GET["filter_column_${index}_value"]);
            $filterValueTo = $con->real_escape_string($_GET["filter_column_${index}_valueTo"]);

            switch ($type) {
                case '=':
                    $whereConditions[] = "$column = '$filterValue'";
                    break;

                case 'between':
                    $whereConditions[] = "$column BETWEEN '$filterValue' AND '$filterValueTo'";
                    break;

                case 'includes':
                    $whereConditions[] = "$column like '%$filterValue%'";
                    break;

                default:
                    // Handle unknown filter types
                    break;
            }
        }
    }

// Constructing the full WHERE condition
    $whereCondition = !empty($whereConditions) ? ' WHERE ' . implode(' AND ', $whereConditions) : '';


    $query = $con->query("SELECT " . implode(',', $heads) . " FROM pim $whereCondition");
}
else
{
    $query = $con->query("SELECT " . implode(',', $heads) . " FROM pim ");
}



// Set the content type and headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="exported_data.csv"');

// Open output stream to php://output
$output = fopen('php://output', 'w');

// Output CSV headers
fputcsv($output, $output_labels);

// Output CSV data
while ($row = $query->fetch_assoc()) {
    if ($row['sku'] !== null) {
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);

$con->close();
?>
