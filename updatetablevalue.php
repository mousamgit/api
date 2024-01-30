<?php

include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sku =  $_POST['sku'];
  $colName = $_POST['colName'];
  $oldvalue = $_POST['oldValue'];
  $value = $_POST['colValue'];
  $username = $_POST['username'];
  

  updateValue('pim','sku',$sku,$colName,$value);
//   addtoLog($sku, $colName, $oldvalue, $value, $username);

  header("Location: /");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
