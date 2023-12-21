<?php

require_once('connect_mousam.php');
// Get the channel_id from the URL
$channelId = isset($_GET['channel_id']) ? $_GET['channel_id'] : null;


// Set the content type and headers to force download
//header('Content-Type: text/csv');
//header('Content-Disposition: attachment; filename="exported_data.csv"');

// Open output stream to php://output
//$output = fopen('php://output', 'w');

$heads=[];
$output_labels=[];


$header = $con->query("Select distinct output_label, attribute_name,(select name from channels where channels.id=channel_attributes.channel_id)channel_name FROM channel_attributes where channel_id =".$channelId);



$channel_name = 'test';
if ($header->num_rows > 0) {
    while ($row = $header->fetch_assoc()) {
        array_push($heads,$row['attribute_name']);
        array_push($output_labels,$row['output_label']);
        $channel_name = $row['channel_name'].'.csv';
    }
}


// Set the content type and headers to force download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename='.$channel_name);

// Open output stream to php://output
$output = fopen('php://output', 'w');

// Output CSV headers
fputcsv($output, $output_labels);

$heads = array_unique($heads);


$column = $con->query("SELECT ".implode($heads,',')." FROM pim ");

$pim =[];
if ($column->num_rows > 0) {
    while ($row = $column->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);

 ?>
