<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('./connect.php');
require_once('./login_checking.php');
require_once('./functions.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$user_name = $_SESSION['username'];

foreach ($data['column_values'] as $key => $value)
{
    $order_no = $key + 1;
    $column_name = $con->real_escape_string($value);
    $table_name = $data['table_name'];

    $sql = "UPDATE user_columns SET order_no = $order_no WHERE column_name = '$column_name' AND table_name='$table_name' AND status = 1";

    if ($con->query($sql) === TRUE) {
        $success = true;
    } else {
        $success = false;
        echo "Error: " . $con->error;
    }
}

$con->close();
?>
