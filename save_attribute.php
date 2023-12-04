
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

    if ($attribute['id'] == 0) {
        $insertSql = "INSERT INTO channel_attributes (channel_id, attribute_name, output_label,formatting) VALUES ('$channelId', '$attributeName', '$output_label','op')";

        // 'id' key exists, perform update


    } else {
        $id = $attribute['id'];
        $updateSql = "UPDATE channel_attributes SET output_label = '$output_label' WHERE id = '$id'";
        $con->query($updateSql);
    }



}
if ($con->query($insertSql) === TRUE) {

    echo json_encode(['success' => true]);

} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();




?>