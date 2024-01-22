<?php

$sku = $getData[0];
$brand = $getData[26];
if($getData[97] == "RED:PURPLISH RED"){$colour = "pRed";} else { $colour = $getData[97]; }
if( preg_match("/NH/i", $colour) > 0 ) { $colour = str_replace("NH","",$colour); $colour = rtrim($colour);}
$clarity = $getData[98];
$centreSKU = $getData[101];
$carat = $getData[102];
$shape = $getData[99];
$measurement = $getData[104];
$type = $getData[22];
$collections = $getData[25];
$mainmetal = $getData[92];
$metalcomposition = $getData[93];
$centerstonesku = $getData[101];
$centrestoneqty = $getData[103];
$allocatedQty = $getData[136];
$cert_availability = $getData[100];

//Prices
$purchasecostAUD = $getData[2];
$purchasecostUSD = $getData[1];
$manufacturingcostAUD = $getData[4];
$wholesaleAUD = $getData[6];
$wholesaleUSD = $getData[5];
$retailAUD = $getData[10];
$retailUSD = $getData[9];
$stonepricewholesaleaud = "";
$stonepriceretailaud = "";
if(strtolower($type) == "loose sapphires" && strtolower($collections) !== "melee"){ $stonepricewholesaleaud = $carat * $wholesaleAUD; $stonepriceretailaud = round($carat * $retailAUD); }
if(strtolower($type) == "loose diamonds" && strtolower($collections) !== "melee") { $stonepricewholesaleaud = $carat * $wholesaleAUD; $stonepriceretailaud = round($carat * $retailAUD); }


//Quantity
$masterqty = $getData[126];
$warehouseqty = $getData[127];
$mdqty = $getData[129];
$psqty = $getData[131];
$usdqty = $getData[135];

//Custom Fields
$edl1 = $getData[11];
$edl2 = $getData[12];
$edl3 = $getData[13];
$edl4 = $getData[14];
$edl5 = $getData[15];
$edl6 = $getData[16];
$edl7 = $getData[17];
$edl8 = $getData[18];
$edl9 = $getData[19];

//Product Title
$productTitle = "";
if (strtolower($brand) == "pink kimberley diamonds" || strtolower($brand) == "blush pink diamonds" ){ $productTitle .= mb_convert_case($getData[19], MB_CASE_TITLE); }
elseif ( strtolower($brand) == "sapphire dreams" && strtolower($type) != "loose sapphires" ) { $productTitle .= mb_convert_case($getData[19], MB_CASE_TITLE); }
elseif ( strtolower($brand) == "sapphire dreams" && strtolower($type) == "loose sapphires" ) {
  if(substr($sku,0,3)=="SDM") {$productTitle .="Australian Sapphire Melee ".$colour." ".$shape." ".$measurement;}
  else{$productTitle .= "Australian Sapphire ".$shape." ".$centrestoneqty." ".$colour;}
}
elseif(strtolower($type) == "loose diamonds"){
  if (preg_match("/argyle/i", $brand) > 0) { $productTitle .= $getData[19]; if (substr($sku,0,3)=="AWD"){$productTitle .=" ".$measurement;}}
}
elseif ( strtolower($brand) == "classique watches" ) { $productTitle .= $getData[34]; }
else { $productTitle .= mb_convert_case($getData[19], MB_CASE_TITLE); }

//Treatments
$treatment="";
if ( preg_match("/NH/i", $getData[97]) > 0 ) { $treatment .= "Unheated"; }

// Shopify Qty Calculations
$shopifyqty = 0; $whshopify = 0; $mdshopify = 0; $psshopify = 0; $allocatedshopify = 0;
if (preg_match("/loose/i", strtolower($type)) > 0){
  if ( $mdqty == 0 ) { $mdshopify = 0; } else { $mdshopify = 1; }
  if ( $psqty == 0 ) { $psshopify = 0; } else { $psshopify = 1; }
  if ( $warehouseqty == 0 ) { $whshopify = 0; } else { $whshopify = 1; }
  if ( $allocatedQty == 0 ) { $allocatedshopify = 0; } else { $allocatedshopify = 1; }
  if (strtolower($brand) == "sapphire dreams")
  {
    $shopifyqty = $whshopify + $mdshopify + $psshopify;
    $shopifyqty = $shopifyqty - $allocatedshopify;
  }
  else{
    $shopifyqty = $whshopify + $mdshopify + $psshopify;
  } 
}
else{
  if ( $warehouseqty > 0 && $warehouseqty <= 1 ) { $whshopify = 1; } elseif ( $warehouseqty > 1 ) { $whshopify = $warehouseqty; }
  if ( $mdqty > 0 && $mdqty <= 1 ) { $mdshopify = 1; } elseif ( $mdqty > 1 ) { $mdshopify = $mdqty; }
  if ( $psqty > 0 && $psqty <= 1 ) { $psshopify = 1; } elseif ( $psqty > 1 ) { $psshopify = $psqty; }
  if (strtolower($brand) == "sapphire dreams")
  {
    $shopifyqty = $whshopify + $mdshopify + $psshopify;
    $shopifyqty = $shopifyqty - $allocatedQty;
  }
  else{
    $shopifyqty = $whshopify + $mdshopify + $psshopify;
  }
}


