<?php
// save_channel.php

// Include your database connection logic here
require_once('connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);


// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];
$channelType = $data['type']?$data['type']:'type1';
$currentDateTime = date("Y-m-d H:i:s");



// Insert/update channel data into the database
if($channelId == 0)
{
    $sql= "INSERT INTO channels (name, type,status,last_time_proceed,filter_condition) 
 VALUES ('$channelName','$channelType',1,'$currentDateTime','hello')";
}
else
{
   $sql = "UPDATE channels SET name = '$channelName' WHERE id = '$channelId'";
}


if ($con->query($sql) === TRUE) {

    echo json_encode(['success' => true]);

} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
