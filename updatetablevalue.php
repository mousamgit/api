<?php

include 'functions.php';
include 'login_checking.php';


$data = json_decode(file_get_contents("php://input"), true);

$formData= $data['formData'];



if (count($formData)>0) {
  $sku =  $formData['sku'];
  $colName = $formData['colName'];
  $oldvalue = $formData['oldValue'];
  $value = $formData['editedValue'];
  $username = $_SESSION['username'];

  addtoLog($sku, $colName, $value, $username);
  updateValue('pim','sku',$sku,$colName,$value);


} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
