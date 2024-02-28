<?php
require('../connect.php');
include '../functions.php';

$username = $_POST['username'];
date_default_timezone_set("Australia/Sydney");
$current = strtotime("now");
$date = date("Y-m-d H:i:s");
$time = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $appro = mysqli_real_escape_string($con, $_POST['appro']);
    $deleteappro = "DELETE FROM appro WHERE id = '$id'";
    $approresult = mysqli_query($con,$deleteappro) or die(mysqli_error($con));
    $deleteitem = "DELETE FROM approitems WHERE `approid` = '$appro'";
    $itemresult = mysqli_query($con,$deleteitem) or die(mysqli_error($con));

    $approlog = " INSERT into approlog (appro,date,time,user,action) VALUES ('$appro','$date','$time','$username','delete')";
$logresult = mysqli_query($con,$approlog) or die(mysqli_error($con));

  
  header("Location: https://pim.samsgroup.info/appro/appro_list.php");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
