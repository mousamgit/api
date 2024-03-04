<?php
  include 'login_checking.php';
  include 'functions.php';
?>
<html>
<head>
  <title>Export Templates</title>
  <?php include 'header.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/dancss.css">
  <style>
body { font-family: 'Open Sans', sans-serif; }
table { border: 2px solid #000; margin: 0px auto; background-color:#F9F6F0; }
td { font-weight: 400; border: 1px solid #000; padding: 15px; }
</style>
</head>
<body>
<?php include 'topbar.php'; ?>
<?php
echo "<h1 style=\"font-family:'Open Sans',sans-serif; text-align:center; padding:20px;\">Export Templates</h1>";

$scriptsToRun = [
    'Daily Export' => 'http://pim.samsgroup.info/daily_export.php',
    'Hubspot' => 'http://pim.samsgroup.info/export_hubspot.php',
    'Nivoda Gems' => 'https://pim.samsgroup.info/export_nivoda.php',
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

$csvFiles = [
    'Daily Export' => 'https://samsgroup.info/export/daily-export.csv',
    'Hubspot' => 'https://samsgroup.info/export/hubspot.csv',
    'Nivoda Gems' => 'https://samsgroup.info/export/nivoda.csv',
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

echo "<table><tr>";

foreach ($scriptsToRun as $scriptName => $scriptURL) {
    echo "<div>";
    echo "<tr><td><span style='font-size:18px;'>".$scriptName."</span></td>";
    echo "<td><a class='button export' style='text-decoration:none; font-size:12px; color:#FFFFFF; background-color:#eb3865;' href='" . $scriptURL . "' target='_blank'>Export</a></td>";
    // Check if there's a corresponding CSV file
    if (isset($csvFiles[$scriptName])) {
        $csvURL = $csvFiles[$scriptName];
        if (strpos($csvURL, "csv") !== false) {
            $downloadType = "CSV Download";
        } elseif (strpos($csvURL, "xml") !== false) {
            $downloadType = "XML Download";
        } else {
            $downloadType = "Unknown Download Type";
        }
        echo "<td><a class='button save' style='text-decoration:none; font-size:12px; color:#FFFFFF; background-color:#2d87ed;' href=\"downloadExports.php?csv=' . urlencode($csvURL) . '\">&nbsp;&nbsp;$downloadType</a></td></tr>";
        echo"</div>";
    }

}

echo "</table></center><br><br><br>";

?>
</body>
</html>