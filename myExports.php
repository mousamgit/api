<?php
include 'login_checking.php';
    include 'functions.php';
?>
<?php include 'header.php'; ?>
<?php
date_default_timezone_set('Australia/Sydney');
$timestamp = date("Y-m-d G:i a");

echo "<h1 style=\"font-family:'Open Sans',sans-serif; color: #c72c2c;padding-top:20px;padding-bottom:10px;font-weight:bold;\">Export Templates</h1>";

$scriptsToRun = [
    'Daily Export' => 'http://pim.samsgroup.info/daily_export.php',
    'Hubspot' => 'http://pim.samsgroup.info/export_hubspot.php',
    'Rephopper' => 'http://pim.samsgroup.info/export_rephopper.php',
    'Shopify Sapphire Dreams' => 'http://pim.samsgroup.info/export_sd_shopify.php',
    'Shopify Pink Kimberley' => 'http://pim.samsgroup.info/export_pk_shopify.php',
    'Shopify SGA Wholesale' => 'http://pim.samsgroup.info/export_sga_shopify.php',
    'Shopify Classique Watches' => 'http://pim.samsgroup.info/export_cl_shopify.php',
    'Shopify Client JIM077 Products Import' => 'http://pim.samsgroup.info/client_jim077_export_shopify.php',
    'Shopify Client JIM309 Products Import' => 'http://pim.samsgroup.info/client_jim309_export_shopify.php',
    'Shopify Client JIM077 Inventory Import' => 'http://pim.samsgroup.info/client_jim077_qty_shopify.php',
    'Shopify Client JIM309 Inventory Import' => 'http://pim.samsgroup.info/client_jim309_qty_shopify.php',
    'Sirv Certificate' => 'http://pim.samsgroup.info/sirv.php',
    
];

function printScriptURL($scriptName, $scriptURL) {
    echo "<div style='font-family:\"Open Sans\", sans-serif;'>";
    echo "<span style='color:black; font-size:18px; font-weight:bold;'>".$scriptName."</span>";
    echo "<a style='text-decoration:none; font-size:10px; color:#a856f5;' href='" . $scriptURL . "' target='_blank'>&nbsp;&nbsp;Export</a><br><br>";
    echo"</div>";
}

foreach ($scriptsToRun as $scriptName => $scriptURL) {
    printScriptURL($scriptName, $scriptURL);
}

echo "<h1 style=\"font-family:'Open Sans',sans-serif; color: #c72c2c;padding-top:20px;padding-bottom:10px;font-weight:bold;\">Download Export</h1>";
$csvFiles = [
    'Daily Export' => 'https://samsgroup.info/export/daily-export.csv',
    'Hubspot' => 'https://samsgroup.info/export/hubspot.csv',
    'Rephopper' => 'https://pim.samsgroup.info/rephopper/rephopper.csv',
    'Shopify Sapphire Dreams' => 'https://samsgroup.info/export/sd-shopify.csv',
    'Shopify Pink Kimberley' => 'https://samsgroup.info/export/pk-shopify.csv',
    'Shopify SGA Wholesale' => 'https://samsgroup.info/export/sga-shopify.csv',
    'Shopify Classique Watches' => 'https://samsgroup.info/export/cl-shopify.csv',
    'Shopify Client JIM077 Products Import' => 'https://pim.samsgroup.info/client_export/jim077_product_import.csv',
    'Shopify Client JIM309 Products Import' => 'https://pim.samsgroup.info/client_export/jim309_product_import.csv',
    'Shopify Client JIM077 Inventory Import' => 'https://pim.samsgroup.info/client_export/jim077_inventory_import.csv',
    'Shopify Client JIM309 Inventory Import' => 'https://pim.samsgroup.info/client_export/jim309_inventory_import.csv',
    'Sirv Certificate' => 'https://pim.samsgroup.info/sirv/sirv.xml',

];

foreach ($csvFiles as $csvName => $csvURL) {
    if (strpos($csvURL,"csv") !== false) { $downloadType = "CSV Download";}
    elseif (strpos($csvURL,"xml") !== false) { $downloadType = "XML Download";}
    else { $downloadType = "Unkown Download Type";}
    echo "<div style='font-family:\"Open Sans\", sans-serif;'>";
    echo "<span style='color:black; font-size:18px; font-weight:bold;'>".$csvName."</span>";
    echo '<a style="text-decoration:none; font-size:10px; color:#a856f5;" href="downloadExports.php?csv=' . urlencode($csvURL) . '">&nbsp;&nbsp;' . $downloadType . '</a><br><br>';
    echo"</div>";
}

?>