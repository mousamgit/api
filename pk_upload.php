<?php
include ('attributes.php');
$fields = array(
                array('sku',$sku),
                array('product_title',$productTitle),
                array('brand',$brand),
                array('specifications',$specifications),
                array('type',$type),
                array('colour',$colour),
                array('clarity',$clarity),
                array('carat',$carat),
                array('measurement',$measurement),
                array('purchase_cost_aud',$purchasecostAUD),
                array('purchase_cost_usd',$purchasecostUSD),
                array('manufacturing_cost_aud',$manufacturingcostAUD),
                array('wholesale_aud',$wholesaleAUD),
                array('wholesale_usd',$wholesaleUSD),
                array('retail_aud',$retailAUD),
                array('retail_usd',$retailUSD),
                array('master_qty',$masterqty),
                array('warehouse_qty',$warehouseqty),
                array('mdqty',$mdqty),
                array('psqty',$psqty),
                array('usdqty', $usdqty),
                array('image1',$image1),
                array('image2',$image2),
                array('image3',$image3),
                array('image4',$image4),
                array('image5',$image5),
                array('image6',$image6),
                array('shopify_qty', $shopifyqty),
                array('packaging_image',$packagingimg),
                array('shape', $shape),
                array('edl1', $edl1),
                array('edl2', $edl2),
                array('edl3', $edl3),
                array('edl4', $edl4),
                array('edl5', $edl5),
                array('edl6', $edl6),
                array('edl7', $edl7),
                array('edl8', $edl8),
                array('edl9', $edl9),
                array('metal_composition', $metalcomposition),
                array('main_metal', $mainmetal),
                array('collections', $collections),
                array('centre_stone_sku', $centrestonesku),
                array('centre_stone_qty', $centrestoneqty)
            );


for ($i = 0; $i < count($fields); $i++) { $insertSQL .= $fields[$i][0].","; }
$insertSQL = rtrim($insertSQL,",");

for ($row = 0; $row < count($fields); $row++) {
  for ($column = 1; $column < 2; $column++) { $valuesSQL .= "'".$fields[$row][$column]."',"; }
}
$valuesSQL = rtrim($valuesSQL,",");

for ($row = 0; $row < count($fields); $row++)
{
  $key .= $fields[$row][0]."='";
  for ($column = 1; $column < 2; $column++) { $key .= $fields[$row][$column]."',"; }
}
$key = rtrim($key,",");

$sql = "INSERT into pim ($insertSQL) VALUES ($valuesSQL) ON DUPLICATE KEY UPDATE $key";
     $result = mysqli_query($con, $sql);


$insertSQL = "";
$valuesSQL = "";
$key = "";

include ('clearattributes.php');

?>
