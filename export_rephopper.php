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
  include_once ('connect.php');
  $query = 'SELECT * FROM pim WHERE (brand <> "" AND brand <> "shopify cl" AND wholesale_aud > 0 AND master_qty > 0 AND retail_aud > 0 );';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/rephopper/rephopper.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("stock_code","name","EAN","qty_avail","qty_held","order_qty_min","order_qty_step","price","rrp","special","cost","image","description","category","subcategory","Custom field 1","Custom field 2","Custom field 3","Custom field 4","Custom field 5","Custom field 6","Custom field 7","Custom field 8","Custom field 9");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

    //category
    if (strtolower($row['brand']) === "classique watches" || strtolower($row['brand']) === "blush pink diamonds" || strtolower($row['brand']) === "pink kimberley diamonds" || strtolower($row['brand']) === "semi precious jewellery") {$category = ucwords($row['brand']);} 
    elseif (strtolower($row['brand']) === "sapphire dreams") {
        if (strtolower($row['collections']) === "sds" || strtolower($row['collections']) === "sdm") {$category = "Australian Sapphire";} 
        else {$category = "Sapphire Dreams";}
    } elseif (strpos(strtolower($row['brand']), "white") !== false ) {
        if (strtolower($row['collections']) === "awd") {$category = "Argyle Diamonds";} 
        else {$category = "White Diamonds";}
    } elseif (strpos(strtolower($row['brand']), "colour") !== false || strpos(strtolower($row['brand']), "yellow") !== false ) {$category = "Fancy Coloured Diamonds";} 
    elseif (strpos(strtolower($row['brand']), "argyle") !== false ) {$category = "Argyle Diamonds";} 
    else {$category = $row['brand'];}

    //Descriptions, if loose sapphire generate description else import from field description
    if (strtolower($type) == "loose sapphires") 
        if( strtolower($treatment) == "unheated") { $description = "An unheated Australian " .  ucfirst(strtolower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . "."; }
        else {$description = "An Australian " .  ucfirst(strtolower($shape)) . " cut " . $colour . " sapphire weighing " . $carat . "ct and measures " . $measurement . ".";  }
    else { $description = $row['description'];}

    //name
    $name = "";

    if ($row['sales_percentage'] > 0) {$name .= "[SPECIAL" . $row['sales_percentage'] . "%] ";}
    if (strtolower($row['type']) === "loose sapphires") {
        if (strtolower($row['collections']) === "sds") {
            $name .= "AU Sapphire " . $row['colour'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'];} 
        elseif (strtolower($row['collections']) === "sdm") {$name .= "AU Sapphire Melee " . $row['colour'] . " " . $row['shape'] . " " . $row['measurement'];}
    } elseif (strtolower($row['type']) === "loose diamonds") {
        if (strtolower($row['collections']) === "sks") {$name .= "AOD " . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'];} 
        elseif (strtolower($row['collections']) === "awd") {$name .= "AWD " . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'];} 
        elseif (strtolower($row['collections']) === "stn" || strtolower($row['collections']) === "sta" || strtolower($row['collections']) === "stp" || strtolower($row['collections']) === "stx") {$name .= "APD " . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'];} 
        elseif (strtolower($row['collections']) === "tpr" || strtolower($row['collections']) === "tdr" ) {$name .=  "APD Tender" . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'];}
        elseif (strtolower($row['collections']) === "melee") {
            if (strtolower($row['brand']) === "argyle pink diamonds") {$name .= "APD Melee " . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct ";}
            elseif (strtolower($row['brand']) === "argyle blue diamonds") {$name .= "ABD Melee " . $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct ";} 
            elseif (strtolower($row['brand']) === "yellow" || strtolower($row['brand']) === "colour" || strtolower($row['brand']) === "coloured") {$name .= "FCD " . $row['colour'] . "/" . $row['clarity'];} 
            elseif (strtolower($row['brand']) === "white diamonds") {$name .= $row['edl9'];}
        elseif (strtolower($row['collections']) === "fcd") {$name .= $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct " . $row['measurement'] . " " . $row['edl2'];} 
        elseif (strtolower($row['collections']) === "est") {$name .= $row['colour'] . "/" . $row['clarity'] . " " . $row['shape'] . " " . $row['carat'] . "ct ";}
        }
    } else {
        if (strtolower($row['brand']) === "blush pink diamonds" || strtolower($row['brand']) === "pink kimberley diamonds" || strtolower($row['brand']) === "sapphire dreams") {$name .= ($row['edl9'] ? $row['edl9']." ":"") . ($row['edl1'] ? $row['edl1']." ":"") . ($row['edl2'] ? $row['edl2']." ":"") . ($row['edl3'] ? $row['edl3']." ":"") . ($row['edl4'] ? $row['edl4']." ":"") . ($row['edl5'] ? $row['edl5']." ":"") . ($row['edl6'] ? $row['edl6']." ":"") . ($row['edl7'] ? $row['edl7']." ":"") . ($row['edl8'] ?? ""); $name = str_replace(["Argyle certificate", "# ", "Pink Round Brilliant Cut", "Round Brilliant Cut", " = "],["Cert", "#", "P.RBC", "RBC", "="],$name);} 
        elseif (strtolower($row['brand']) === "classique watches") {$name .= ($row['product_title'] ? $row['product_title']." ":"") . ($row['edl1'] ? $row['edl1']." ":"") . ($row['edl2'] ? $row['edl2']." ":"") . ($row['edl3'] ? $row['edl3']." ":"") . ($row['edl4'] ? $row['edl4']." ":"") . ($row['edl5'] ? $row['edl5']." ":"") . ($row['edl6'] ? $row['edl6']." ":"") . ($row['edl7'] ? $row['edl7']." ":"") . ($row['edl8'] ?? ""); $$name = str_replace(["Argyle certificate", "# ", "Pink Round Brilliant Cut", "Round Brilliant Cut", " = "],["Cert", "#", "P.RBC", "RBC", "="],$name);} 
        else {$name .= ($row['edl1'] ? $row['edl1']." ":"") . ($row['edl2'] ? $row['edl2']." ":"") . ($row['edl3'] ? $row['edl3']." ":"") . ($row['edl4'] ? $row['edl4']." ":"") . ($row['edl5'] ? $row['edl5']." ":"") . ($row['edl6'] ? $row['edl6']." ":"") . ($row['edl7'] ? $row['edl7']." ":"") . ($row['edl8'] ?? ""); $name = str_replace(["Argyle certificate", "# ", "Pink Round Brilliant Cut", "Round Brilliant Cut", " = "],["Cert", "#", "P.RBC", "RBC", "="],$name);}
    }   $name = trim(substr($name, 0, 180));

    //subcategory
    if (strpos(strtolower($row['type']), "loose") !== false) {
        if (strpos(strtolower($row['brand']), "argyle") !== false) {
            if (strtolower($row['collections']) === "sks") {$subcategory = "Argyle Origin Pink";} 
            elseif (strtolower($row['collections']) === "awd") {$subcategory = "Argyle White";} 
            elseif (strtolower($row['collections']) === "tdr" || strtolower($row['collections']) === "tpr") {$subcategory = "Argyle Tender";} 
            elseif (strtolower($row['collections']) === "sta" || strtolower($row['collections']) === "stn" || strtolower($row['collections']) === "stx" || strtolower($row['collections']) === "stp") {$subcategory = "Argyle Certified Pink";} 
            elseif (strtolower($row['collections']) === "melee") {$subcategory = "Argyle Melee";}
        } else {
            if (strpos(strtolower($row['brand']), "white") !== false) {
                if (strtolower($row['collections']) === "est") {$subcategory = "IGI Certified";} 
                elseif (strtolower($row['collections']) === "wdl" || strtolower($row['collections']) === "wxl" || strtolower($row['collections']) === "stn") {$subcategory = "Loose Diamonds";} 
                elseif (strtolower($row['collections']) === "wdp") {$subcategory = "Diamond Pairs";} 
                elseif (strtolower($row['collections']) === "melee") {$subcategory = "Loose Melee";}
            } elseif (strtolower($row['brand']) === "yellow diamonds" || strtolower($row['brand']) === "colour diamonds") {
                if (strtolower($row['collections']) === "melee") {$subcategory = "Loose Coloured Melee";} 
                else {$subcategory = "Loose Coloured Diamonds";}
            } elseif (strtolower($row['brand']) === "sapphire dreams") {
                if (strtolower($row['collections']) === "sds") {$subcategory = "Loose Sapphires";} else {$subcategory = "Loose Melee";}
            } 
        }
    } else {$subcategory = $row[type];}

    //Custom Field 7 Warehouse QTY
    if ( $row['warehouse_qty'] !== 0)
        if ( $row['allocated_qty'] > 0)  {$custom7 = "W/H: " . $row['warehouse_qty'] . " - Appro: " . $row['allocated_qty'];}
        else { $custom7 = "W/H: " . $row['warehouse_qty'];}

    //Custom Field 8 MD QTY
    if ( $row['mdqty'] > 0) { $custom8 = "MD: " . $row['mdqty'];} else { $custom8 = "";}

    //Custom Field 9 PS QTY
    if ( $row['psqty'] > 0) { $custom9 = "PS: " . $row['psqty'];} else { $custom9 = "";}

    $content = array (
        0 => $row['sku'],
        1 => $name,
        2 => $row['sku'],
        3 => $row['master_qty'],
        4 => "",
        5 => "1",
        6 => "",
        7 => $row['wholesale_aud'],
        8 => $row['retail_aud'],
        9 => "",
        10 => "",
        11 => $row['sku'],
        12 => $description,
        13 => $category,
        14 => $subcategory,
        15 => $row['edl1'],
        16 => $row['edl2'],
        17 => $row['edl3'],
        18 => $row['edl4'],
        19 => $row['edl5'],
        20 => $row['edl6'],
        21 => $custom7,
        22 => $custom8,
        23 => $custom9,
    
      );
    
      fputcsv($fp, $content);
    }

fclose($fp);
$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<center><h2>REPHOPPER Export Completed!</h2><br>";
echo "Total of ".$count." Products Exported<br><br>";
echo "<a href='https://pim.samsgroup.info/rephopper/rephopper.csv'><b>View on Web</a><br><br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br></center>';

$error = mysqli_error($con);
if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }

fclose($fp);


?>
</div>
</body>
</html>

