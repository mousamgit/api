<?php

$type = $getData[22]; $lowertype = strtolower($type);

$sku = $getData[0];
$productTitle = mb_convert_case($getData[19], MB_CASE_TITLE);
$brand = $getData[26];
$colour = mb_convert_case($getData[97], MB_CASE_TITLE);
$clarity = $getData[98];
$centreSKU = $getData[101];
$carat = $getData[102];
if ($lowertype == "loose sapphires") { $measurement .= $getData[16]; }else{ $measurement .= $getData[104]; }
if ($lowertype == "loose sapphires") { $cert .= $getData[12]; }
$purchasecostAUD = $getData[2];
$purchasecostUSD = $getData[1];
$manufacturingcostAUD = $getData[4];
$wholesaleAUD = $getData[6];
$wholesaleUSD = $getData[5];
$retailAUD = $getData[10];
$retailUSD = $getData[9];
$masterqty = $getData[126];
$warehouseqty = $getData[127];
$mdqty = $getData[129];
$psqty = $getData[131];
$usdqty = $getData[135];
$shape = $getData[99]; 

$stonepricewholesaleAUD = $carat * $wholesaleAUD;
$stonepriceretailAUD = $carat * $retailAUD;

// Shopify Qty Calculations
$shopifyqty = 0;
$whshopify = 0;
$mdshopify = 0;
$psshopify = 0;
if ( $warehouseqty > 0 && $warehouseqty <= 1 ) { $whshopify .= 1; } elseif ( $warehouseqty > 1 ) { $whshopify .= $warehouseqty; }
if ( $mdqty > 0 && $mdqty <= 1 ) { $mdshopify .= 1; } elseif ( $mdqty > 1 ) { $mdshopify .= $mdqty; }
if ( $psqty > 0 && $psqty <= 1 ) { $psshopify .= 1; } elseif ( $psqty > 1 ) { $psshopify .= $psqty; }
if ( $warehouseqty > 0 || $mdqty > 0 || $psqty > 0 ){ $shopifyqty .= $whshopify + $mdshopify + $psshopify; }

// Specifications
if ($lowertype == "loose sapphires"){ $specifications .= "Shape: ".$shape."<br>Carat Weight: ".$carat."ct<br>Colour: ".$colour."<br>Measurement: ".$measurement."<br>Certification: ".$cert."";}
else { for ($i=11; $i <= 18; $i++){if ($getData[$i] !== ''){$specifications .= $getData[$i]."<br>";}} }

// Images
$imgURL = 'https://samsgroup.info/pim-images/'.$sku;
$imgFile = $_SERVER['DOCUMENT_ROOT'].'/pim-images/'.$sku;
if ( file_exists($imgFile.".jpg") ){$image1 .= $imgURL.".jpg";}
if ( file_exists($imgFile."_2.jpg") ){$image2 .= $imgURL."_2.jpg";}
if ( file_exists($imgFile."_3.jpg") ){$image3 .= $imgURL."_3.jpg";}
if ( file_exists($imgFile."_4.jpg") ){$image4 .= $imgURL."_4.jpg";}
if ( file_exists($imgFile."_5.jpg") ){$image5 .= $imgURL."_5.jpg";}
if ( file_exists($imgFile."_6.jpg") ){$image6 .= $imgURL."_6.jpg";}
if ( strpos(strtolower($specifications),"cert") != false ) {
  if ($lowertype == "bracelets") { $packagingimg .= "https://samsgroup.info/pim-images/pk-box-cert.jpg"; }
} else {
  $packagingimg .= "https://samsgroup.info/pim-images/pk-box.jpg";
}


