
<html>
<head>
  <title>Sync Status</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <style>
body { font-family: 'Open Sans', sans-serif; }
table { border: 2px solid black; margin: 0px auto; background-color:#F9F6F0; width:30%; text-align:center; border-collapse:collapse;}
td { border: 1px solid black; padding: 20px; width:50%;}
</style>
</head>
<body>
<?php
include('connect.php');
date_default_timezone_set('Australia/Sydney');
echo date("Y-m-d G:i a");

function updateSync($con, $query, $message)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    echo "<div><span style='font-size:18px;'>$message</span>";
    echo "<td style='font-size:14px; color:grey;'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET sync_shopify = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) 
                die(mysqli_error($con));
            } 
            echo $sku;
    }echo "</div></td>";

function updateRetailExclusive($con, $query, $categoryValue)
{
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    echo "<div><span style='font-size:18px;'>$categoryValue</span>";
    echo "<td style='font-size:14px; color:grey;'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];
        $sql = "UPDATE pim SET retail_exclusive = 1 WHERE sku = '{$sku}'";
        $result_update = mysqli_query($con, $sql);
            if (!$result_update) {
                die(mysqli_error($con));
            } 
            echo $sku;
    }echo "</div></td>";
}
echo "<center><h1 style='font-family:'Open Sans',sans-serif; font-weight:normal; padding:20px;'>Sync Shopify Updated 0 => 1</h1></center><table><tr><td>";

// Update sync_shopify for SD Jewellery
$sdJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "sapphire dreams" && collections_2 <> "steve" && collections_2 <> "discontinued" && collections_2 <> "wholesale_only" && collections <> "sds" && collections <> "melee")'; 
updateSync($con, $sdJewelleryQuery, 'SD Jewellery :');
echo "</td></tr><tr><td>";

// Update sync_shopify for SD Stones
$sdStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && sync_shopify <> 1 && brand = "sapphire dreams" && type LIKE "%loose%")';
updateSync($con, $sdStonesQuery, 'SD Stones :');
echo "</td></tr><tr><td>";

// Update sync_shopify for PK Jewellery
$pkJewelleryQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "pink kimberley diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && brand = "blush pink diamonds")';
updateSync($con, $pkJewelleryQuery, 'PK Jewellery :');
echo "</td></tr><tr><td>";

// Update sync_shopify for PK Stones
$pkStonesQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle pink diamonds") OR (image1 <> "" && image1 IS NOT NULL && description <> "" && description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && sync_shopify <> 1 && collections <> "melee" && brand = "argyle origin diamonds")';
updateSync($con, $pkStonesQuery,'PK Stones :');
echo "</td></tr><tr><td>";

// Update sync_shopify for Wholesale
$sgaWholesaleQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && description <> "" & description IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && collections <> "SDL" && sync_shopify <> 1 && brand <> "classique watches" && brand <> "shopify cl" && brand <> "sapphire dreams" && brand <> "pink kimberley diamonds" && brand <> "blush pink diamonds" && brand <> "argyle pink diamonds" && brand <> "argyle origin diamonds")';
updateSync($con, $sgaWholesaleQuery,'SGA Wholesale:');

echo "</td></tr></table><h1 style='font-family:'Open Sans',sans-serif; font-weight:normal; padding:20px;'>Retail Exclusive (SD Stones Only) 0 => 1</h1><table><tr><td>";

// Update retail_exclusive status for SD Stones, jewellery is manual therefore excluded
$sdsOnlyQuery = 'SELECT * FROM pim WHERE (image1 <> "" && image1 IS NOT NULL && retail_aud > 0 && shopify_qty > 0 && collections <> "melee" && brand = "sapphire dreams" && type LIKE "%loose%" && retail_exclusive <> 1)'; 
updateRetailExclusive($con, $sdsOnlyQuery, 'SDS stones :');
echo "</td></tr></table><br><br><br>";

mysqli_close($con);

?>
</body>
</html>

