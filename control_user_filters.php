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

$success=false;

$user_name = $_SESSION['username'];
$con->query("update product_filter set status=0 where user_name='".$_SESSION['username']."'");

$updated_product_detail_id = $con->query("select id from user_filter_details where status=1 and filter_no=".$data['filter_no']);

if ($updated_product_detail_id->num_rows > 0) {
    while ($prevAttributeValue = $updated_product_detail_id->fetch_assoc()) {
        $con->query("update product_filter set status =1 where id=".$prevAttributeValue['id']);
    }
    $success=true;
}

if ($success == true ) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
