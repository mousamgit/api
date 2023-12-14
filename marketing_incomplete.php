<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (description = "" AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND brand <> "classique watches" AND brand <> "shopify cl" AND type <> "loose diamonds" AND type <> "loose sapphires") OR (tags = "" AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND brand <> "classique watches" AND brand <> "shopify cl" AND type <> "loose diamonds" AND type <> "loose sapphires" AND brand <> "white diamond jewellery");';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/marketing/marketing-incomplete.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("sku", "brand", "product_title", "specifications", "descriptions", "tags");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  
  echo "<center>";
  echo "<h2>Missing Descriptions or Tags</h2><br>";
  echo "<table border=1 cellspacing=0 cellpadding=10><tr>";
  echo "<td>SKU</td><td>Image 1</td></tr>"; 
  while($row = mysqli_fetch_assoc($result)){


    $content = array (
      0 => $row[sku],
      1 => $row[brand],
      2 => $row[product_title],
      3 => $row[specifications],
      4 => $row[$description],
      5 => $row[tags]
    );
    fputcsv($fp, $content);

  echo "<tr><td>".$row[sku]."</td><td><img src='".$row[image1]."' width=150px></td></tr>";


}

fclose($fp);

echo "</table>";

$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');

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
