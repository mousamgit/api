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

  $query = 'SELECT * FROM pim WHERE (brand = "blush pink diamonds" AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "" AND (shopify_qty > 0 OR preorder = 1)) OR (brand in ("sapphire dreams","argyle white diamonds","pink kimberley diamonds","white diamond jewellery","argyle pink diamonds","argyle blue diamonds","argyle origin diamonds") AND collections not in ("sdm","melee") AND wholesale_aud > 0 AND retail_aud > 0 AND description <> "" AND image1 <> "") OR (brand = "shopify cl" && collections <> "Vintage") OR ((type = "loose sapphires" OR type = "loose diamonds") AND collections not in ("sdm","melee") AND wholesale_aud > 0 AND retail_aud > 0 AND image1 <> "") ORDER BY product_title ASC;';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/sga-shopify.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Variant SKU","Command","Handle","Body HTML","Inventory Available:Home","Inventory Available:MD","Inventory Available:PS","Status","Tags","Tags Command","Title","Type","Variant Barcode","Variant Command","Variant Cost","Variant Inventory Policy","Variant Inventory Tracker","Variant Price","Vendor","Option1 Name","Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","Image Src","Image Command","Variant Image","Metafield:custom.argyle_colour","Metafield:custom.certification","Metafield:custom.product_caratprice","Metafield:custom.product_rrp","Metafield:custom.specifications","Metafield:custom.stone_carat","Metafield:custom.stone_clarity","Metafield:custom.stone_colour","Metafield:custom.stone_measurement","Metafield:custom.stone_shape","Metafield:custom.table_specifications","Variant Fullfilment Service","ID");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

  $title_mod = trim(str_replace(["earrings","pendant","necklace","bracelet"," ring","various"],"",strtolower($row['product_title'])));
  $type_mod = str_replace(["rings","pendants","necklaces","bracelets","earring"],["ring","pendant","necklace","bracelet","earrings"],strtolower($row['type']));

        // Create handle
        $handle = "";
        if ( strtolower($row['brand']) == "shopify cl") { $handle .= $row['product_title'] . "-watch";}
        elseif ( strtolower($row['brand']) == "sapphire dreams")
          if ( strtolower($row['type']) == "loose sapphires") { $handle .= $row['shape'] . "-" . $row['colour'] . "-australian-sapphire-" . $row['sku'];}
          else { $handle .= $row['product_title'] . "-" . $row['sku'];}
        elseif ( strtolower($row['brand']) == "argyle origin diamonds" || strtolower($row['brand']) == "argyle pink diamonds") { $handle .= "argyle-pink-diamond-" . $row['shape'] . "-" . $row['colour'] . "-" . $row['clarity'] . "-" . $row['sku'];}
        elseif ( strtolower($row['brand'])== "pink kimberley diamonds" || strtolower($row['brand']) == "blush pink diamonds") { $handle .= $row['product_title'] . "-" . $row['sku'];}
        else { $handle .= $row['product_title'] . "-" . $row['sku'];}
        $handle = str_replace([" ","--"],"-",strtolower($handle));             

        //Status - draft if steve, discontinued, wholesale only
        $status = "active";
        if ( preg_match("/steve/i", strtolower($row['collections_2']))) { $status = "draft"; }
        elseif ( preg_match("/discontinued/i", strtolower($row['collections_2']))) { $status = "draft"; }

        //Command - delete if 0 stock, MERGE if in stock but status is draft, MERGE if everything passes
        $command = "MERGE";
        if ($row['deletion'] == 1) {$command = "DELETE";}
        elseif ($row['shopify_qty'] <= 0) {
          if ($row['brand'] == "Shopify CL") {$command = "MERGE";}
          elseif ($row['preorder'] == 1) {$command = "MERGE";}
          else {$command = "DELETE";}
        }
        
        // Tags
        $tags = "";
        if ( strtolower($row['brand']) === "shopify cl"){
            if ( $row['watch_material'] != "") { $tags .= "Material " . $row['watch_material'];}
            if ( $row['watch_dial'] != "") { $tags .= "," . $row['watch_strap'];}
            if ( $row['product_title'] != "") { $tags .= ",_alt_" . $row['product_title'];}
            if ( $row['watch_movement'] != "") { $tags .= "," . $row['watch_movement'];}
            if ( $row['watch_strap'] != "") { $tags .= "," . $row['watch_strap'];}
            if ( strpos($row['watch_strap'], "leather")) { $tags .= ",Leather Strap Watch";}
            if ( strtolower($row['watch_strap']) == "solid gold") { $tags .= ",solid-gold";}
            if ( $row['type'] != "") { $tags .= "," . $row['type'];}
            if ( $row['collections'] != "") { $tags .= "," . $row['watch_collections'];}
            if ( $row['tags'] != "") { $tags .= ",".$row['tags'];}
            $tags .= ",Classique watches,relatedproducts";
        }else {
            if ( $row['tags'] != "") { $tags .= $row['tags'].", "; }
            if ( $row['brand'] != "" ) { $tags .= $row['brand'].", "; }
            if ( $row['colour'] != "" ) { $tags .= $row['colour'].", "; }
            if ( $row['shape'] != "" ) { $tags .= $row['shape'].", "; }
            if ( $row['type'] != "" ) { $tags .= $row['type'].", "; }
            if ( $row['collections'] != "" ) { $tags .= $row['collections'].", "; }
            if ( $row['metal_composition'] != "" ) { $tags .= $row['metal_composition'].", "; }
            if ( $row['main_metal'] != "" ) { $tags .= $row['main_metal']." Metal, "; }

            if ( strtolower($row['brand']) === "pink kimberley diamonds" || strtolower($row['brand']) === "blush pink diamonds" || strtolower($row['brand']) === "argyle pink diamonds" || strtolower($row['brand']) === "argyle origin diamonds"  ){
              if ( $row['colour'] != "" ) {
                if (preg_match("/pp/i", strtolower($row['colour'])) > 0){ $tags .= "PP - Purplish Pink, "; }
                elseif (preg_match("/pr/i", strtolower($row['colour'])) > 0){ $tags .= "PR - Pink Rose, "; }
                elseif (preg_match("/pc/i", strtolower($row['colour'])) > 0){ $tags .= "PC - Pink Champagne, "; }
                elseif (preg_match("/bl/i", strtolower($row['colour'])) > 0){ $tags .= "BL - Blue, "; }
                elseif (preg_match("/pred/i", strtolower($row['colour'])) > 0){ $tags .= "pRed - Pinkish Red, "; }
                else { $tags .= "P - Pink, "; }
              }
              if ( $row['clarity'] != "" ) { $tags .= $row['clarity'].", "; }
              if ( $row['preorder'] == 1 ) { $tags .= "Preorder, "; }
              if ( strtolower($row['type']) == "loose diamonds") {
              if ($row['collections'] == "SKS") { $tags .= "pkcertified";}
              if ($row['collections'] == "STN") { $tags .= "argylecertified";}
              }
            }
            elseif ( strtolower($row['brand']) === "sapphire dreams" ){
                if ( $row['treatment'] != "") { $tags .= $row['treatment'].", "; }
                if ( $row['carat'] != "" ) {
                if ($row['carat'] < 1.00){ $tags .= "Less than 1.00ct"; }
                elseif ($row['carat'] >= 1.00 && $row['carat'] <= 1.49){ $tags .= "1.00ct - 1.49ct"; }
                elseif ($row['carat'] >= 1.50 && $row['carat'] <= 1.99){ $tags .= "1.50ct - 1.99ct"; }
                elseif ($row['carat'] >= 2.00 && $row['carat'] <= 2.49){ $tags .= "2.00ct - 2.49ct"; }
                elseif ($row['carat'] >= 2.50 && $row['carat'] <= 2.99){ $tags .= "2.50ct - 2.99ct"; }
                elseif ($row['carat'] >= 3.00 && $row['carat'] <= 3.99){ $tags .= "3.00ct - 3.99ct"; }
                elseif ($row['carat'] >= 4.00){ $tags .= "Greater than 4.00ct"; }
                }
            }
          }  
        

        //Metafield : argyle colour
        if ( strpos(strtolower($row['brand']), "argyle") !== false) { $argyle_colour = str_replace("RED:PURPLISH RED","pRed",$row['colour']);}
        elseif ( strtolower($row['brand']) == "blush pink diamonds" || strtolower($row['brand']) == "pink kimberley diamonds") { $argyle_colour = str_replace("RED:PURPLISH RED","pRed",$row['colour']);}
        else { $argyle_colour = "";}

        // Metafield: Certification
        if ( strpos(strtolower($row['specifications']), "cert") !== false)  { $certification = "YES"; }
        elseif ( strpos($row['specifications'], "GIA") !== false)  { $certification = "YES"; }
        elseif ( strpos($row['specifications'], "GSL") !== false)  { $certification = "YES"; }
        else { $certification = "NO";}

        //Descriptions, if loose sapphire generate description else import from field description
        if (strtolower($row['type']) == "loose sapphires") {
          if( strtolower($row['treatment']) == "unheated") { $description = "An unheated Australian " .  ucfirst(strtolower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . "."; }
          else {$description = "An Australian " .  ucfirst(strtolower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . ".";  }
        }
        else { $description = $row['description'];}

        //Title
        $title = "";
        if (strtolower($row['brand']) == "shopify cl") { $title = $row['product_title'] . " " . $row['type'];}
        elseif ( strtolower($row['brand']) == "sapphire dreams")
          if ( strtolower($row['type']) == "loose sapphires")
            if ( $row['treatment'] == "Unheated") { $title = "Australian Sapphire ". ucfirst(strtolower($row['shape']))." 1=".$row['carat']."ct ".$row['colour'] . " NH";}
            else { $title = "Australian Sapphire ".ucfirst(strtolower($row['shape']))." 1=".$row['carat']."ct ".$row['colour'];}
          else {
            if ( strpos(strtolower($row['collections_2']), "variants") !== false) { $title = ucwords($title_mod) . " " . $row['shape'] . " Sapphire " . ucwords($type_mod);}
            else { $title = $row['product_title'];}}
        else {
          if ($row['collections'] == "TPR" || $row['collections'] == "TDR") { $title = str_replace("pink diamond","tender diamond",$row['product_title']);}
          else { $title = $row['product_title'];}
        } 

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
        else { $optionOneName = "";}
        
        //optionTwoName
        $optionTwoName = "";
        if ( strtolower($row['brand']) === "shopify cl") { $optionTwoName = "Dial";}
        else { $optionTwoName = "";}

        //optionTwoValue
        $optionTwoValue = "";
        if ( strtolower($row['brand']) === "shopify cl"){
            if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionTwoValue = $row['watch_index'];}
            else { $optionTwoValue = $row['watch_dial'] . " Dial " . $row['watch_index'];}}
        else { $optionTwoValue = "";}

        //optionThreeName
        $optionThreeName = "";
        if ( strtolower($row['brand']) === "shopify cl")
            if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionThreeName = "";}
            elseif ( strpos($row['tags'], "bezel-set") !== false) { $optionThreeName = "Bezel";}
            else { $optionThreeName = "Band";}
        else { $optionThreeName = "";}

        //optionThreeValue
        $optionThreeValue = "";
        if ( strtolower($row['brand']) === "shopify cl"){
            if ( strpos($row['tags'], "bezel-set") !== false) { $optionThreeValue = $row['watch_bezel'];}
            else { 
              if ( strtolower($row['type']) == "pendant watch" || strtolower($row['type']) == "pocket watch") { $optionThreeValue = "";}
              else {$optionThreeValue = $row['watch_strap'];}
            }}
        else { $optionThreeValue = "";}

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
        if ( $row['add_desc2'] != "") { $add_desc .= $row['add_desc2'];}
        if ( $row['add_desc3'] != "") { $add_desc .= $row['add_desc3'];}
        if ( $row['add_desc4'] != "") { $add_desc .= $row['add_desc4'];}
        $table_specifications .= $add_desc  . "</td></tr></table></div>";

        //Brand replace shopify cl to classique watches
        $brand = str_replace("Shopify CL","Classique Watches",$row['brand']);

        //Product Carat Price
        if ( strpos(strtolower($row['type']), "loose") !== false ) { $caratprice = $row['wholesale_aud'];}
        else { $caratprice = "";}

        //Product RRP
        if ( strpos(strtolower($row['type']), "loose") !== false ) { $rrp = $row['stone_price_retail_aud'];}
        else { $rrp = $row['retail_aud'];}

        //Price wholesale AUD
        if ( strpos(strtolower($row['type']), "loose") !== false ) { $variant_price = $row['stone_price_wholesale_aud'];}
        else { $variant_price = $row['wholesale_aud'];}

        //Carat
        if ( strpos(strtolower($row['type']), "loose") !== false ) { $caratweight = $row['carat'];}
        else { $caratweight = "";}

        //Stone Colour 'Pink' for argyle colour + treatment for SD
        if ( strtolower($row['brand']) == "sapphire dreams") 
          if ($row[$treatment] == "Unheated") { $stonecolour = $row['colour'] . " NH"; }
          else {$stonecolour = $row['colour']; }
        elseif ( strtolower($row['brand']) == "pink kimberley diamonds" || strtolower($row['brand']) == "blush pink diamonds" || strtolower($row['brand']) == "argyle pink diamonds" || strtolower($row['brand']) == "argyle origin diamonds") { $stonecolour = "Pink";}
        else { $stonecolour = "";}



        $content = array (
            0 => $row['sku'],
            1 => $command,
            2 => $handle,
            3 => $description,
            4 => $row['shopify_qty'],
            5 => $row['mdqty'],
            6 => $row['psqty'],
            7 => $status,
            8 => $tags,
            9 => "REPLACE",
            10 => $title,
            11 => $row['type'],
            12 => $row['sku'],
            13 => "MERGE",
            14 => $row['purchase_cost_aud'],
            15 => "deny",
            16 => "shopify",
            17 => $variant_price,
            18 => $brand,
            19 => $optionOneName,
            20 => $row['watch_material'],
            21 => $optionTwoName,
            22 => $optionTwoValue,
            23 => $optionThreeName,
            24 => $optionThreeValue,
            25 => $imageURL,
            26 => "REPLACE",
            27 => $row['image1'],
            28 => $argyle_colour,
            29 => $certification,
            30 => $caratprice,
            31 => $rrp,
            32 => $row['specifications'],
            33 => $caratweight,
            34 => $row['clarity'],
            35 => $stonecolour,
            36 => $row['measurement'],
            37 => $row['shape'],
            38 => $table_specifications,
            39 => "manual",
            40 => $row['ws_product_id'],

          );

          fputcsv($fp, $content);
  }


  fclose($fp);
  $count = mysqli_num_rows($result) -1;
  date_default_timezone_set('Australia/Sydney');
  echo "<h2>SGA Export Completed!</h2><br>";
  echo "Total Products Exported to CSV: ".$count."<br>";
  echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/sga-shopify.csv'>View on Web</a><br><br>";
  echo date("Y-m-d G:i a")."<br>";
  $endScriptTime=microtime(TRUE);
  $totalScriptTime=$endScriptTime-$startScriptTime;
  echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br>';

  $error = mysqli_error($con);
  if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }


  fclose($fp);
?>
</div>
</body>
</html>