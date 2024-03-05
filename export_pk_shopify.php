<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; }
    table { border: 2px solid #000; }
    th { font-size: 14px; font-weight: 700; border: 1px solid #000; padding:20px 40px; }
    td { font-size: 12px; font-weight: 400; border: 1px solid #000; padding: 20px; }
  </style>

</head>
<body>
<div style="margin: 0 auto; width:600px; padding:20px; background-color:#F9F6F0; text-align:center;">

<?php
  $startScriptTime=microtime(TRUE);
  include_once ('connect.php');
  include_once ('mkdir.php');

  $query = 'SELECT * from pim WHERE (image1<>"" AND image1 IS NOT NULL AND brand = "Pink Kimberley Diamonds" AND retail_aud > 0 AND description<>"" AND description IS NOT NULL AND sync_shopify=1) OR (image1<>"" AND image1 IS NOT NULL AND brand = "Blush Pink Diamonds" AND retail_aud > 0 AND description<>"" AND description IS NOT NULL AND sync_shopify=1) OR (image1<>"" AND image1 IS NOT NULL AND brand LIKE "%Argyle Pink%" AND SKU NOT LIKE "%MEL%" AND SKU NOT LIKE "%STX%" AND retail_aud > 0 AND description<>"" AND description IS NOT NULL AND sync_shopify=1) OR (image1<>"" AND image1 IS NOT NULL AND brand LIKE "%Argyle Origin%" AND SKU NOT LIKE "%MEL%" AND retail_aud > 0 AND description<>"" AND description IS NOT NULL AND sync_shopify=1);';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));


  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/pk-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","handle","Command","Body HTML","Image Command","Inventory Available:Pink Kimberley Head Office","Tags Command","Tags","Title","Type","Variant Cost","Variant Image","Metafield:custom.Specifications","Variant Price","Variant Command","Vendor","Image Src","Status","Metafield:custom.centrecolour","Variant Inventory Policy","Variant Inventory Tracker","Variant Fulfillment Service");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

    //check images
    $imageURL = "";
    if($row['image1'] != "") { $imageURL .= $row['image1'].";";}
    if($row['image2'] != "") { $imageURL .= $row['image2'].";";}
    if($row['image3'] != "") { $imageURL .= $row['image3'].";";}
    if($row['image4'] != "") { $imageURL .= $row['image4'].";";}
    if($row['image5'] != "") { $imageURL .= $row['image5'].";";}
    if($row['image6'] != "") { $imageURL .= $row['image6'].";";}
    if($row['packaging_image'] != "") { $imageURL .= $row['packaging_image'];}

    //Status - draft if steve, discontinued, wholesale only
    $status = "";
    if ( preg_match("/steve/i", strtolower($row['collections_2']))) { $status = "draft"; }
    elseif ( preg_match("/discontinued/i", strtolower($row['collections_2']))) { $status = "draft"; }
    elseif ( preg_match("/wholesale_only/i", strtolower($row['collections_2']))) { $status = "draft"; }
    else { $status = "active"; }

    //Command - delete if 0 stock or marked for deletion (deletion = 1), MERGE if in stock but status is draft, MERGE if everything passes
    $command = "";
    if ($row['shopify_qty'] > 0) {
      if ($status == "active") { $command = "MERGE";  }
      if ($status == "draft") { $command = "MERGE"; }
      if ($row['deletion'] == 1) { $command= "DELETE"; }
    }else { $command = "DELETE";}


    // Stone price vs item price
    $itemprice = "";
    if( strtolower($row['type']) == "loose diamonds" ){ $itemprice = $row['stone_price_retail_aud']; } else{ $itemprice = $row['retail_aud']; }

    // Create handle
    $handle ="";
    if( substr($row['sku'],0,3) == "TDR" || substr($row['sku'],0,3) == "TPR" ){ $handle = "argyle-tender-diamond-".$row['shape']."-".$row['colour']."-".$row['clarity']."-".$row['sku']; $handle = strtolower($handle); } elseif( strtolower($row['type']) == "loose diamonds" ) { $handle = ""; $handle = "argyle-pink-diamond-".$row['shape']."-".$row['colour']."-".$row['clarity']."-".$row['sku']; $handle = strtolower($handle); } else{ $handle = ""; $handle = str_replace(" ","-",strtolower($row['product_title'])) ."-". strtolower($row['sku']); }
    $handle = str_replace(["--"," "],"-",$handle);

    // Purchase Cost Calculation
    $purchase_cost = "";
    if( strtolower($row['type']) == "loose diamonds" ) { $purchase_cost = $row['purchase_cost_aud'] * $row['carat']; $purchase_cost = round($purchase_cost,2); } else{ $purchase_cost = $row['purchase_cost_aud']; }

    // Tags
    $tags = "";
    if ( $row['tags'] != "") { $tags .= $row['tags'].", "; }
    if ( $row['brand'] != "" ) { $tags .= $row['brand'].", "; }
    if ( $row['colour'] != "" ) { $tags .= $row['colour'].", "; }
    if ( $row['colour'] != "" ) {
      if (preg_match("/pp/i", strtolower($row['colour'])) > 0){ $tags .= "PP - Purplish Pink, "; }
      elseif (preg_match("/pr/i", strtolower($row['colour'])) > 0){ $tags .= "PR - Pink Rose, "; }
      elseif (preg_match("/pc/i", strtolower($row['colour'])) > 0){ $tags .= "PC - Pink Champagne, "; }
      elseif (preg_match("/bl/i", strtolower($row['colour'])) > 0){ $tags .= "BL - Blue, "; }
      elseif (preg_match("/pred/i", strtolower($row['colour'])) > 0){ $tags .= "pRed - Pinkish Red, "; }
      else { $tags .= "P - Pink, "; }
    }
    if ( $row['shape'] != "" ) { $tags .= $row['shape'].", "; }
    if ( $row['clarity'] != "" ) { $tags .= $row['clarity'].", "; }
    if ( $row['collections'] != "" ) { $tags .= $row['collections'].", "; }
    if ( $row['type'] != "" ) { $tags .= $row['type'].", "; }
    if ( $row['metalcomposition'] != "" ) { $tags .= $row['metalcomposition'].", "; }
    if ( $row['main_metal'] != "" ) { $tags .= $row['main_metal']." Metal, "; }
    if ( $row['preorder'] == 1 ) { $tags .= "Preorder, "; }
    if ( strtolower($row['type']) == "loose diamonds") {
      if ($row['collections'] == "SKS") { $tags .= "pkcertified";}
      if ($row['collections'] == "STN") { $tags .= "argylecertified";}
    }

    $content = array (
        0 => $row['sku'],
        1 => $handle,
        2 => $command,
        3 => $row['description'],
        4 => "REPLACE",
        5 => $row['shopify_qty'],
        6 => "REPLACE",
        7 => $tags,
        8 => $row['product_title'],
        9 => $row['type'],
        10 => $purchase_cost,
        11 => $row['image1'],
        12 => $row['specifications'],
        13 => $itemprice,
        14 => "MERGE",
        15 => $row['brand'],
        16 => $imageURL,
        17 => $status,
        18 => $row['colour'],
        19 => "deny",
        20 => "shopify",
        21 => "manual",
      );

      fputcsv($fp, $content);
  }

fclose($fp);
$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<center><h2>PK Export Completed!</h2><br>";
echo "Total of ".$count." Products Exported<br><br>";
echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/pk-shopify.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br></center>';


$error = mysqli_error($con);
if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }
?>
</div>
</body>
</html>