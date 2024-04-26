<?php

include 'functions.php';
include 'login_checking.php';


$data = json_decode(file_get_contents("php://input"), true);

$formData= $data['formData'];

if (count($formData)>0) {
  $colName = $formData['colName'];
  $oldvalue = $formData['oldValue'];
  $value = $formData['editedValue'];
  $username = $_SESSION['username'];
  $table = $formData['table'];
  $pr_key = $formData['pr_key'];
  $pr_value =$formData['sku'];

  updateValue($table,$pr_key,$pr_value,$colName,$value);
  if($table == 'pim')
  {
    if($oldvalue != $value)
    {
      addtoLog($pr_key, $colName, $value, $username);
    }
  }

} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
