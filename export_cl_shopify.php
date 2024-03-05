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

  $query = 'SELECT * FROM pim WHERE (brand = "shopify cl" AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "") ORDER BY product_title ASC';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/cl-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","Command","Handle","Body HTML","Image Command","Inventory Available:Head Office","Tags","Tags Command","Title","Type","Variant Cost","Variant Image","Variant Price","Variant Command","Vendor","Image Src","Status","Variant Barcode","Variant Inventory Policy","Variant Inventory Tracker","Option1 Name","Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","Variant Metafield:custom.dial","Variant Metafield:custom.straptype","Variant Metafield:custom.gender","Image Alt Text","Variant Metafield:custom.features","Variant Metafield:custom.glass","Variant Metafield:custom.waterresistance","Variant Metafield:custom.movement","Variant Metafield:custom.dimension","Variant Metafield:custom.watchtype","Variant Metafield:custom.watchspecs", "SEO Title","Variant Fulfillment Service","Image Position", "Variant Compare At Price");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

        //Status - draft if steve, discontinued, wholesale only
        $status = "active";
        if ( $row['collections'] == "Vintage" && $row['sync_shopify'] == 0) { $status = "draft";}

        //Command - deletwe if 0 stock, MERGE if in stock but status is draft, MERGE if everything passes
        $command = "MERGE";
        if ($row['deletion'] == 1) { $command = "DELETE";}
        
        // Create handle
        if ( $row['collections'] == "Vintage") 
          if ( $row['product_title'] != "") { $handle = $row['watch_gender'] . "-" . $row['product_title'] . "-" . $row['watch_dimension']. "-" . $row['watch_movement'] . "-watch";}
          else {  $handle = $row['watch_gender'] . "-" . $row['watch_dimension']. "-" . $row['watch_movement'] . "-watch";}
        else { $handle = $row['watch_gender'] . "-" . $row['product_title'] . "-" . $row['watch_dimension'] . "-" . $row['watch_movement'] . "-watch";}
        $handle = str_replace([" ","--"],"-",strtolower($handle));            

        //check images
        $imageURL = "";
        if($row['image1'] != "") { $imageURL .= $row['image1'].";";}
        if($row['image2'] != "") { $imageURL .= $row['image2'].";";}
        if($row['image3'] != "") { $imageURL .= $row['image3'].";";}
        if($row['image4'] != "") { $imageURL .= $row['image4'].";";}
        if($row['image5'] != "") { $imageURL .= $row['image5'].";";}
        if($row['image6'] != "") { $imageURL .= $row['image6'].";";}
        if($row['packaging_image'] != "") { $imageURL .= $row['packaging_image'];}

        //optionOneName
        $optionOneName = "";
        if ( strtolower($row['brand']) === "shopify cl") { $optionOneName = "Case";}

        //optionTwoName
        $optionTwoName = "";
        if ( strtolower($row['brand']) === "shopify cl") { $optionTwoName = "Dial";}

        //optionTwoValue
        $optionTwoValue = "";
        if ( strtolower($row['brand']) === "shopify cl")
            if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionTwoValue = $row['watch_index'];}
            else { $optionTwoValue = $row['watch_dial'] . " Dial " . $row['watch_index'];}

        //optionThreeName
        $optionThreeName = "";
        if ( strtolower($row['brand']) === "shopify cl")
            if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionThreeName = "";}
            elseif ( strpos($row['tags'], "bezel-set") !== false) { $optionThreeName = "Bezel";}
            else { $optionThreeName = "Band";}

        //optionThreeValue
        $optionThreeValue = "";
        if ( strtolower($row['brand']) === "shopify cl")
            if ( strpos($row['tags'], "bezel-set") !== false) { $optionThreeValue = $row[watch_bezel];}
            else { 
              if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionThreeValue = "";}
              else {$optionThreeValue = $row['watch_strap'];}
            }

        //Metafield: watch table specifications
        $table_specifications = "<div class='col-md-6'><table><tr class='watchtype'><td><b>Watch Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>";
        if (strtolower($row['type']) !== "pocket watch" || strtolower($row['type']) !== "pendant watch") { $table_specifications .= "Wrist Watch";} else { $table_specifications .= $row['type']; }
        $table_specifications .= "</td></tr><tr class='movementtype'><td><b>Movement Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_movement'] 
        . "</td></tr><tr class='dialcolour'><td><b>Dial</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_dial'] 
        . "</td></tr><tr class='casedimension'><td><b>Case Dimension</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_dimension'] 
        . "</td></tr><tr class='Glass'><td><b>Glass</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_glass'] 
        . "</td></tr></table></div><div class='col-md-6'><table><tr class='waterresistance'><td><b>Water Resistance</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_waterresistance'] 
        . "</td></tr><tr class='gender'><td><b>Gender</b></div></td><td>" . $row['watch_gender'] 
        . "</td></tr><tr class='straptype'><td><b>Strap Type</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>" . $row['watch_strap'] 
        . "</td></tr><tr class='specialfeatures'><td><b>Special Features</b><i class='las la-question-circle'></i><div class='arr-left'></div></td><td>";
        $add_desc = "";
        if ( $row['add_desc1'] != "") { $add_desc .= $row['add_desc1'];}
        if ( $row['add_desc2'] != "") { $add_desc .= "," . $row['add_desc2'];}
        if ( $row['add_desc3'] != "") { $add_desc .= "," . $row['add_desc3'];}
        if ( $row['add_desc4'] != "") { $add_desc .= "," . $row['add_desc4'];}
        $table_specifications .= $add_desc  . "</td></tr></table></div>";

        //Vendor
        $vendor = str_replace("shopify cl","Classique Watches",strtolower($row['brand']));

        //Metafield: Dial
        if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $mf_dial = "";}
        else { $mf_dial = $row['watch_dial'];}

        //Metafield: features
        $mf_features = "";
        if($row['add_desc1'] != "") { $mf_features .= $row['add_desc1'];}
        if($row['add_desc2'] != "") { $mf_features .= " " . $row['add_desc2'];}
        if($row['add_desc3'] != "") { $mf_features .= " " . $row['add_desc3'];}
        if($row['add_desc4'] != "") { $mf_features .= " " . $row['add_desc4'];}
        $mf_features = trim($mf_features);

        //Metafield: watch type
        $mf_watchtype = "Wrist Watch";
        if (strtolower($row['type']) == "pocket watch" || strtolower($row['type']) == "pendant watch") { $mf_watchtype = $row['type'];}

        //Image Alt Text
        if (strtolower($row['type']) == "pocket watch" || strtolower($row['type']) == "pendant watch") { $image_alt_text = "Case " . $row['watch_material'] . " Dial " . $row['watch_index'] . " " . $row['watch_strap'];}
        else { $image_alt_text = "Case " . $row['watch_material'] . " Dial " . $row['watch_dial'] . " Dial " . $row['watch_index'] . " " . $row['watch_strap'];}

        //SEO Title
        $seo_title = "";
        if ( $row['collections'] == "Vintage") 
          if ( $row['product_title'] != "") { $seo_title = "vintage " . $row['watch_gender'] . " " . $row['watch_material'] . " " . $row['product_title'] . " watch";}
          else { $seo_title = "vintage " . $row['watch_gender'] . " " . $row['watch_material'] . " watch";}
        else {
          if($row['product_title'] != "") { $seo_title .= $row['product_title'];}
          if($row['watch_material'] != "") { $seo_title .= " " . $row['watch_material'];}
          if($row['watch_dimension'] != "") { $seo_title .= " " . $row['watch_dimension'];}
          if($row['watch_material'] != "") { $seo_title .= " " . $row['watch_material'] . " watch";} 
        }
        $seo_title = strtolower($seo_title);

        // Tags
        $tags = "";
        if ( $row['tags'] != "") { $tags .= $row['tags'].", "; }
        if ( $row['collections'] != "" ) { $tags .= $row['collections'].", "; }
        $tags = str_replace("Solid Gold","solid-gold",$tags);
        if ( $row['watch_material'] != "") { $tags .= "Material " . $row['watch_material'];}
        if ( $row['watch_dial'] != "") { $tags .= "," . $row['watch_dial'];}
        if ( $row['watch_gender'] != "") { $tags .= "," . $row['watch_gender'];}
        if ( $row['product_title'] != "") { $tags .= ",_alt_" . $row['product_title'];}
        if ( $row['watch_movement'] != "") { $tags .= "," . $row['watch_movement'];}
        if ( $row['watch_strap'] != "") { $tags .= "," . $row['watch_strap'];}
        if ( strpos($row['watch_strap'], "Leather") !== false) { $tags .= ",Leather Strap Watch";}
        if ( $row['type'] != "") { $tags .= "," . $row['type'];}
        if ( $row['collections'] == "Vintage" && $row['collections_2'] != "") { $tags .= ",multiimages," . $row['collections_2'];}
        $tags .= ",Classique watches,relatedproducts";
        for ($length = 1; $length <= strlen($sku); $length++) {
          $tags .= substr($sku, 0, $length) . ",";}


        //Title
        $title = "";
        if ($row['collections'] == "Vintage") { $title = "Vintage " . $row['product_title'] . " " . $$row['watch_gender'] . " Watch"; }
        else { $title = $row['product_title'];}
        $title = str_replace(["  "]," ",$title);  

        //Retail Price for compare
        $compare_price = 0;
        if ($row['collections'] == "Vintage") { $compare_price = $row['retail_aud'];}
        
        //Sale Price for Vintage collection, else RRP
        $price = $row['retail_aud'];
        if ($row['collections'] == "Vintage" && $row['sales_percentage'] !== "") { $price = ceil($row['retail_aud']*((100-$row['sales_percentage'])/100));}


        $content = array (
            0 => $row['sku'],
            1 => $command,
            2 => $handle,
            3 => $row['description'],
            4 => "REPLACE",
            5 => $row['shopify_qty'],
            6 => $tags,
            7 => "REPLACE",
            8 => $title,
            9 => $row['type'],
            10 => $row['purchase_cost_aud'],
            11 => $row['image1'],
            12 => $price,
            13 => "MERGE",
            14 => $vendor,
            15 => $imageURL,
            16 => $status,
            17 => "",
            18 => "deny",
            19 => "shopify",
            20 => $optionOneName,
            21 => $row['watch_material'],
            22 => $optionTwoName,
            23 => $optionTwoValue,
            24 => $optionThreeName,
            25 => $optionThreeValue,
            26 => $mf_dial,
            27 => $row['watch_strap'],
            28 => $row['watch_gender'],
            29 => $image_alt_text,
            30 => $mf_features,
            31 => $row['watch_glass'],
            32 => $row['watch_waterresistance'],
            33 => $row['watch_movement'],
            34 => $row['watch_dimension'],
            35 => $mf_watchtype,
            36 => $table_specifications,
            37 => $seo_title,
            38 => "manual",
            39 => 1,
            40 => $compare_price,
            
          );



          fputcsv($fp, $content);
  }

  fclose($fp);
  $count = mysqli_num_rows($result);
  date_default_timezone_set('Australia/Sydney');
  echo "<center><h2>CL Export Completed!</h2><br>";
  echo "Total of ".$count." Products Exported<br><br>";
  echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/cl-shopify.csv'>View on Web</a><br><br>";
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