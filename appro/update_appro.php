<?php
require('../connect.php');
include '../functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id =  $_POST['id'];
  $approID =  $_POST['appro'];
  $colName = $_POST['colName'];
  $oldvalue = $_POST['oldValue'];
  $value = $_POST['newValue'];
  $username = $_POST['username'];
  $itemid =  $_POST['itemid'];

  date_default_timezone_set("Australia/Sydney");
  $current = strtotime("now");
  $date = date("Y-m-d H:i:s");
  $time = date("Y-m-d H:i:s");

//   addtoLog($sku, $colName, $value, $username);

  if($itemid == ''){
    updateValue('appro','id',$id,$colName,$value);
  }
  else{
    updateValue('approitems','id',$itemid,$colName,$value);
  }
// echo  $approID.$oldvalue.$value.$username.$date.$time;
  $approlog = " INSERT into approlog (appro,date,time,user,action,field,oldrecord,newrecord) VALUES ('$approID','$date','$time','$username','edit','$colName','$oldvalue','$value')";
$logresult = mysqli_query($con,$approlog) or die(mysqli_error($con));


  
  header("Location: https://pim.samsgroup.info/appro/appro.php?id=$id");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
