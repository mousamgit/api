<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; font-size:10px; }
    table { border: 2px solid #000; }
    th { font-size: 14px; font-weight: 700; border: 1px solid #000; padding:20px 40px; }
    td { font-size: 12px; font-weight: 400; border: 1px solid #000; padding: 20px; }
  </style>
  <h1>Shopify Committed Quantity Updated!</h1>
</head>
<?php
$startScriptTime=microtime(TRUE);
function importCommittedQty($filedirect){
  include 'connect.php';

  if( file_exists($filedirect) ){
      $file = fopen($filedirect, "r");

      $header = fgetcsv($file);

      $skuColumnIndex = array_search('Line: SKU', $header);
      $quantityColumnIndex = array_search('Line: Quantity', $header);
      $refundColumnIndex = array_search('Refund: Restock Type', $header);

      

      while (($line = fgetcsv($file, 10000, ",")) !== false) {
          if (!isset($skipHeaders)) {
              $skipHeaders = true;
              continue;
          }
          
          // Extract SKU and Quantity from the current line
          $sku = isset($line[$skuColumnIndex]) ? $line[$skuColumnIndex] : null;
          $quantity = isset($line[$quantityColumnIndex]) ? $line[$quantityColumnIndex] : null;
          $refund = isset($line[$refundColumnIndex]) ? $line[$refundColumnIndex] : null;

          // Skip the row if SKU is empty
          if (empty($sku)) {
              continue;
          }
          // skip refund restock type is return
          elseif ($refund == "return") {
            continue;
          }
          
          $sql = "UPDATE pim SET committed_qty = committed_qty + '" . mysqli_real_escape_string($con, $quantity) . "' WHERE sku = '" . mysqli_real_escape_string($con, $sku) . "'";
          $result = mysqli_query($con, $sql);
          
          echo "SQL query: $sql<br>"; // Check the generated SQL query
          if (!$result) {
              echo "Query failed: " . mysqli_error($con) . "<br>";
          }
      }

      fclose($file);
  }
}

$filedirect1 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/PK_Orders.csv';


importCommittedQty($filedirect1);



 $endScriptTime=microtime(TRUE);
 $totalScriptTime=$endScriptTime-$startScriptTime;
 echo '<br>Processed in: '.number_format($totalScriptTime, 4).' seconds';
 echo "<br><br><a href='index.php'>Return Home</a>";

 ?>


</html>
