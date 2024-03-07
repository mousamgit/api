<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; font-size:10px; }
    table { border: 2px solid #000; }
    th { font-size: 14px; font-weight: 700; border: 1px solid #000; padding:20px 40px; }
    td { font-size: 12px; font-weight: 400; border: 1px solid #000; padding: 20px; }
  </style>
  <h1>Product ID & Variant ID Import Updated!</h1>
</head>
<?php
$startScriptTime=microtime(TRUE);
include_once ('connect.php');  
 function importCsvFile($filedirect){
    global $con;
    if( file_exists($filedirect) ){
        $file = fopen($filedirect, "r");

        $header = fgetcsv($file);

        $skuColumnIndex = array_search('Variant SKU', $header);
        $productIDColumnIndex = array_search('ID', $header);
        $variantIDColumnIndex = array_search('Variant ID', $header);
        
        //echo "<table><tr><th>sku</th><th>product_id</th><th>variant_id</th></tr>";

        while (($line = fgetcsv($file, 10000, ",")) !== false) {
            if (!isset($skipHeaders)) {
            $skipHeaders = true;
            continue;
            }
            $sku = $line[$skuColumnIndex];
            $product_id = $line[$productIDColumnIndex];
            $variant_id = $line[$variantIDColumnIndex];

            //echo "<tr><td>".$sku."</td><td>".$product_id."</td><td>".$variant_id."</td></tr>";

            $sql = "UPDATE pim SET product_id = '" . mysqli_real_escape_string($con, $product_id) . "', variant_id = '" . mysqli_real_escape_string($con, $variant_id) . "' WHERE sku = '" . mysqli_real_escape_string($con, $sku) . "'";
            $result = mysqli_query($con, $sql);
            
            echo "SQL query: $sql<br>"; // Check the generated SQL query
            $result = mysqli_query($con, $sql);
            if (!$result) {
                echo "Query failed: " . mysqli_error($con) . "<br>";
            }
            }

        }
        //echo "</table>";
        fclose($file);
    }

$filedirect1 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/SD_Export.csv';
$filedirect2 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/PK_Export.csv';
$filedirect3 = dirname($_SERVER['DOCUMENT_ROOT']).'/pim/matrixify-export/CL_Export.csv';

importCsvFile($filedirect1);
importCsvFile($filedirect2);
importCsvFile($filedirect3);


 $endScriptTime=microtime(TRUE);
 $totalScriptTime=$endScriptTime-$startScriptTime;
 echo '<br>Processed in: '.number_format($totalScriptTime, 4).' seconds';
 echo "<br><br><a href='index.php'>Return Home</a>";

 ?>


</html>
