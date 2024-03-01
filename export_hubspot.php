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
  $startScriptTime=microtime(TRUE);
  include_once ('connect.php');
  include_once ('mkdir.php');

  $query = 'SELECT * FROM pim';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/hubspot.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("SKU","Name","Price AUD","Product Description","Cost of goods sold");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){


    //price per piece carat for loose
    if ( strpos(strtolower($row[type]), "loose") !== false ) { $price = $row[stone_price_wholesale_aud];}
        else { $price = $row[wholesale_aud];}

    //description
    $name = "";

    if ($row[sales_percentage] > 0) {$name .= "[SPECIAL" . $row[sales_percentage] . "%]";}
    if (strtolower($row[type]) === "loose sapphires") {
        if (strtolower($row[collections]) === "sds") {
            $name .= "AU Sapphire " . $row[colour] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement];} 
        elseif (strtolower($row[collections]) === "sdm") {$name .= "AU Sapphire Melee " . $row[colour] . " " . $row[shape] . " " . $row[measurement];}
    } elseif (strtolower($row[type]) === "loose diamonds") {
        if (strtolower($row[collections]) === "sks") {$name .= "AOD " . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement];} 
        elseif (strtolower($row[collections]) === "awd") {$name .= "AWD " . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement];} 
        elseif (strtolower($row[collections]) === "stn" || strtolower($row[collections]) === "sta" || strtolower($row[collections]) === "stp" || strtolower($row[collections]) === "stx") {$name .= "APD " . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement];} 
        elseif (strtolower($row[collections]) === "tpr" || strtolower($row[collections]) === "tdr" ) {$name .=  "APD Tender" . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement];}
        elseif (strtolower($row[collections]) === "melee") {
            if (strtolower($row[brand]) === "argyle pink diamonds") {$name .= "APD Melee " . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct ";}
            elseif (strtolower($row[brand]) === "argyle blue diamonds") {$name .= "ABD Melee " . $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct ";} 
            elseif (strtolower($row[brand]) === "yellow" || strtolower($row[brand]) === "colour" || strtolower($row[brand]) === "coloured") {$name .= "FCD " . $row[colour] . "/" . $row[clarity];} 
            elseif (strtolower($row[brand]) === "white diamonds") {$name .= $row[edl9];}
        elseif (strtolower($row[collections]) === "fcd") {$name .= $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct " . $row[measurement] . " " . $row[edl2];} 
        elseif (strtolower($row[collections]) === "est") {$name .= $row[colour] . "/" . $row[clarity] . " " . $row[shape] . " " . $row[carat] . "ct ";}
        }
    } else {
        if (strtolower($row[brand]) === "blush pink diamonds" || strtolower($row[brand]) === "pink kimberley diamonds" || strtolower($row[brand]) === "sapphire dreams") {$name .= ($row[edl9] ? $row[edl9]." ":"") . ($row[edl1] ? $row[edl1]." ":"") . ($row[edl2] ? $row[edl2]." ":"") . ($row[edl3] ? $row[edl3]." ":"") . ($row[edl4] ? $row[edl4]." ":"") . ($row[edl5] ? $row[edl5]." ":"") . ($row[edl6] ? $row[edl6]." ":"") . ($row[edl7] ? $row[edl7]." ":"") . ($row[edl8] ?? ""); $name = str_replace(["Argyle certificate", "# ", "Pink Round Brilliant Cut", "Round Brilliant Cut", " = "],["Cert", "#", "P.RBC", "RBC", "="],$name);} 
        elseif (strtolower($row[brand]) === "classique watches") {
            if ( strpos($row[type], "Clearance 50%")!= false ) {$name .= "[CLEARANCE50%]";}
            $name .= ($row[edl1] ? $row[edl1]." ":"") . ($row[edl2] ? $row[edl2]." ":"") . ($row[edl3] ? $row[edl3]." ":"") . ($row[edl4] ? $row[edl4]." ":"") . ($row[edl5] ? $row[edl5]." ":"") . ($row[edl6] ? $row[edl6]." ":"") . ($row[edl7] ? $row[edl7]." ":"") . ($row[edl8] ?? "") . ($row[edl9] ?? "") . ($row[edl10] ?? "") . ($row[edl11] ?? "");}
        else {$name .= ($row[edl1] ? $row[edl1]." ":"") . ($row[edl2] ? $row[edl2]." ":"") . ($row[edl3] ? $row[edl3]." ":"") . ($row[edl4] ? $row[edl4]." ":"") . ($row[edl5] ? $row[edl5]." ":"") . ($row[edl6] ? $row[edl6]." ":"") . ($row[edl7] ? $row[edl7]." ":"") . ($row[edl8] ?? ""); $name = str_replace(["Argyle certificate", "# ", "Pink Round Brilliant Cut", "Round Brilliant Cut", " = "],["Cert", "#", "P.RBC", "RBC", "="],$name);}
    }   $name = trim(substr($name, 0, 180));


    $content = array (
        0 => $row[sku],
        1 => $row[sku],
        2 => $price,
        3 => $name,
        4 => $row[purchase_cost_aud],


      );

      $handleIndex = array_search("Handle", $headers);
      $handleColumn = array_column($content, $headers[$handleIndex]);
      array_multisort($handleColumn, $content);

      fputcsv($fp, $content);
}


fclose($fp);
$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<h2>HUBSPOT Export Completed!</h2><br>";
echo "Total Products Exported to CSV: ".$count."<br><br>";
echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/hubspot.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br>';

$error = mysqli_error($con);
if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }

fclose($fp);
?>
</div>
</body>
</html>