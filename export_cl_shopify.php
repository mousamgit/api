<?php
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (brand = "shopify cl" AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "")';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/export/sga-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","Command","Handle","Body HTML","Image Command","Inventory Available:Head Office","Tags","Tags Command","Title","Type","Variant Cost","Variant Image","Variant Price","Variant Command","Vendor","Image Src","Status","Variant Barcode","Variant Inventory Policy","Variant Inventory Tracker","Option1 Name","Option1 Value","Option3 Name","Option2 Name","Option2 Value","Option3 Value","Variant Metafield:custom_dial","Variant Metafield:custom_straptype","Variant Metafield:custom_gender","Image Alt Text","Variant Metafield:custom_features","Variant Metafield:custom_glass","Variant Metafield:custom_waterresistance","Variant Metafield:custom_movement","Variant Metafield:custom_dimension","Variant Metafield:custom_watchtype","Variant Metafield:custom_watchspecs", "SEO Title","Variant Fulfillment Service","Image Position");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

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
        if ( $row[add_desc2] != "") { $add_desc .= "," . $row[add_desc2];}
        if ( $row[add_desc3] != "") { $add_desc .= "," . $row[add_desc3];}
        if ( $row[add_desc4] != "") { $add_desc .= "," . $row[add_desc4];}
        $table_specifications .= $add_desc  . "</td></tr></table></div>";

        //Vendor
        $vendor = str_replace("Shopify CL","Classique Watches",$row[brand]);

        //Metafield: Dial
        if (strtolower($row[type]) === "pocket watch" || strtolower($row[type]) === "pendant watch") { $mf_dial = "";} else { $mf_dial = $row[dial];}

        //Metafield: features
        $mf_features = "";
        if($row[add_desc1] != "") { $mf_features .= $row[add_desc1];}
        if($row[add_desc2] != "") { $mf_features .= " " . $row[add_desc2];}
        if($row[add_desc3] != "") { $mf_features .= " " . $row[add_desc3];}
        if($row[add_desc4] != "") { $mf_features .= " " . $row[add_desc4];}
        $mf_features = trim($mf_features);

        //Metafield: watch type
        if (strtolower($row[type]) !== "pocket watch" || strtolower($row[type]) !== "pendant watch") { $mf_watchtype = "Wrist Watch";} else { $mf_watchtype = $row[type];}

        //Image Alt Text
        if (strtolower($row[type]) == "pocket watch" || strtolower($row[type]) == "pendant watch") { $image_alt_text = "Case " . $row[watch_material] . " Dial " . $row[watch_index] . " " . $row[watch_strap];}
        else { $image_alt_text = "Case " . $row[watch_material] . " Dial " . $row[watch_dial] . " Dial " . $row[watch_index] . " " . $row[watch_strap];}

        //SEO Title
        $seo_title = "";
        if($row[product_title] != "") { $seo_title .= $row[product_title];}
        if($row[watch_material] != "") { $seo_title .= " " . $row[watch_material];}
        if($row[watch_dimension] != "") { $seo_title .= " " . $row[watch_dimension];}
        if($row[watch_material] != "") { $seo_title .= " " . $row[watch_material] . " watch";}
        $seo_title = strtolower($seo_title);

        $content = array (
            0 => $row[sku],
            1 => $command,
            2 => $handle,
            3 => $row[description],
            4 => $row[shopify_qty],
            5 => $tags,
            6 => "REPLACE",
            7 => $row[product_title],
            8 => $row[type],
            9 => $row[purchase_cost_aud],
            10 => $row[image1],
            11 => $row[retail_aud],
            12 => "MERGE",
            13 => $vendor,
            14 => $imageURL,
            15 => $status,
            16 => "deny",
            17 => "shopify",
            18 => $optionOneName,
            19 => $row[watch_material],
            20 => $optionTwoName,
            21 => $optionTwoValue,
            22 => $optionThreeName,
            23 => $optionThreeValue,
            24 => $mf_dial,
            25 => $row[watch_strap],
            26 => $row[watch_gender],
            27 => $image_alt_text,
            28 => $mf_features,
            29 => $row[watch_glass],
            30 => $row[watch_waterresistance],
            31 => $row[watch_movement],
            32 => $row[watch_dimension],
            33 => $mf_watchtype,
            34 => $table_specifications,
            36 => $seo_title,
            37 => "manual",
            38 => 1,

          );

          fputcsv($fp, $content);
  }

date_default_timezone_set('Australia/Sydney');
echo "SGA Wholesale Export Completed!<br>";
echo date("Y-m-d G:i a");


  fclose($fp);
?>
