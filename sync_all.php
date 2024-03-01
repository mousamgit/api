
<?php
include('connect.php');
date_default_timezone_set('Australia/Sydney');
echo date("Y-m-d G:i a");

function updateSync($con, $query, $message)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    echo "<table style=\"border:2px solid #EBF6FF; font-family:'Open Sans',sans-serif; width:50%; margin:0 auto; background-color: #EBF6FF; padding:10px;font-size:20px;\">";
    echo "<tr><th style=\"color:#226BA8;\">{$message}</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET sync_shopify = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            echo "<tr><td style=\"color:#7F878D;font-weight:normal;font-size:14px;text-align:center;\">{$sku}</td></tr>";
    }
    echo "</table><br>";
}

function updateRetailExclusive($con, $query, $categoryValue)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    echo "<table style=\"border:2px solid #F3FAC1; font-family:'Open Sans',sans-serif; width:50%; margin:0 auto; background-color: #F3FAC1; padding:10px;font-size:20px;\">";
    echo "<tr><th style=\"color:#5c8a33;\">{$categoryValue}</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET retail_exclusive = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            echo "<tr><td style=\"color:#7F878D;font-weight:normal;font-size:14px;text-align:center;\">{$sku}</td></tr>";
    }
    echo "</table><br>";
}


echo "<h1 style=\"text-align:center; font-family:'Open Sans',sans-serif; color: #666666;padding-top:20px;padding-bottom:10px;font-weight:bold;\">Sync Updated 0 => 1</h1>";

// Update sync_shopify for SD Jewellery
$sdJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "sapphire dreams" && collections_2 <> "steve" && collections_2 <> "discontinued" && collections_2 <> "wholesale_only" && collections <> "sds" && collections <> "melee")'; 
updateSync($con, $sdJewelleryQuery, 'SD Jewellery:');

// Update sync_shopify for SD Stones
$sdStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && sync_shopify <> 1 && brand = "sapphire dreams" && type LIKE "%loose%")';
updateSync($con, $sdStonesQuery, 'SD Stones:');

// Update sync_shopify for PK Jewellery
$pkJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "pink kimberley diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "blush pink diamonds")';
updateSync($con, $pkJewelleryQuery, 'PK Jewellery:');

// Update sync_shopify for PK Stones
$pkStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle pink diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle origin diamonds")';
updateSync($con, $pkStonesQuery,'PK Stones:');

// Update sync_shopify for Wholesale
$sgaWholesaleQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" & description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && collections <> "SDL" && sync_shopify <> 1 && brand <> "classique watches" && brand <> "shopify cl" && brand <> "sapphire dreams" && brand <> "pink kimberley diamonds" && brand <> "blush pink diamonds" && brand <> "argyle pink diamonds" && brand <> "argyle origin diamonds")';
updateSync($con, $sgaWholesaleQuery,'SGA Wholesale:');

echo "<h1 style=\"text-align:center; font-family:'Open Sans',sans-serif; color: #666666;padding-top:20px;padding-bottom:10px;font-weight:bold;\">Retail Exclusive (SD Stones Only) 0 => 1</h1>";

// Update retail_exclusive status for SD Stones, jewellery is manual therefore excluded
$sdsOnlyQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && brand = "sapphire dreams" && type LIKE "%loose%" && retail_exclusive <> 1)'; 
updateRetailExclusive($con, $sdsOnlyQuery, 'SDS stones:');


mysqli_close($con);

?>

