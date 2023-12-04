<?php
// save_channel.php

// Include your database connection logic here
require_once('connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);


// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];
$channelType = $data['type']?$data['type']:'type1';
$currentDateTime = date("Y-m-d H:i:s");

// Insert channel data into the database
$sql= "INSERT INTO channels (id,name, type,status,last_time_proceed) 
 VALUES ('$channelId','$channelName','$channelType',1,'$currentDateTime')
ON DUPLICATE KEY UPDATE name = '$channelName'";
if ($con->query($sql) === TRUE) {

    echo json_encode(['success' => true]);

} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
