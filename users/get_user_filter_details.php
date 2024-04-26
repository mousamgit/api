<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('../connect.php');
require_once('../login_checking.php');
require_once('../functions.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);
$user_name= $_SESSION['username'];
$user_id = getValue('users','username', $user_name,'id');
$user_name = $_SESSION['username'];

$user_filter_data = $con->query("select filter_name from user_filters where id= ".$data['filter_no']." and user_id=".$user_id);

$detail_data ='';
if ($user_filter_data->num_rows > 0) {
    while ($prevAttributeValue = $user_filter_data->fetch_assoc()) {
        $detail_data=$prevAttributeValue['filter_name'];
    }
}

echo json_encode($detail_data);

// Close the database connection
$con->close();
?>
