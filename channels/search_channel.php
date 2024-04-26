<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$channelName = $data['channelName'];
$result = $con->query("SELECT * FROM channels where name like '%".$channelName."%'");


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($channels);
?>