if ($lowertype == "loose sapphires")
{
  $sql = " INSERT into pim (sku,product_title,brand,specifications,type,colour,clarity,carat,measurement,purchase_cost_aud,purchase_cost_usd,manufacturing_cost_aud,wholesale_aud,wholesale_usd,retail_aud,retail_usd,master_qty,warehouse_qty,mdqty,psqty,usdqty,image1,image2,image3,image4,stone_price_wholesale_aud,stone_price_retail_aud,shape,image5,image6,packaging_image)
       VALUES ('".$sku."','".$productTitle."','".$brand."','".$specifications."','".$type."','".$colour."','".$clarity."','".$carat."','".$measurement."','".$purchasecostAUD."','".$purchasecostUSD."','".$manufacturingcostAUD."','".$wholesaleAUD."','".$wholesaleUSD."','".$retailAUD."','".$retailUSD."','".$masterqty."','".$warehouseqty."','".$mdqty."','".$psqty."','".$usdqty."','".$image1."','".$image2."','".$image3."','".$image4."','".$stonepricewholesaleAUD."','".$stonepriceretailAUD."','".$shape."','".$image5."','".$image6."','".$packagingimg."')
       ON DUPLICATE KEY UPDATE
       sku='".$sku."',product_title='".$productTitle."',brand='".$brand."',specifications='".$specifications."',type='".$type."',colour='".$colour."',clarity='".$clarity."',carat='".$carat."',measurement='".$measurement."',purchase_cost_aud='".$purchasecostAUD."',purchase_cost_usd='".$purchasecostUSD."',manufacturing_cost_aud='".$manufacturingcostAUD."',wholesale_aud='".$wholesaleAUD."',wholesale_usd='".$wholesaleUSD."',retail_aud='".$retailAUD."',retail_usd='".$retailUSD."',master_qty='".$masterqty."',warehouse_qty='".$warehouseqty."',mdqty='".$mdqty."',psqty='".$psqty."',usdqty='".$usdqty."',image1='".$image1."',image2='".$image2."',image3='".$image3."',image4='".$image4."',stone_price_wholesale_aud='".$stonepricewholesaleAUD."',stone_price_retail_aud='".$stonepriceretailAUD."',shape='".$shape."',image5='".$image5."',image6='".$image6."',packaging_image='".$packagingimg."'";
       $result = mysqli_query($con, $sql);
}else {
  $sql = " INSERT into pim (sku,product_title,brand,specifications,type,colour,clarity,carat,measurement,purchase_cost_aud,purchase_cost_usd,manufacturing_cost_aud,wholesale_aud,wholesale_usd,retail_aud,retail_usd,master_qty,warehouse_qty,mdqty,psqty,usdqty,image1,image2,image3,image4,shape,image5,image6,packaging_image)
       VALUES ('".$sku."','".$productTitle."','".$brand."','".$specifications."','".$type."','".$colour."','".$clarity."','".$carat."','".$measurement."','".$purchasecostAUD."','".$purchasecostUSD."','".$manufacturingcostAUD."','".$wholesaleAUD."','".$wholesaleUSD."','".$retailAUD."','".$retailUSD."','".$masterqty."','".$warehouseqty."','".$mdqty."','".$psqty."','".$usdqty."','".$image1."','".$image2."','".$image3."','".$image4."','".$shape."','".$image5."','".$image6."','".$packagingimg."')
       ON DUPLICATE KEY UPDATE
       sku='".$sku."',product_title='".$productTitle."',brand='".$brand."',specifications='".$specifications."',type='".$type."',colour='".$colour."',clarity='".$clarity."',carat='".$carat."',measurement='".$measurement."',purchase_cost_aud='".$purchasecostAUD."',purchase_cost_usd='".$purchasecostUSD."',manufacturing_cost_aud='".$manufacturingcostAUD."',wholesale_aud='".$wholesaleAUD."',wholesale_usd='".$wholesaleUSD."',retail_aud='".$retailAUD."',retail_usd='".$retailUSD."',master_qty='".$masterqty."',warehouse_qty='".$warehouseqty."',mdqty='".$mdqty."',psqty='".$psqty."',usdqty='".$usdqty."',image1='".$image1."',image2='".$image2."',image3='".$image3."',image4='".$image4."',shape='".$shape."',image5='".$image5."',image6='".$image6."',packaging_image='".$packagingimg."'";
       $result = mysqli_query($con, $sql);
}

$specifications = "";
$shopifyqty = 0;
$whshopify = 0;
$mdshopify = 0;
$psshopify = 0;
$image1 = "";
$image2 = "";
$image3 = "";
$image4 = "";
$image5 = "";
$image6 = "";
$packagingimg = "";

?>
