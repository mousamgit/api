<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];

        // Data found in channel_attributes, delete related records first
        $deleteAttributesQuery = "DELETE FROM channel_attributes WHERE channel_id=" . $channelId;
        $deleteFilterQuery = "DELETE FROM attribute_filter WHERE channel_id=" . $channelId;

        $deleteChannelQuery = "DELETE FROM channels WHERE id=" . $channelId;
        $con->query($deleteAttributesQuery);
        $con->query($deleteFilterQuery);
        $con->query($deleteChannelQuery);
        $con->query($deleteChannelQuery);
        if ($con->query($deleteAttributesQuery) === TRUE) {
         echo json_encode(['success' => true]);
        } else {
         echo json_encode(['success' => false, 'error' => $con->error]);
        }




// Close the database connection
$con->close();
?>
