<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (brand = "blush pink diamonds" AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "" AND (shopify_qty > 0 OR preorder = 1)) OR (brand in ("sapphire dreams","argyle white diamonds","pink kimberley diamonds","white diamond jewellery","argyle pink diamonds","argyle blue diamonds","argyle origin diamonds") AND collections not in ("sdm","melee") AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "" AND shopify_qty > 0);';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/export/sga-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","Command","Handle","Body HTML","Inventory Available:Home","Inventory Available:MD","Inventory Available:PS","Status","Tags","Tags Command","Title","Type","Variant Barcode","Variant Command","Variant Cost","Variant Inventory Policy","Variant Inventory Tracker","Variant Price","Vendor","Option1 Name","Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","Image Src","Image Command","Variant Image","Metafield:custom.argyle_colour","Metafield:custom.certification","Metafield:custom.product_caratprice","Metafield:custom.product_rrp","Metafield:custom.specifications","Metafield:custom.stone_carat","Metafield:custom.stone_clarity","Metafield:custom.stone_colour","Metafield:custom.stone_measurement","Metafield:custom.stone_shape","Metafield:custom.table_specifications","Variant Fullfilment Service");
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

        // Create handle
        $handle = "";
        if (strtolower($row[brand]) === "sapphire dreams")
          if( strtolower($row[type]) == "loose sapphires" )
            if ( strtolower($row[treatment]) == "unheated") { $handle = $row[shape]."-".$row[colour].$row[edl]."-australian-sapphire-".$row[sku]; }
            else { $handle = $row[shape]."-".$row[colour]."-australian-sapphire-".$row[sku]; }
          else{ 
            if ( strtolower($row[treatment]) == "unheated") {$handle = $title_mod."-".$row[colour]."-".$row[edl3]."-".str_replace("  "," ",str_replace("&","",$row[metal_composition]))."-".$type_mod."-".$row[sku]; }
            else {$handle = $title_mod."-".$row[colour]."-".str_replace("  "," ",str_replace("&","",$row[metal_composition]))."-".$type_mod."-".$row[sku];}
          }
        elseif ( strtolower($row[brand]) === "pink kimberley diamonds" || strtolower($row[brand]) === "blush pink diamonds") { $handle = $handle = $row[product_title]."-".$row[colour]."-".$row[sku]; }    
        elseif ( strtolower($row[brand]) === "argyle pink diamonds" || strtolower($row[brand]) === "argyle origin diamonds") { $handle = "argyle-pink-diamond-" . $row[shape] . "-" . $row[colour] . "-" . $row[clarity] . "-" . $row[sku]; } 
        else{ $handle = $row[product_title]."-".$row[colour]."-".$row[sku];}       
        $handle = str_replace([" ","--"],"-",strtolower($handle));

        //Command - delete if 0 stock, MERGE if in stock but status is draft, MERGE if everything passes
        $command = "";
        if ($row[shopify_qty] > 0) {
        if ($status == "active") { $command = "MERGE";  }
        if ($status == "draft") { $command = "MERGE"; }
        }else { $command = "DELETE";}

        //Status - draft if steve, discontinued, wholesale only
        $status = "";
        if ( preg_match("/steve/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/discontinued/i", strtolower($row[collections_2]))) { $status = "draft"; }
        elseif ( preg_match("/wholesale_only/i", strtolower($row[collections_2]))) { $status = "draft"; }

        // Tags
        $tags = "";
        if ( $row[tags] != "") { $tags .= $row[tags].", "; }
        if ( $row[brand] != "" ) { $tags .= $row[brand].", "; }
        if ( $row[colour] != "" ) { $tags .= $row[colour].", "; }
        if ( $row[shape] != "" ) { $tags .= $row[shape].", "; }
        if ( $row[type] != "" ) { $tags .= $row[type].", "; }
        if ( $row[collections] != "" ) { $tags .= $row[collections].", "; }
        if ( $row[metal_composition] != "" ) { $tags .= $row[metal_composition].", "; }
        if ( $row[main_metal] != "" ) { $tags .= $row[main_metal]." Metal, "; }

        if ( strtolower($row[brand]) === "pink kimberley diamonds" || strtolower($row[brand]) === "blush pink diamonds" || strtolower($row[brand]) === "argyle pink diamonds" || strtolower($row[brand]) === "argyle origin diamonds"  )
            if ( $row[colour] != "" ) {
            if (preg_match("/pp/i", strtolower($row[colour])) > 0){ $tags .= "PP - Purplish Pink, "; }
            elseif (preg_match("/pr/i", strtolower($row[colour])) > 0){ $tags .= "PR - Pink Rose, "; }
            elseif (preg_match("/pc/i", strtolower($row[colour])) > 0){ $tags .= "PC - Pink Champagne, "; }
            elseif (preg_match("/bl/i", strtolower($row[colour])) > 0){ $tags .= "BL - Blue, "; }
            elseif (preg_match("/pred/i", strtolower($row[colour])) > 0){ $tags .= "pRed - Pinkish Red, "; }
            else { $tags .= "P - Pink, "; }
            }
            if ( $row[clarity] != "" ) { $tags .= $row[clarity].", "; }
            if ( $row[preorder] == 1 ) { $tags .= "Preorder, "; }
            if ( strtolower($row[type]) == "loose diamonds") {
            if ($row[collections] == "SKS") { $tags .= "pkcertified";}
            if ($row[collections] == "STN") { $tags .= "argylecertified";}
            }
        elseif ( strtolower($row[brand]) === "sapphire dreams" )
            if ( $row[treatment] != "") { $tags .= $row[treatment].", "; }
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

        //Metafield : argyle colour
        if ( strpos(strtolower($row[brand]), "argyle") !== false) { $argyle_colour = str_replace("RED:PURPLISH RED","pRed",$row[colour]);}

        // Metafield: Certification
        if ( strpos($row[specifications], "Certificate") !== false)  { $certification = "YES"; }
        else { $certification = "NO";}

        //Descriptions, if loose sapphire generate description else import from field description
        if (strtolower($type) == "loose sapphires") 
          if( strtolower($treatment) == "unheated") { $description = "An unheated Australian " .  ucfirst(strtlower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . "."; }
          else {$description = "An Australian " .  ucfirst(strtlower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . ".";  }
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

        //optionOneName
        if ( strtolower($row[brand]) === "shopify cl") { $optionOneName = "Case";}

        //optionTwoName
        if ( strtolower($row[brand]) === "shopify cl") { $optionTwoName = "Dial";}

        //optionTwoValue
        if ( strtolower($row[brand]) === "shopify cl")
            if ( strtolower($row[type]) == "pendant watch" || strtolower($row[type]) == "pocket watch") { $optionTwoValue = $row[watch_index];}
            else { $optionTwoValue = $row[watch_dial] . " Dial " . $row[watch_index];}

        //optionThreeName
        if ( strtolower($row[brand]) === "shopify cl")
            if ( strpos($row[tags], "bezel-set") !== false) { $optionThreeName = "Bezel";}
            else { $optionThreeName = "Band";}

        //optionThreeValue
        if ( strtolower($row[brand]) === "shopify cl")
            if ( strpos($row[tags], "bezel-set") !== false) { $optionThreeName = $row[watch_diamonds];}
            else { $optionThreeName = $row[watch_strap];}

        //Metafield: watch table specifications
        $table_specifications = "<div class='col-md-6'><table><tr class='watchtype'><td><b>Watch Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>";
        if (strtolower($row[type]) !== "pocket watch" || strtolower($row[type]) !== "pendant watch") { $table_specifications .= "Wrist Watch";} else { $table_specifications .= $row[type]; }
        $table_specifications .= "</td></tr><tr class='movementtype'><td><b>Movement Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_movement] 
        . "</td></tr><tr class='dialcolour'><td><b>Dial</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_dial] 
        . "</td></tr><tr class='casedimension'><td><b>Case Dimension</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_dimension] 
        . "</td></tr><tr class='Glass'><td><b>Glass</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_glass] 
        . "</td></tr></table></div><div class='col-md-6'><table><tr class='waterresistance'><td><b>Water Resistance</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_waterresistance] 
        . "</td></tr><tr class='gender'><td><b>Gender</b></div></td><td>" . $row[watch_gender] 
        . "</td></tr><tr class='straptype'><td><b>Strap Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row[watch_strap] 
        . "</td></tr><tr class='specialfeatures'><td><b>Special Features</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>";
        $add_desc = "";
        if ( $row[add_desc1] != "") { $add_desc .= $row[add_desc1];}
        if ( $row[add_desc2] != "") { $add_desc .= $row[add_desc2];}
        if ( $row[add_desc3] != "") { $add_desc .= $row[add_desc3];}
        if ( $row[add_desc4] != "") { $add_desc .= $row[add_desc4];}
        $table_specifications .= $add_desc  . "</td></tr></table></div>";


        $content = array (
            0 => $row[sku],
            1 => $command,
            2 => $handle,
            3 => $description,
            4 => $row[shopify_qty],
            5 => $row[mdqty],
            6 => $row[psqty],
            7 => $status,
            8 => $tags,
            9 => "REPLACE",
            10 => $title,
            11 => $row[type],
            12 => $row[sku],
            13 => "MERGE",
            14 => $row[purchase_cost_aud],
            15 => "deny",
            16 => "shopify",
            17 => $row[wholesale_aud],
            18 => $row[brand],
            19 => $optionOneName,
            20 => $row[watch_material],
            21 => $optionTwoName,
            22 => $optionTwoValue,
            23 => $optionThreeName,
            24 => $optionThreeValue,
            25 => $imageURL,
            26 => "REPLACE",
            27 => $row[image1],
            28 => $argyle_colour,
            29 => $certification,
            30 => $row[stone_price_wholesale_aud],
            31 => $row[retail_aud],
            32 => $row[specifications],
            33 => $row[carat],
            34 => $row[clarity],
            35 => $row[colour],
            36 => $row[measurement],
            37 => $row[shape],
            38 => $table_specifications,
            39 => "manual",

          );

          fputcsv($fp, $content);
  }

$count = mysqli_num_rows($result) - 1;

date_default_timezone_set('Australia/Sydney');
echo "SGA Wholesale Export Completed!<br>";
echo "Total Products Uploaded: ".$count."<br>";
echo date("Y-m-d G:i a");


  fclose($fp);
?>
