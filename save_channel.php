<?php
// save_channel.php

// Include your database connection logic here
require_once('connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);


// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];

$sql = "delete from ";

if ($con->query($sql) === TRUE) {

    echo json_encode(['success' => true]);

} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
