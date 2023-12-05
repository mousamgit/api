<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (image1<> "" AND retail_aud > 0 AND description<> "" AND sync_shopify=1 AND brand = "Sapphire Dreams" AND collections != "SDL" AND collections != "SDM");';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/export/sd-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","handle","Command","Body HTML","Image Command","Inventory Available:Sapphire Dreams Head Office","Tags Command","Tags","Title","Type","Variant Cost","Variant Image","Variant Price","Variant Command","Vendor","Image Src","Status","Variant Inventory Policy","Metafield:custom.metal_colour","Metafield:custom.metal_info","Metafield:custom.stone_info","Metafield:custom.stone_shape","Metafield:title_tag","Metafield:custom.certification","Metafield:custom.specifications","Metafield:custom.stone_colour","Metafield:custom.stone_specifications");
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
        if ( preg_match("/Cert/i", $row[specifications]) > 0) { $stone_specifications = "ID No.: ".str_replace("SDS","",$row[sku])."<br>Colour: ".strtoupper($row[colour]).str_replace("Unheated"," NH",$row[treatment])."<br>Shape: ".strtoupper($row[shape])."<br>Weight: ".$row[carat]."ct<br>Size: ".$row[measurement]."<br>Origin: AUSTRALIA"; $stone_specifications = str_replace("  "," ",$stone_specifications);}
        else { $stone_specifications = "";}

        // Metafield: Certification
        if ( preg_match("/Cert/i", $row[specifications]) > 0) { $certification = "YES"; }
        else { $certification = "NO";}

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
        if ( preg_match("/steve/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/discontinued/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/wholesale_only/i", strtolower($row[collections_2]))) { $status = "draft"; }
        else { $status = "active"; }

        //Command - delete if 0 stock, MERGE if in stock but status is draft, MERGE if everything passes
        $command = "";
        if ($row[shopify_qty] > 0) {
        if ($status == "active") { $command = "MERGE";  }
        if ($status == "draft") { $command = "MERGE"; }
        }else { $command = "DELETE";}


        // Stone price vs item price
        $itemprice = "";
        if( strtolower($row[type]) == "loose sapphires" ){ $itemprice = $row[stone_price_retail_aud]; } else{ $itemprice = $row[retail_aud]; }

        // Create handle
        $handle = "";
        if( strtolower($row[type]) == "loose sapphires" ){ $handle = $row[shape]."-".$row[colour]."-australian-sapphire-".$row[sku]; $handle = str_replace(" ","-",strtolower($handle)); }
        else{ $handle = $title_mod."-".$row[colour]."-".str_replace("unheated", "nh", strtolower($row[treatment]))."-".str_replace("  "," ",str_replace("&","",$row[metal_composition]))."-".$type_mod."-".$row[sku];
        $handle = str_replace([" ","--"],"-",strtolower($handle));}

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
            3 => $row[description],
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
            26 => $stone_specifications
          );

          fputcsv($fp, $content);
  }

date_default_timezone_set('Australia/Sydney');
echo "SD Export Completed!<br>";
echo date("Y-m-d G:i a");


  fclose($fp);
?>
