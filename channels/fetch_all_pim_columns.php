<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');

$channels = [];
$channel_attribute = [];

$result = $con->query("SELECT COLUMN_NAME as column_name,DATA_TYPE as data_type
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
