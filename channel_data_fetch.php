<?php
require_once('connect.php');

$channels = [];
$channel_attribute = [];

$result = $con->query("SELECT * FROM channels");



if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($channels);
?>
