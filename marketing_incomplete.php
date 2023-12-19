<html>
<head>
  <title>SGA Marketing - Missing Descriptions or Tags</title>
  <?php include 'header.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/dancss.css">
</head>
<body>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div>

<div class="main-box">
<form class="form-horizontal" action="update_descriptions.php" method="post" name="update_descriptions" enctype="multipart/form-data">
<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE ( (description = "" or description IS NULL) AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND (brand = "Pink Kimberley Diamonds" OR brand = "Sapphire Dreams" OR brand = "Blush Pink Diamonds") AND type <> "loose diamonds" AND type <> "loose sapphires") OR ( (tags = "" or tags IS NULL) AND image1 <> "" AND shopify_qty > 0 AND retail_aud > 0 AND (brand = "Pink Kimberley Diamonds" OR brand = "Sapphire Dreams" OR brand = "Blush Pink Diamonds") AND type <> "loose diamonds" AND type <> "loose sapphires" AND brand <> "white diamond jewellery");';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/marketing/marketing-incomplete.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("sku", "description", "tags");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  
  echo "<center>";
  echo "<br><h2>Missing Descriptions or Tags</h2><br>";
  echo "<table><tr>";
  echo "<th>SKU</th><th>Image 1</th><th>Details</th><th>Description</th><th>Tags</th></tr>"; 
  while($row = mysqli_fetch_assoc($result)){


    $content = array (
      0 => $row[sku],
      1 => $row[$description],
      2 => $row[tags]
    );
    fputcsv($fp, $content);

  echo "<tr><td><input type='hidden' id='sku' name='sku[]' value='".$row[sku]."'>".$row[sku]."</td><td align=center class='image-box'><img src='".$row[image1]."' width=200px></td><td><b>Brand:</b> ".$row[brand]."<br><b>Title:</b> ".$row[product_title]."<br><br>".$row[specifications]."</td>";
  echo "<td align=center width=30%><input type='text' id='description' name='description[]' value='".$row[description]."'></input></td>";
  echo "<td align=center width=30%><input type='text' id='tags' name='tags[]' value='".$row[tags]."'></input></td>";
  echo "</tr>";
  

}

fclose($fp);

echo "</table>";

$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');

echo "<br><br>";

echo '<a href="https://pim.samsgroup.info/marketing/marketing-incomplete-csv.php" class="button button-dark">Export CSV</a><button type="submit" id="submit" name="Submit" class="button button-blue" data-loading-text="Loading...">Submit Descriptions and Tags</button><br><br>';


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

</form>
</div>
</body>
</html>
