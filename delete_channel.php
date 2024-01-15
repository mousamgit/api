<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];
$attribute_check = "SELECT id FROM channel_attributes WHERE channel_id=".$channelId;

$result = $con->query($attribute_check);

if ($result) {
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Data found in channel_attributes, delete related records first
        $deleteAttributesQuery = "DELETE FROM channel_attributes WHERE channel_id=".$channelId;

        if ($con->query($deleteAttributesQuery) === TRUE) {
            //delete the channel record
            $deleteChannelQuery = "DELETE FROM channels WHERE id=".$channelId;

            if ($con->query($deleteChannelQuery) === TRUE) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $con->error]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => $con->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No data found in channel_attributes']);
    }
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
