<?php
  include 'login_checking.php';
  include 'functions.php';
  $sku = $_GET['sku'];
  require ('connect.php');

  $query = " SELECT * from pim WHERE SKU = '".$sku."'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  $row = mysqli_fetch_assoc($result);
?>

<html>
  <head>
    <?php include 'header.php'; ?>
    <title> SGA PIM - SKU: <?php echo $sku; ?> </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  </head>
  <?php include 'topbar.php'; ?>

  <div class="product">
    <input id="tab1" type="radio" name="tabs" checked><label for="tab1">Summary</label>
    <input id="tab2" type="radio" name="tabs" ><label for="tab2">Information</label>
    <?php include 'product-summary.php'; include 'product-information.php'; ?>
  </div>

  <?php

    foreach ($row as $colName => $val) {
      echo $colName.": ".$row[$colName]."<br>";
    }
  ?>


  <body>
  </body>
</html>
