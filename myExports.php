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
td { border: 1px solid #000; padding: 15px; }
</style>
</head>
<body>
<?php include 'topbar.php'; ?>
<?php
echo "<h1 style=\"font-family:'Open Sans',sans-serif; text-align:center; padding:20px;\">Export Templates</h1>";

$scriptsToRun = [
    'Daily Export' => ['script' => 'http://pim.samsgroup.info/daily_export.php', 'file' => 'https://samsgroup.info/export/daily-export.csv'],
    'Hubspot' => ['script' => 'http://pim.samsgroup.info/export_hubspot.php', 'file' => 'https://samsgroup.info/export/hubspot.csv'],
    'Nivoda Gems' => ['script' => 'https://pim.samsgroup.info/export_nivoda.php', 'file' => 'https://samsgroup.info/export/nivoda.csv'],
    'Production - ID CARD PK' => ['script' =>'http://pim.samsgroup.info/export_pk_id.php', 'file' => 'https://pim.samsgroup.info/production/pk-id.csv'],
    'Production - ID CARD BP' => ['script' =>'http://pim.samsgroup.info/export_bp_id.php', 'file' => 'https://pim.samsgroup.info/production/bp-id.csv'],
    'Production - ID CARD SD' => ['script' =>'http://pim.samsgroup.info/export_sd_id.php', 'file' => 'https://pim.samsgroup.info/production/sd-id.csv'],
    'Rephopper' => ['script' => 'http://pim.samsgroup.info/export_rephopper.php', 'file' => 'https://pim.samsgroup.info/rephopper/rephopper.csv'],
    'Shopify Sapphire Dreams' => ['script' => 'http://pim.samsgroup.info/export_sd_shopify.php', 'file' => 'https://samsgroup.info/export/sd-shopify.csv'],
    'Shopify Pink Kimberley' => ['script' => 'http://pim.samsgroup.info/export_pk_shopify.php', 'file' => 'https://samsgroup.info/export/pk-shopify.csv'],
    'Shopify SGA Wholesale' => ['script' => 'http://pim.samsgroup.info/export_sga_shopify.php', 'file' => 'https://samsgroup.info/export/sga-shopify.csv'],
    'Shopify Classique Watches' => ['script' => 'http://pim.samsgroup.info/export_cl_shopify.php', 'file' => 'https://samsgroup.info/export/cl-shopify.csv'],
    'Client JIM077 Shopify Products Import' => ['script' => 'http://pim.samsgroup.info/export_jim077_product.php', 'file' => 'https://pim.samsgroup.info/client_export/jim077_product_import.csv'],
    'Client JIM309 Shopify Products Import' => ['script' => 'http://pim.samsgroup.info/export_jim309_product.php', 'file' => 'https://pim.samsgroup.info/client_export/jim309_product_import.csv'],
    'Client JIM077 Shopify Inventory Import' => ['script' => 'http://pim.samsgroup.info/export_jim077_inventory.php', 'file' => 'https://pim.samsgroup.info/client_export/jim077_inventory_import.csv'],
    'Client JIM309 Shopify Inventory Import' => ['script' => 'http://pim.samsgroup.info/export_jim309_inventory.php', 'file' => 'https://pim.samsgroup.info/client_export/jim309_inventory_import.csv'],
    'Sirv Certificate' => ['script' => 'http://pim.samsgroup.info/export_sirv.php', 'file' => 'https://pim.samsgroup.info/sirv/sirv.xml'],
];

echo "<table><tr>";

foreach ($scriptsToRun as $name => $urls) {
    echo "<tr><td><span style='font-size:18px;'>".$name."</span></td>";
    echo "<td><a class='button export' style='text-decoration:none; font-size:12px; color:#FFFFFF; background-color:#eb3865;' href='" . $urls['script'] . "' target='_blank'>Export</a></td>";
    
    if (strpos($urls['file'], "csv") !== false) {$downloadType = "CSV Download";} 
    elseif (strpos($urls['file'], "xml") !== false) {$downloadType = "XML Download";} 
    else {$downloadType = "Unknown Download Type";}
    echo "<td><a class='button save' style='text-decoration:none; font-size:12px; color:#FFFFFF; background-color:#2d87ed;' href='downloadExports.php?csv=" . urlencode($urls['file']) . "'>&nbsp;&nbsp;" . $downloadType . "</a></td></tr>";
}
echo "</table><br><br><br>";

?>
</body>
</html>