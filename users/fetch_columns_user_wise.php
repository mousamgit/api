
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../connect.php');
require_once('../login_checking.php');

$channels = [];
$channel_attribute = [];


$selected_columns = $con->query("select column_name from user_columns where user_name ='".$_SESSION['username']."' and status =1");
$column_names=[];
if ($selected_columns->num_rows > 0) {
    while ($row = $selected_columns->fetch_assoc()) {
        $column_names[]=$row['column_name'];
    }
}

$result = $con->query("SELECT COLUMN_NAME as column_name,DATA_TYPE as data_type,false as selected
                       FROM information_schema.columns
                       WHERE table_schema = '".$name."' AND table_name = 'pim'");

$columns=[];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $selected = in_array($row['column_name'], $column_names) ? true : false;
        $row['selected'] = $selected;
        $columns[] = $row;
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($columns);
?>