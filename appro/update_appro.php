<?php

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id =  $_POST['id'];
  $colName = $_POST['colName'];
  $oldvalue = $_POST['oldValue'];
  $value = $_POST['newValue'];
  $username = $_POST['username'];
  echo $id.$colName.$value;
//   addtoLog($sku, $colName, $value, $username);
  updateValue('appro','appro','app003','customer','test');

  
  // header("Location: /");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
