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
$channelType = 'type1';
$currentDateTime = date("Y-m-d H:i:s");

$filterCondition = "where 1=1";



foreach ($data["attribute"] as $key=>$attribute_value)
{
    if($attribute_value["filter_type"] == "=")
    {
    $filterCondition .= ' and '.$attribute_value["attribute_name"].' = "'.$attribute_value['attribute_condition'].'"';
    }
    elseif ($attribute_value['filter_type'] == 'between')
    {
     $filterCondition .= " and ".$attribute_value['attribute_name']." between ".$attribute_value['rangeFrom']." and ".$attribute_value['rangeTo']."";
    }
    else
    {
    $filterCondition .= " and ".$attribute_value['attribute_name']." IS NOT NULL";
    }
}



// Insert/update channel data into the database
if($channelId == 0)
{
    $sql = "INSERT INTO channels (`name`, `type`, `status`, `filter_condition`, `last_time_proceed`) 
        VALUES ('$channelName', '$channelType', 1, '$filterCondition', '$currentDateTime')";
}
else
{
   $sql = "UPDATE channels SET name = '$channelName' and filter_condition='$filterCondition' WHERE id = '$channelId'";
}


if ($con->query($sql) === TRUE) {

    echo json_encode(['success' => true]);

} else {

    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
