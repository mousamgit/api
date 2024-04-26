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
  include ('connect.php');
  include ('mkdir.php');

  $query = 'SELECT * FROM `pim` WHERE brand like "%sapphire dreams%" and type = "Loose sapphires" and (image1 IS NOT NULL and image1 <> "") and shopify_qty > 0';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/export/nivoda.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Stock ID/ SKU","Shape","Price per carat (USD)","Total Price","Origin","Measurements","Location","Length","Width","Height","Lab","Gemstone Type/Name","GemStones/Cut","Color Description","Stone Color","Carat Weight","Clarity/Transparency","Certificate ID","Treatment","Video","Thumbnail","Any Comments");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);

  while($row = mysqli_fetch_assoc($result)){

    if($row['image1'] != "") { $imageURL = $row['image1'];}
    $stone_price_usd = round($row['wholesale_usd'] * $row['carat'],2);
    $measurement = str_replace("mm", "", $row['measurement']);
    $measurements = explode("x",$measurement);

    $length = $measurements[0];
    $width = $measurements[1];
    $height = $measurements[2];
    $certID = str_replace("SDS","",$row['sku']);
    if (empty($row['treatment'])) { $treatment = "heated";} else { $treatment = $row['treatment']; }

    $content = array (
        0 => $row['sku'],
        1 => $row['shape'],
        2 => $row['wholesale_usd'],
        3 => $stone_price_usd,
        4 => "Australia",
        5 => $row['measurement'],
        6 => "Sydney",
        7 => $length,
        8 => $width,
        9 => $height,
        10 => "Sapphire Dreams",
        11 => "Sapphire",
        12 => "Faceted",
        13 => "",
        14 => $row['colour'],
        15 => $row['carat'],
        16 => "EyeClean",
        17 => $certID,
        18 => $treatment,
        19 => "https://samsgroup.sirv.com/SD-Product/Sapphire%20Dreams%20Products/".$row['sku']."/".$row['sku'].".spin",
        20 => $row['image1'],
        21 => "",
      );

      fputcsv($fp, $content);
}

fclose($fp);
$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<center><h2>Nivoda Export Completed!</h2><br>";
echo "Total of ".$count." Products Exported<br><br>";
echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/nivoda.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br></center>';


$error = mysqli_error($con);
if($error != "") { print($sku."Error Occurred: ".$error."<br>"); }
?>