//Specifications
$jewellerybrands = array("pink kimberley diamonds", "blush pink diamonds", "semi precious jewellery", "white diamond jewellery", "yellow diamond jewellery");
$specifications = "";
if ( in_array(strtolower($brand),$jewellerybrands) ) {
  for ($i=11; $i <= 18; $i++){
    if ($getData[$i] !== ''){
      if (preg_match("/argyle lot/i", strtolower($getData[$i])) > 0){}
      else {
        $specifications .= $getData[$i]."<br>";}
      }
    }
}
elseif ( strtolower($brand) == "sapphire dreams" && strtolower($type) != "loose sapphires" ) {
  for ($i=11; $i <= 18; $i++){if ($getData[$i] !== ''){$specifications .= $getData[$i]."<br>";}}
}
elseif ( strtolower($brand) == "sapphire dreams" && strtolower($type) == "loose sapphires" ) {
  $measurements = "";
  if ($cert_availability == "YES") { $measurements = "<br>Certification: ".$edl2; }
  if (strtolower($edl3) == "nh") { $comment .= "<br>An unheated natural Australian sapphire.";}
  $specifications .= "Shape: ".$shape."<br>Carat Weight: ".$carat."ct<br>Colour: ".$colour."<br>Measurement: ".$measurement.$measurements.$comment;
  $comment = "";
}
elseif ( strtolower($type) == "loose diamonds" && preg_match("/argyle/i", strtolower($brand)) > 0 ) {
  $colourchoice = "";
  if(preg_match("/origin/i", strtolower($brand)) > 0) { $colourchoice .= "Argyle Equivalent Colour: ";}
  else { $colourchoice .= "Argyle Colour: ";}
  $specifications .= "Shape: ".$shape."<br>Carat Weight: ".$carat."ct<br>".$colourchoice.$colour."<br>Clarity: ".$clarity."<br>Measurement: ".$measurement."<br>Certification: ".$edl2;
}
if ( preg_match("/NH/i", $specifications) > 0 ) { $specifications = str_replace("NH ","",$specifications); }


// images
$image1 = "";
$image2 = "";
$image3 = "";
$image4 = "";
$image5 = "";
$image6 = "";
$packagingimg = "";
$imgURL = 'https://samsgroup.info/pim-images/'.$sku;
$imgFile = $_SERVER['DOCUMENT_ROOT'].'-images/'.$sku;
if ( file_exists($imgFile.".jpg") ){$image1 .= $imgURL.".jpg";}
if ( file_exists($imgFile."_2.jpg") ){$image2 .= $imgURL."_2.jpg";}
if ( file_exists($imgFile."_3.jpg") ){$image3 .= $imgURL."_3.jpg";}
if ( file_exists($imgFile."_4.jpg") ){$image4 .= $imgURL."_4.jpg";}
if ( file_exists($imgFile."_5.jpg") ){$image5 .= $imgURL."_5.jpg";}
if ( file_exists($imgFile."_6.jpg") ){$image6 .= $imgURL."_6.jpg";}

