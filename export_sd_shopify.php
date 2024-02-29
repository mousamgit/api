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

  $query = 'SELECT * FROM pim WHERE ((image1<> "" AND image1 IS NOT NULL AND retail_aud > 0 AND description<> "" AND description IS NOT NULL AND sync_shopify=1 AND brand = "Sapphire Dreams" AND collections != "SDL" AND collections != "SDM") OR (image1<> "" AND image1 IS NOT NULL AND retail_aud > 0 AND sync_shopify=1 AND type = "loose sapphires" AND collections != "SDL" AND collections != "SDM"));';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/sd-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","Handle","Command","Body HTML","Image Command","Inventory Available:Sapphire Dreams Head Office","Tags Command","Tags","Title","Type","Variant Cost","Variant Image","Variant Price","Variant Command","Vendor","Image Src","Status","Variant Inventory Policy","Metafield:custom.metal_colour","Metafield:custom.metal_info","Metafield:custom.stone_info","Metafield:custom.stone_shape","Metafield:title_tag","Metafield:custom.certification","Metafield:custom.specifications","Metafield:custom.stone_colour","Metafield:custom.stone_specifications","Variant Inventory Tracker","Variant Fulfillment Service");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

  $title_mod = trim(str_replace(["earrings","pendant","necklace","bracelet"," ring","various"],"",strtolower($row[product_title])));
  $type_mod = str_replace(["rings","pendants","necklaces","bracelets","earring"],["ring","pendant","necklace","bracelet","earrings"],strtolower($row[type]));

        // Metafield: Stone Specifications
        if ( strpos($row[specifications], "Certificate") !== false) { $stone_specifications = "ID No.: ".str_replace("SDS","",$row[sku])."<br>Colour: ". strtoupper($row[colour])."<br>Shape: ".strtoupper($row[shape])."<br>Weight: ".$row[carat]."ct<br>Size: ".$row[measurement]."<br>Origin: AUSTRALIA";}
        else { $stone_specifications = "";}

        // Metafield: Certification
        if ( strpos($row[specifications], "Certificate") !== false)  { $certification = "YES"; }
        else { $certification = "NO";}

        //Descriptions, if loose sapphire generate description else import from field description
        if (strtolower($row[type]) == "loose sapphires")
          if( strtolower($row[treatment]) == "unheated") { $description = "An unheated Australian " .  $row[shape] . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . "."; }
          else {$description = "An Australian " .  $row[shape] . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . ".";  }
        else { $description = $row[description];}

        //check images
        $imageURL = "";
        if($row[image1] != "") { $imageURL .= $row[image1].";";}
        if($row[image2] != "") { $imageURL .= $row[image2].";";}
        if($row[image3] != "") { $imageURL .= $row[image3].";";}
        if($row[image4] != "") { $imageURL .= $row[image4].";";}
        if($row[image5] != "") { $imageURL .= $row[image5].";";}
        if($row[image6] != "") { $imageURL .= $row[image6].";";}
        if($row[packaging_image] != "") { $imageURL .= $row[packaging_image];}

        //Status - draft if steve, discontinued, wholesale only
        $status = "";
        if ( $row[retail_exclusive] <> 1) { $status = "draft";}
        elseif ( preg_match("/steve/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/discontinued/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/wholesale_only/i", strtolower($row[collections_2]))) { $status = "draft"; }
        else { $status = "active"; }

        //Command - delete if 0 stock, MERGE if in stock but status is draft, MERGE if everything passes
        $command = "";
        if ( $row[retail_exclusive] <> 1) { $command = "DELETE";}
        else {
          if ($row[shopify_qty] > 0) {
            if ($status == "active") { $command = "MERGE";  }
            if ($status == "draft") { $command = "MERGE"; }
            if ($row[deletion] == 1) { $command = "DELETE"; }
          }else { $command = "DELETE";} 
        }


        // Stone price vs item price
        $itemprice = "";
        if( strtolower($row[type]) == "loose sapphires" ){ $itemprice = $row[stone_price_retail_aud]; } else{ $itemprice = $row[retail_aud]; }

        // Create handle
        $handle = "";
        if( strtolower($row[type]) == "loose sapphires" )
          if ( strtolower($row[treatment]) == "unheated") { $handle = $row[shape]."-".$row[colour].$row[edl]."-australian-sapphire-".$row[sku]; }
          else { $handle = $row[shape]."-".$row[colour]."-australian-sapphire-".$row[sku]; }
        else{
          if ( strtolower($row[treatment]) == "unheated") {$handle = $title_mod."-".$row[colour]."-".$row[edl3]."-".str_replace("  "," ",str_replace("&","",$row[metal_composition]))."-".$type_mod."-".$row[sku]; }
          else {$handle = $title_mod."-".$row[colour]."-".str_replace("  "," ",str_replace("&","",$row[metal_composition]))."-".$type_mod."-".$row[sku];}
        } $handle = str_replace([" ","--"],"-",strtolower($handle));


        //Purchase cost calculation
        $purchase_cost = "";
        if( strtolower($row[type]) == "loose sapphires" ) { $purchase_cost = $row[purchase_cost_aud] * $row[carat]; $purchase_cost = round($purchase_cost,2); } else{ $purchase_cost = $row[purchase_cost_aud]; }

        // Tags
        $tags = "";
        if ( $row[treatment] != "") { $tags .= $row[treatment].", "; }
        if ( $row[colour] != "" ) { $tags .= $row[colour].", "; }
        if ( $row[shape] != "" ) { $tags .= $row[shape].", "; }
        if ( $row[type] != "" ) { $tags .= $row[type].", "; }
        if ( $row[main_metal] != "" ) { $tags .= $row[main_metal].", "; }
        if ( $row[metal_composition] != "" ) { $tags .= $row[metal_composition].", "; }
        if ( $row[collections] != "" ) { $tags .= $row[collections].", "; }
        if ( $row[tags] != "" ) { $tags .= $row[tags].", "; }
        if ( $row[carat] != "" ) {
          if ($row[carat] < 1.00){ $tags .= "Less than 1.00ct"; }
          elseif ($row[carat] >= 1.00 && $row[carat] <= 1.49){ $tags .= "1.00ct - 1.49ct"; }
          elseif ($row[carat] >= 1.50 && $row[carat] <= 1.99){ $tags .= "1.50ct - 1.99ct"; }
          elseif ($row[carat] >= 2.00 && $row[carat] <= 2.49){ $tags .= "2.00ct - 2.49ct"; }
          elseif ($row[carat] >= 2.50 && $row[carat] <= 2.99){ $tags .= "2.50ct - 2.99ct"; }
          elseif ($row[carat] >= 3.00 && $row[carat] <= 3.99){ $tags .= "3.00ct - 3.99ct"; }
          elseif ($row[carat] >= 4.00){ $tags .= "Greater than 4.00ct"; }
        }

        //Title
        if ( strtolower($row[type]) == "loose sapphires" && $row[carat] !== ["",0]) { $title = "Australian Sapphire ".$row[shape]." 1=".$row[carat]."ct ".$row[colour];}
        else {
          if ( strpos(strtolower($row[collections_2]), "variants") !== false) { $title = ucwords($title_mod) . " " . $row[shape] . " Sapphire " . ucwords($type_mod) ;}
          else { $title = $row[product_title];}
        }

        // Metafield: Stone Info
        $stone_info = "";
        if ( strtolower($row[treatment]) == "unheated")
          if ($row[carat] > 0) { $stone_info .= $row[carat]."ct ".$row[colour]." Sapphire, ".$row[shape].", ".$row[treatment];}
          else { $stone_info .= $row[colour]. " Sapphire, ".$row[shape].", ".$row[treatment];}
        else { $stone_info .= $row[carat]."ct ".$row[colour]." Sapphire, ".$row[shape];}

        // Metafield: Title Tag
        $title_tag = "";
        if ( strtolower($row[type]) == "loose sapphires") {
          if ( strtolower($row[treatment]) == "unheated") { $title_tag .= $row[carat] . "ct Unheated " . $row[colour] . " " . $row[shape] . " Shape Australian Sapphire";}
            else {
            if ( $row[carat] > 0) { $title_tag .= $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Shape Australian Sapphire";}
            else { $title_tag .= $row[colour] . " " . $row[shape] . " Shape Australian Sapphire";}
          }
        } else { $title_tag .= ucwords($title_mod) . " " . $row[colour] . " " . $row[shape] . " Shape " . $row[metal_composition] . " " . ucwords($type_mod);}
        $title_tag = str_replace("  ", " ", $title_tag);

        $content = array (
            0 => $row[sku],
            1 => $handle,
            2 => $command,
            3 => $description,
            4 => "REPLACE",
            5 => $row[shopify_qty],
            6 => "REPLACE",
            7 => $tags,
            8 => $title,
            9 => $row[type],
            10 => $purchase_cost,
            11 => $row[image1],
            12 => $itemprice,
            13 => "MERGE",
            14 => $row[brand],
            15 => $imageURL,
            16 => $status,
            17 => "deny",
            18 => $row[main_metal],
            19 => $row[metal_composition],
            20 => $stone_info,
            21 => $row[shape],
            22 => $title_tag,
            23 => $certification,
            24 => $row[specifications],
            25 => $row[colour],
            26 => $stone_specifications,
            27 => "shopify",
            28 => "manual",

          );

          fputcsv($fp, $content);
  }

  fclose($fp);
  $count = mysqli_num_rows($result);
  date_default_timezone_set('Australia/Sydney');
  echo "<center><h2>SD Export Completed</h2><br>";
  echo "Total of ".$count." Products Exported<br><br>";
  echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/sd-shopify.csv'>View on Web</a><br><br>";
  echo date("Y-m-d G:i a")."<br>";
  $endScriptTime=microtime(TRUE);
  $totalScriptTime=$endScriptTime-$startScriptTime;
  echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br></center>';

  $error = mysqli_error($con);
  if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }


  fclose($fp);
?>
</div>
</body>
</html>
