<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('../connect.php');
require_once('../login_checking.php');
require_once('../functions.php');
$user_name = $_SESSION['username'];
$data = json_decode(file_get_contents("php://input"), true);


$user_name = $_SESSION['username'];
$column_name= $data['column_name'];
$order_no = maxOrderNo('user_columns');
$success=false;

if($data['selectedStatus'] == 1)
{
 $check_query=$con->query("select id from user_columns where user_name ='".$user_name."' and column_name='".$column_name."'");
 if($check_query->num_rows>0)
 {
     $con->query("update user_columns set status =1, order_no=".$order_no." where user_name='".$user_name."' and column_name='".$column_name."'");
 }
 else
 {
     $con->query("INSERT INTO user_columns (`user_name`, `column_name`, `order_no`, `status`)
                VALUES ('$user_name', '$column_name', $order_no, 1)");
 }
    $success = true;
}
else
{
 $con->query("update user_columns set status =0 where user_name= '".$_SESSION['username']."' and column_name='".$column_name."'");
 $success = true;
}
    if ($success == true) {
        $success = true;
    } else {
        $success = false;
        echo "Error: " . $con->error;
    }
if ($success == true) {
    $response = array(
        'success' => true
    );
    echo json_encode($response);
} else {

    $response = array(
        'success' => false,
        'error' => "Error: " . $con->error
    );
    echo json_encode($response);
}

$con->close();
?>
