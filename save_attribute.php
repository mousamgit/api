
<?php
// save_channel.php

// Include your database connection logic here
require_once('connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $attribute) {
    $attributeName = $attribute['attribute_name'];
    $output_label = $attribute['output_label'];
    $channelId = $attribute['channel_id'];
    $attribute_type = $attribute['attribute_type'];
    $filter_logic = $attribute['filter_logic'];

    if ($attribute['id'] == 0) {
        $sql = "INSERT INTO channel_attributes (channel_id, attribute_name, output_label, formatting, attribute_type, filter_logic) VALUES ('$channelId', '$attributeName', '$output_label', 'op','$attribute_type','$filter_logic')";
        $con->query($sql);
    } else {
        $id = $attribute['id'];
        $sql = "UPDATE channel_attributes SET output_label = '$output_label', attribute_name = '$attributeName',  attribute_type = '$attribute_type', filter_logic='$filter_logic' WHERE id = '$id'";
        $con->query($sql);
    }
}


// Check if any of the queries failed
$error = $con->error;
if (empty($error)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $error]);
}

// Close the database connection
$con->close();




?>