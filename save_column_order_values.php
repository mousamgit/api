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
$success= false;
$delete_previous = $con->query("delete from user_columns where user_name ='".$user_name."'");

foreach ($data['column_values'] as $key=>$value)
{
    $status=1;
    $sql = "INSERT INTO user_columns (`user_name`, `column_name`, `order_no`, `status`) 
                VALUES ('$user_name', '$value', '$key', $status)";
    if($con->query($sql)==true)
    {
        $success=true;
    }
}


if ($success == true ) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
