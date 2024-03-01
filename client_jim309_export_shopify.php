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

  $query = 'SELECT * FROM pim WHERE (client_jim309_qty > 0 AND brand IN ("blush pink diamonds", "pink kimberley diamonds","sapphire dreams") AND image1 <> "" AND image1 IS NOT NULL AND collections <> "melee" AND collections <> "sdm" AND collections <> "sdl") OR (client_jim309_qty <= 0 AND client_sgastock = 1 AND collections_2 <> "steve" && collections_2 <> "discontinued" AND brand <> "classique watches" AND brand <>"shopify cl" AND image1 <> "" AND image1 IS NOT NULL AND collections <> "melee" AND collections <> "sdm" AND collections <> "sdl")';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/client_export/jim309_product_import.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Handle","Title","Body (HTML)","Vendor","Product Category","Type","Tags","Published","Option1 Name","Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","Variant SKU","Variant Grams","Variant Inventory Tracker","Variant Inventory Qty","Variant Inventory Policy","Variant Fulfillment Service","Variant Price","Variant Compare At Price","Variant Requires Shipping","Variant Taxable","Variant Barcode","Image Src","Image Position","Image Alt Text","Gift Card","SEO Title","SEO Description","Google Shopping / Google Product Category","Google Shopping / Gender","Google Shopping / Age Group","Google Shopping / MPN","Google Shopping / AdWords Grouping","Google Shopping / AdWords Labels","Google Shopping / Condition","Google Shopping / Custom Product","Google Shopping / Custom Label 0","Google Shopping / Custom Label 1","Google Shopping / Custom Label 2","Google Shopping / Custom Label 3","Google Shopping / Custom Label 4","Variant Image","Variant Weight Unit","Variant Tax Code","Cost per item","Price / International","Compare At Price / International","Status","Included / Australia","Included / International");
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

          //Image Position = 0
          $a = 0;
    
          // Create handle
          $handle = "";
          if ( strtolower($row[brand]) == "sapphire dreams")
            if ( strtolower($row[type]) == "loose sapphires") { $handle .= $row[shape] . "-" . $row[colour] . "-australian-sapphire-" . $row[sku] . "-melbourne-jewellers";}
            else { $handle .= $row[product_title] . "-" . $row[sku] . "-melbourne-jewellers";}
          elseif ( strtolower($row[brand]) == "argyle origin diamonds" || strtolower($row[brand]) == "argyle pink diamonds") { $handle .= "argyle-pink-diamond-" . $row[shape] . "-" . $row[colour] . "-" . $row[clarity] . "-" . $row[sku] . "-melbourne-jewellers";}
          elseif ( strtolower($row[brand])== "pink kimberley diamonds" || strtolower($row[brand]) == "blush pink diamonds") { $handle .= $row[product_title] . "-" . $row[sku] . "-melbourne-jewellers";}
          else { $handle .= $row[product_title] . "-" . $row[sku] . "-melbourne-jewellers";}
          $handle = str_replace([" ","--"],"-",strtolower($handle));   
          
          //product title
          $title = $row[product_title];
          if ( strtolower($row[brand]) == "sapphire dreams")
            if ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) == "unheated") { $title = "Australian Sapphire ". $row[shape]." 1=".$row[carat]."ct ".$row[colour] . " NH";}
            elseif ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) !== "unheated") { $title = "Australian Sapphire ". $row[shape]." 1=".$row[carat]."ct ".$row[colour]; }
            else { $title = $row[brand] . " " . $row[product_title];}
          if ( $row[collections] == "TPR" || $row[collections] == "TDR") { $title = str_replace("pink diamond","tender diamond", $row[product_title]);}

          //Descriptions, if loose sapphire generate description else import from field description
          if (strtolower($row[type]) == "loose sapphires") 
          if( strtolower($row[treatment]) == "unheated") { $description = "An unheated Australian " .  $row[shape] . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . "."; }
          else {$description = "An Australian " .  $row[shape] . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . ".";  }
          else { $description = $row[description];}

          //Product custom Category for shopify
          $category = "Apparel & Accessories > Jewelry";
          if ( strtolower($row[brand]) == "shopify cl") { $category .= " > Watches";}
          if ( strtolower($row[type]) == "rings") { $category .= " > Rings";}
          if ( strtolower($row[type]) == "earrings") { $category .= " > Earrings";}
          if ( strtolower($row[type]) == "bracelets") { $category .= "";}
          if ( strtolower($row[type]) == "necklaces") { $category .= " > Necklaces";}
          if ( strtolower($row[type]) == "pendants") { $category .= " > Charms & Pendants";}


          // Tags
          $tags = "";
          if ( $row[brand] != "" ) { $tags .= $row[brand].", "; }
          if ( $row[colour] != "" ) { $tags .= $row[colour].", "; }
          if ( $row[shape] != "" ) { $tags .= $row[shape].", "; }
          if ( $row[clarity] != "" ) { $tags .= $row[clarity].", ";}
          if ( $row[type] != "" ) { $tags .= $row[type].", "; }
          if ( $row[metal_composition] != "" ) { $tags .= $row[metal_composition].", "; }
          if ( $row[main_metal] != "" ) { $tags .= $row[main_metal]." Metal, "; }
          if ( $row[treatment] != "" ) { $tags .= $row[treatment].", "; }
          if ( $row[client_tags] != "" ) { $tags .= $row[client_tags].", "; }
          if ( $row[client_jim309_qty] > 0 ) { $tags .= "MELBOURNE_JWLR_STOCK"; }
          if ( $row[client_jim309_qty] <= 0 & $row[client_sgastock] != 1 ) { $tags .= "SGA_STOCK";}

          //if SGA Stock >= 3 qty = 1 else 0
          if ( $row[client_jim309_qty] >= 1 ) { $inventoryQty = $row[client_jim309_qty];}
          else{
            if ( $row[client_sgastock] == 1)
              if ( $row[shopify_qty] >= 3) { $inventoryQty = 1; }
              else { $inventoryQty = 0;}
          }

          //price
          $price = $row[retail_aud];

          //BP & PK > PK
          $brand = str_replace("Blush Pink Diamonds","Pink Kimberley Diamonds", $row[brand]);

          //check Status
          if ( $inventoryQty <= 0) { $status = "draft";} else { $status = "active";} 

          //images alt text
          if (strtolower($row[brand]) == "sapphire dreams") 
            if ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) == "unheated") { $alt_text = $row[carat] . "ct Unheated " . $row[colour] . " " . $row[shape] . " Australian Sapphire - Melbourne Jewellers";}
            elseif ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) !== "unheated") { $alt_text = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Australian Sapphire - Melbourne Jewellers";}
            else { $alt_text =  ucwords($title_mod) . " " . $row[metal_composition] . " Australian " . $row[colour] . " Sapphire " . ucwords($type_mod) . " - Melbourne Jewellers";}
          else {
            if ( strtolower($row[brand]) == "argyle origin diamonds") { $alt_text = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Argyle origin pink diamond - Melbourne Jewellers";}
            elseif ( strtolower($row[brand]) == "argyle pink diamonds") { $alt_text = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Argyle certified pink diamond - Melbourne Jewellers";}
            else { $alt_text = $row[product_title] . " - Melbourne Jewellers";}
          }

          //SEO title
          if (strtolower($row[brand]) == "sapphire dreams") 
            if ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) == "unheated") { $seoTitle = $row[carat] . "ct Unheated " . $row[colour] . " " . $row[shape] . " Australian Sapphire";}
            elseif ( strtolower($row[type]) == "loose sapphires" && strtolower($row[treatment]) !== "unheated") { $seoTitle = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Australian Sapphire";}
            else { $seoTitle =  ucwords($title_mod) . " " . $row[metal_composition] . " Australian " . $row[colour] . " Sapphire " . ucwords($type_mod);}
          else {
            if ( strtolower($row[brand]) == "argyle origin diamonds") { $seoTitle = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Argyle origin pink diamond";}
            elseif ( strtolower($row[brand]) == "argyle pink diamonds") { $seoTitle = $row[carat] . "ct " . $row[colour] . " " . $row[shape] . " Argyle certified pink diamond";}
            else { $seoTitle = $row[product_title] . " set with Argyle pink diamonds";}
          }

         //check images
         $imageURL = "";
         if($row[image1] != "") { $imageURL .= $row[image1].";";}
         if($row[image2] != "") { $imageURL .= $row[image2].";";}
         if($row[image3] != "") { $imageURL .= $row[image3].";";}
         if($row[image4] != "") { $imageURL .= $row[image4].";";}
         if($row[image5] != "") { $imageURL .= $row[image5].";";}
         if($row[image6] != "") { $imageURL .= $row[image6].";";}
         if($row[packaging_image] != "") { $imageURL .= $row[packaging_image];}

         $images = explode(';', rtrim($imageURL, ';'));
         $imagesString = print_r($images, true);
         foreach ($images as $index => $image) {
            $isPositionGreaterThanOne = ($index + 1 > 1);

                $content = array (
                    0 => $handle,
                    1 => $isPositionGreaterThanOne ? "" : $title,
                    2 => $isPositionGreaterThanOne ? "" : $description, 
                    3 => $isPositionGreaterThanOne ? "" : $brand,
                    4 => $isPositionGreaterThanOne ? "" : $category,
                    5 => $isPositionGreaterThanOne ? "" : $row[type],
                    6 => $isPositionGreaterThanOne ? "" : $tags,
                    7 => $isPositionGreaterThanOne ? "" : "FALSE",
                    8 => "",
                    9 => "",
                    10 => "",
                    11 => "",
                    12 => "",
                    13 => "",
                    14 => $isPositionGreaterThanOne ? "" : $row[sku],
                    15 => "",
                    16 => $isPositionGreaterThanOne ? "" : "shopify",
                    17 => $isPositionGreaterThanOne ? "" : $inventoryQty,
                    18 => $isPositionGreaterThanOne ? "" : "deny",
                    19 => $isPositionGreaterThanOne ? "" : "manual",
                    20 => $isPositionGreaterThanOne ? "" : $price,
                    21 => "",
                    22 => $isPositionGreaterThanOne ? "" : "TRUE",
                    23 => $isPositionGreaterThanOne ? "" : "TRUE",
                    24 => "",
                    25 => $image,
                    26 => ++$a,
                    27 => $isPositionGreaterThanOne ? "" : $alt_text,    
                    28 => $isPositionGreaterThanOne ? "" : "FALSE",
                    29 => $isPositionGreaterThanOne ? "" : $seoTitle,
                    30 => $isPositionGreaterThanOne ? "" : $description,
                    31 => "",
                    32 => "",
                    33 => "",
                    34 => "",
                    35 => "",
                    36 => "",
                    37 => "",
                    38 => "",
                    39 => "",
                    40 => "",
                    41 => "",
                    42 => "",
                    43 => "",
                    44 => $isPositionGreaterThanOne ? "" : $row[image1],
                    45 => "",
                    46 => "",
                    47 => "",
                    48 => "",
                    49 => "",
                    50 => $isPositionGreaterThanOne ? "" : $status,
                    51 => $isPositionGreaterThanOne ? "" : "TRUE",
                    52 => $isPositionGreaterThanOne ? "" : "TRUE",
            
                );
                fputcsv($fp, $content);
        }
    }      
  

  fclose($fp);
  $count = mysqli_num_rows($result) -1;
  date_default_timezone_set('Australia/Sydney');
  echo "<h2>JIM309: Melbourne Jewellers Shopify Products Import CSV Exported!</h2><br>";
  echo "Total Products Exported to CSV: ".$count."<br><br>";
  echo "<a style='font-weight:bold;' href='https://pim.samsgroup.info/client_export/jim309_product_import.csv'>View on Web</a><br><br>";
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