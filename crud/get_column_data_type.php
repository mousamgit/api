<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');

$table_name = $_GET['table_name'];
$column_name = $_GET['column_name'];

$result = $con->query("SELECT DATA_TYPE as data_type_value
                       FROM information_schema.columns
                       WHERE table_schema = '".$name."' AND table_name = '".$table_name."' and column_name = '".$column_name."'");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $column = $row['data_type_value'];
    }
}
$con->close();

header('Content-Type: application/json');
echo json_encode(['dType'=>$column]); die;
?>


