<?php
require_once('connect.php');


// Extract channel data
$channelId = $_GET['channelId'];;

$attribute_values=[];
$result = $con->query("SELECT id,attribute_name,data_type_value as data_type,concat(concat(attribute_name,','),data_type_value) as attribute,channel_id,filter_type,attribute_condition,range_from as rangeFrom,range_to as rangeTo FROM attribute_filter where channel_id =".$channelId);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attribute_values[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($attribute_values);
?>
