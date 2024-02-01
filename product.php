<?php
  include 'login_checking.php';
  include 'functions.php';
  $sku = $_GET['sku'];
  require ('connect.php');

  $query = " SELECT * from pim WHERE SKU = '".$sku."'";
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
  //$row = mysqli_fetch_assoc($result);
?>

<html>
  <head>
    <?php include 'header.php'; ?>
    <title> SGA PIM - SKU: <?php echo $sku; ?> </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  </head>
  <body>
  <?php include 'topbar.php'; ?>

  <?php 
    if ($sku == "")
    {
      echo "No SKU supplied. Please select another product.";
    }
    else{
      $brand = getValue('pim', 'sku', $sku, 'brand'); 
 
  ?>

  <div style="padding:20px; text-align:center;">
    <h2>Product information for <?php echo $sku; ?></h2>
  </div>
  <div class="product">
    <input id="tab1" type="radio" name="tabs" checked><label for="tab1">Quick Summary</label>
    <input id="tab2" type="radio" name="tabs" ><label for="tab2">Information</label>
    <input id="tab3" type="radio" name="tabs" ><label for="tab3">Media</label>
    <input id="tab4" type="radio" name="tabs" ><label for="tab4">Log</label>
    <?php 
            $logquery = " SELECT * from pimlog WHERE SKU = '".$sku."'";
            $logresult = mysqli_query($con, $logquery) or die(mysqli_error($con));
    ?>
    <?php
      while($row = mysqli_fetch_assoc($result)){
        include 'product-summary.php'; include 'product-information.php'; include 'product-media.php'; include 'product-log.php';
      }
    ?>
  </div>

  


  <?php } ?>

  

  </body>
</html>