if (strtolower($brand) == "pink kimberley diamonds"){
  if ( strpos(strtolower($specifications),"cert") != false ) { $packagingimg .= "https://samsgroup.info/pim-images/pk-box-cert.jpg"; }
  else { $packagingimg .= "https://samsgroup.info/pim-images/pk-box.jpg"; }
}
elseif (strtolower($brand) == "blush pink diamonds"){
  if ( strpos(strtolower($specifications),"cert") != false ) { $packagingimg .= "https://samsgroup.info/pim-images/bp-box-cert.jpg"; }
  else { $packagingimg .= "https://samsgroup.info/pim-images/bp-box.jpg"; }
}
elseif (strtolower($brand) == "sapphire dreams" && strtolower($type) != "loose sapphires"){
  if ( strtolower($type) == "bracelets" && preg_match("/9ct/i", $metalcomposition) > 0) { $packagingimg .= "https://samsgroup.info/pim-images/sdb-9-box-card.jpg"; }
  elseif ( strtolower($type) == "earrings" && preg_match("/9ct/i", $metalcomposition) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sde-9-box-card.jpg"; }
  elseif ( strtolower($type) == "earrings" && preg_match("/18ct/i", $metalcomposition) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sde-18-box-card.jpg"; }
  elseif ( strtolower($type) == "necklaces" && preg_match("/9ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdp-9-box-card-cert.jpeg"; }
  elseif ( strtolower($type) == "necklaces" && preg_match("/9ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) == 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdp-9-box-card.jpg"; }
  elseif ( strtolower($type) == "necklaces" && preg_match("/18ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdp-18-box-card-cert.jpg"; }
  elseif ( strtolower($type) == "necklaces" && preg_match("/18ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) == 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdp-18-box-card.jpg"; }
  elseif ( strtolower($type) == "rings" && preg_match("/9ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdr-9-box-card-cert.jpg"; }
  elseif ( strtolower($type) == "rings" && preg_match("/9ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) == 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdr-9-box-card.jpg"; }
  elseif ( strtolower($type) == "rings" && preg_match("/18ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) > 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdr-18-box-card-cert.jpg"; }
  elseif ( strtolower($type) == "rings" && preg_match("/18ct/i", $metalcomposition) > 0 && preg_match("/cert/i", $specifications) == 0 ) { $packagingimg .= "https://samsgroup.info/pim-images/sdr-18-box-card.jpg"; }
}
elseif (strtolower($brand) == "sapphire dreams" && strtolower($type) == "loose sapphires"){
  if ($carat < 2) { $packagingimg .= "https://samsgroup.info/pim-images/sds-box-cert-sml.jpg"; }
  else { $packagingimg .= "https://samsgroup.info/pim-images/sds-box-cert.jpg"; }
}
elseif (preg_match("/argyle/i", $brand) > 0){
  if (preg_match("/mel/i", $sku) > 0) {  }
  else { $packagingimg .= "https://samsgroup.info/pim-images/pd-box-cert.jpg"; }
}


//START LOGGING
//check price changes 
$searchprice = "SELECT sku, wholesale_aud, stone_price_wholesale_aud, retail_aud, stone_price_retail_aud from pim where sku = '$sku'";
$resultprice = mysqli_query($con,$searchprice) or die(mysqli_error($con));
while ($rows = mysqli_fetch_array($resultprice, MYSQLI_ASSOC)) {
  if(strtolower($type) == "loose sapphires" || strtolower($type) == "loose diamonds") {
    if ($rows[stone_price_wholesale_aud] != round($stonepricewholesaleaud,2)) { $logsku = $sku; $logheader = "stone_price_wholesale_aud"; $newrecord = $stonepricewholesaleaud; $username = "autoimport"; include 'log.php'; }
    if ($rows[stone_price_retail_aud] != round($stonepriceretailaud,2)) { $logsku = $sku; $logheader = "stone_price_retail_aud"; $newrecord = $stonepriceretailaud; $username = "autoimport"; include 'log.php'; }
  }
  if ($rows[wholesale_aud] != $wholesaleAUD) { $logsku = $sku; $logheader = "wholesale_aud"; $newrecord = $wholesaleAUD; $username = "autoimport"; include 'log.php'; }
  if ($rows[retail_aud] != $retailAUD) { $logsku = $sku; $logheader = "retail_aud"; $newrecord = $retailAUD; $username = "autoimport"; include 'log.php'; }
}

//check title changes - if title is blank - skip log for now
$searchtitle = "SELECT sku, product_title, brand from pim where sku = '$sku'";
$resulttitle = mysqli_query($con,$searchtitle) or die(mysqli_error($con));
while ($rows = mysqli_fetch_array($resulttitle, MYSQLI_ASSOC)) {
  if ($productTitle != ""){ 
    if ($rows[product_title] != $productTitle) { $logsku = $sku; $logheader = "product_title"; $newrecord = $productTitle; $username = "autoimport"; include 'log.php'; }
  }
}

//check colour changes
$searchcolour = "SELECT sku, colour from pim where sku = '$sku'";
$resultcolour = mysqli_query($con,$searchcolour) or die(mysqli_error($con));
while ($rows = mysqli_fetch_array($resultcolour, MYSQLI_ASSOC)) {
  if($getData[97] != "RED:PURPLISH RED") {
    if ($rows[colour] != $colour) { $logsku = $sku; $logheader = "colour"; $newrecord = $colour; $username = "autoimport"; include 'log.php'; }
  }
}

//check qty changes
/*$searchqty = "SELECT sku, warehouse_qty, mdqty, psqty, usdqty, allocated_qty from pim where sku = '$sku'";
$resultqty = mysqli_query($con,$searchqty) or die(mysqli_error($con));
while ($rows = mysqli_fetch_array($resultqty, MYSQLI_ASSOC)) {
  if ($rows[warehouse_qty] != round($warehouseqty,2)) { $logsku = $sku; $logheader = "warehouse_qty"; $newrecord = $warehouseqty; $username = "autoimport"; include 'log.php'; }
  if ($rows[mdqty] != round($mdqty,2)) { $logsku = $sku; $logheader = "mdqty"; $newrecord = $mdqty; $username = "autoimport"; include 'log.php'; }
  if ($rows[psqty] != round($psqty,2)) { $logsku = $sku; $logheader = "psqty"; $newrecord = $psqty; $username = "autoimport"; include 'log.php'; }
  if ($rows[usdqty] != round($usdqty,2)) { $logsku = $sku; $logheader = "usdqty"; $newrecord = $usdqty; $username = "autoimport"; include 'log.php'; }
  if ($rows[allocated_qty] != round($allocatedQty,2)) { $logsku = $sku; $logheader = "allocated_qty"; $newrecord = round($allocatedQty,2); $username = "autoimport"; include 'log.php'; }
}*/
 ?>
