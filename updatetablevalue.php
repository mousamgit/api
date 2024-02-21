<?php

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sku =  $_POST['sku'];
  $colName = $_POST['colName'];
  $oldvalue = $_POST['oldValue'];
  $value = $_POST['colValue'];
  $username = $_POST['username'];
  
  addtoLog($sku, $colName, $value, $username);
  updateValue('pim','sku',$sku,$colName,$value);
  
  header("Location: /");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
