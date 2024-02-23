<?php
require('../connect.php');
include '../functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $appro = mysqli_real_escape_string($con, $_POST['appro']);
    $deleteappro = "DELETE FROM appro WHERE id = '$id'";
    $approresult = mysqli_query($con,$deleteappro) or die(mysqli_error($con));
    $deleteitem = "DELETE FROM approitems WHERE `approid` = '$appro'";
    $itemresult = mysqli_query($con,$deleteitem) or die(mysqli_error($con));

  
  header("Location: https://pim.samsgroup.info/appro/appro_list.php");
  exit();
} else {
  // Handle invalid requests
  http_response_code(400);
  echo 'Invalid request';
}
?>
