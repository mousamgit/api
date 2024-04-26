<?php

include '../functions.php';
require('../connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id =  $_POST['id'];
    $comment = $_POST['comment'];
    $username = $_POST['username'];

 

    date_default_timezone_set("Australia/Sydney");
    $current = strtotime("now");
    $date = date("Y-m-d H:i:s");
    $time = date("Y-m-d H:i:s");
    // echo $date.$time.$id.$username.$comment;

$logsql = " INSERT into approcomment (date,time,approid,user,comment) VALUES ('$date','$time','$id','$username','$comment')";
$logresult = mysqli_query($con,$logsql) or die(mysqli_error($con)); 

  
   header("Location: https://pim.samsgroup.info/appro/appro.php?id=$id");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
