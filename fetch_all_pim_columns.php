<?php
require_once('connect_mousam.php');

$channels = [];
$channel_attribute = [];

$result = $con->query("SELECT column_name
                       FROM information_schema.columns
                       WHERE table_schema = '".$name."' AND table_name = 'pim'");


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row;
    }
}


$con->close();

header('Content-Type: application/json');
echo json_encode($columns);
?>
