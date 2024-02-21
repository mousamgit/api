<?php

include '../functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id =  $_POST['id'];
  $colName = $_POST['colName'];
  $oldvalue = $_POST['oldValue'];
  $value = $_POST['newValue'];
  $username = $_POST['username'];

//   addtoLog($sku, $colName, $value, $username);
  updateValue('appro','id',$id,$colName,$value);

  
   header("Location: https://pim.samsgroup.info/appro/appro.php?id=$id");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
