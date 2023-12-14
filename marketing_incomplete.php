<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (description = "" AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND brand <> "classique watches" AND brand <> "shopify cl" AND type <> "loose diamonds" AND type <> "loose sapphires") OR (tags = "" AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND brand <> "classique watches" AND brand <> "shopify cl" AND type <> "loose diamonds" AND type <> "loose sapphires" AND brand <> "white diamond jewellery");';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/marketing/marketing-incomplete.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("sku", "descriptions", "tags");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  
  echo "<center>";
  echo "<br><h2 style='font-family: Helvetica; color: #808080;'>Missing Descriptions or Tags</h2><br>";
  echo "<table border=1 cellspacing=0 cellpadding=10 style='border-color: #FFD2C3;'><tr>";
  echo "<td>SKU</td><td>Image 1</td><td>Brand</td><td>Product Name</td><td>Specifications</td></tr>"; 
  while($row = mysqli_fetch_assoc($result)){


    $content = array (
      0 => $row[sku],
      1 => $row[$description],
      2 => $row[tags]
    );
    fputcsv($fp, $content);

  echo "<tr><td>".$row[sku]."</td><td><img src='".$row[image1]."' width=150px></td><td>".$row[brand]."</td><td>".$row[product_title]."</td><td>".$row[specifications]."</td></tr>";
  

}

fclose($fp);

echo "</table>";

$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');

echo "<br><a href='https://pim.samsgroup.info/marketing/marketing-incomplete-csv.php' style='display: inline-block; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; font-family: Helvetica; cursor: pointer; border: 1px solid #3498db; color: #3498db; border-radius: 5px; transition: background-color 0.3s;'>Export CSV</a><br>";



echo "<br>Total Products Exported to CSV: ".$count."<br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br>';

$error = mysqli_error($con);
if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }
fclose($fp);

echo "</center>";

?>
