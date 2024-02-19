<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

//  database connection
require_once('connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$channelId = $data['channelId'];
$channelName = $data['channelName'];
$channelType = 'CSV';
$currentDateTime = date("Y-m-d H:i:s");

$filterCondition = "where 1=1";


$success = false;

// Insert/update channel data into the database
if ($channelId == 0) {
    $sql = "INSERT INTO channels (`name`, `type`, `status`, `filter_condition`, `last_time_proceed`) 
        VALUES ('$channelName', '$channelType', 1, '$filterCondition', '$currentDateTime')";
if ($con->query($sql) === TRUE) {
    $success = true;
}
} else {

    $sql_update = "UPDATE channels SET name = '$channelName' WHERE id = '$channelId'";

    if ($con->query($sql_update) === TRUE) {

        $success = true;
    }
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
