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
$user_filter_data = $con->query("select * from user_filter_details where filter_no= ".$data['filter_no']." and user_name='".$_SESSION['username']."'");

$tooltipData =[];
if ($user_filter_data->num_rows > 0) {
    while ($prevAttributeValue = $user_filter_data->fetch_assoc()) {
       $tooltipData[]=$prevAttributeValue;
    }
}

echo json_encode($tooltipData);

// Close the database connection
$con->close();
?>
