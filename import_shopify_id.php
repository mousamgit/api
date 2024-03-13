<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; font-size:10px; }
    table { border: 2px solid #000; }
    th { font-size: 14px; font-weight: 700; border: 1px solid #000; padding:20px 40px; }
    td { font-size: 12px; font-weight: 400; border: 1px solid #000; padding: 20px; }
  </style>
</head>
<body>
<div style="margin: 0 auto; width:600px; padding:20px; background-color:#F9F6F0; text-align:center;">
<?php
$startScriptTime=microtime(TRUE);
 function importCsvFile($filedirect){
    include 'connect.php';

    if( file_exists($filedirect) ){
        $file = fopen($filedirect, "r");

        $header = fgetcsv($file);

        $skuColumnIndex = array_search('Variant SKU', $header);
        $productIDColumnIndex = array_search('ID', $header);
        $variantIDColumnIndex = array_search('Variant ID', $header);

        while (($line = fgetcsv($file, 10000, ",")) !== false) {
            if (!isset($skipHeaders)) {
            $skipHeaders = true;
            continue;
            }
            $sku = $line[$skuColumnIndex];
            $product_id = $line[$productIDColumnIndex];
            $variant_id = $line[$variantIDColumnIndex];

            if (strpos($sku, "LOT") !== false) { 
              continue;
            }
            $sql = "UPDATE pim SET product_id = '" . mysqli_real_escape_string($con, $product_id) . "', variant_id = '" . mysqli_real_escape_string($con, $variant_id) . "' WHERE sku = '" . mysqli_real_escape_string($con, $sku) . "';";
            $result = mysqli_query($con, $sql);
            
            echo "SQL query: $sql<br>";
            if (!$result) {
                echo "Query failed: " . mysqli_error($con) . "<br>";
            }
            }
        }
        fclose($file);
    }
    function importCsvFile2($filedirect){
      include 'connect.php';
  
      if( file_exists($filedirect) ){
          $file = fopen($filedirect, "r");
  
          $header = fgetcsv($file);
  
          $skuColumnIndex = array_search('Variant SKU', $header);
          $productIDColumnIndex = array_search('ID', $header);
          $variantIDColumnIndex = array_search('Variant ID', $header);
  
          while (($line = fgetcsv($file, 10000, ",")) !== false) {
              if (!isset($skipHeaders)) {
              $skipHeaders = true;
              continue;
              }
              $sku = $line[$skuColumnIndex];
              $product_id = $line[$productIDColumnIndex];
              $variant_id = $line[$variantIDColumnIndex];
  
              $sql = "UPDATE pim SET ws_product_id = '" . mysqli_real_escape_string($con, $product_id) . "' WHERE sku = '" . mysqli_real_escape_string($con, $sku) . "';";
              $result = mysqli_query($con, $sql);
              
              echo "SQL query: $sql<br>";
              if (!$result) {
                  echo "Query failed: " . mysqli_error($con) . "<br>";
              }
              }
          }
          fclose($file);
      }

echo "<h1>Product ID & Variant ID Updated!</h1>";
$filedirect1 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/SD_Export.csv';
$filedirect2 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/PK_Export.csv';
$filedirect3 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/CL_Export.csv';

importCsvFile($filedirect1);
importCsvFile($filedirect2);
importCsvFile($filedirect3);

echo "<br><h1>Wholesale Product ID Updated!</h1>";
$filedirect4 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/SGA_Export.csv';

importCsvFile2($filedirect4);



 $endScriptTime=microtime(TRUE);
 $totalScriptTime=$endScriptTime-$startScriptTime;
 echo '<br>Processed in: '.number_format($totalScriptTime, 4).' seconds';
 echo "<br><br><a href='index.php'>Return Home</a>";

 ?>
</div>
</body>
</html>
