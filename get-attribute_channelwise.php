<?php
require_once('connect_mousam.php');


// Extract channel data
$channelId = $_GET['channelId'];;



$result = $con->query("SELECT * FROM channel_attributes where channel_id =".$channelId);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attributes[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($attributes);
?>
