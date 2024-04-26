
<html>
<head>
  <title>Sync Status</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <style>
body { font-family: 'Open Sans', sans-serif;margin-left: 20px;font-weight:normal; font-size:12px;}
</style>
</head>
<body>
<?php
include('connect.php');
date_default_timezone_set('Australia/Sydney');
echo date("Y-m-d G:i a")."<br><br>";

function updateSync($con, $query)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET sync_shopify = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            echo "<strong>".$sku."</strong> sync_shopify status updated to 1<br>";
    }
}
function updateRetailExclusiveOne($con, $query)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET retail_exclusive = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            "<strong>".$sku."</strong> retail_exclusive status updated to 1<br>";
    }
}

function updateRetailExclusiveZero($con, $query)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET retail_exclusive = 0 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            echo "<strong>".$sku."</strong> retail_exclusive status updated to 0<br>";
    }
}

// Update sync_shopify for SD Jewellery
$sdJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "sapphire dreams" && collections_2 <> "steve" && collections_2 <> "discontinued" && collections_2 <> "wholesale_only" && collections <> "sds" && collections <> "melee")'; 
updateSync($con, $sdJewelleryQuery);

// Update sync_shopify for SD Stones
$sdStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && sync_shopify <> 1 && brand = "sapphire dreams" && type LIKE "%loose%")';
updateSync($con, $sdStonesQuery);

// Update sync_shopify for PK Jewellery
$pkJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "pink kimberley diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "blush pink diamonds")';
updateSync($con, $pkJewelleryQuery);

// Update sync_shopify for PK Stones
$pkStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle pink diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle origin diamonds")';
updateSync($con, $pkStonesQuery);

// Update sync_shopify for Wholesale
$sgaWholesaleQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" & description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && collections <> "SDL" && sync_shopify <> 1 && brand <> "classique watches" && brand <> "shopify cl" && brand <> "sapphire dreams" && brand <> "pink kimberley diamonds" && brand <> "blush pink diamonds" && brand <> "argyle pink diamonds" && brand <> "argyle origin diamonds")';
updateSync($con, $sgaWholesaleQuery);

// Update retail_exclusive status to true for all SD stones only, jewellery is manual therefore excluded
//$sdsOnlyQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && brand = "sapphire dreams" && type LIKE "%loose%" && retail_exclusive <> 1)'; 
//updateRetailExclusiveOne($con, $sdsOnlyQuery);

// Turn off retail exclusive true to false for all SD Jewellery items that are shopify qty <= 0 and appro qty is > 0
$sdjQuery = 'SELECT * FROM pim WHERE (brand = "sapphire dreams" AND shopify_qty <= 0 AND retail_exclusive = 1 AND allocated_qty > 0)';
updateRetailExclusiveZero($con, $sdjQuery);

mysqli_close($con);

?>
</body>
</html>

