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
  $query = 'SELECT * FROM pim WHERE (brand = "Pink Kimberley Diamonds" AND type NOT LIKE "%loose%");';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/production/pk-id.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Model","Description","Specification","Disclaimer");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){

    //edl1 - edl8
    $specification = $row['edl1'];
    if ($row['edl2'] != "") { $specification .= "\n".$row['edl2'];}
    if ($row['edl3'] != "") { $specification .= "\n".$row['edl3'];}
    if ($row['edl4'] != "") { $specification .= "\n".$row['edl4'];}
    if ($row['edl5'] != "") { $specification .= "\n".$row['edl5'];}
    if ($row['edl6'] != "") { $specification .= "\n".$row['edl6'];}
    if ($row['edl7'] != "") { $specification .= "\n".$row['edl7'];}
    if ($row['edl8'] != "") { $specification .= "\n".$row['edl8'];}

    //disclaimer
    if (strpos( $specification, "blue")) { $disclaimer = "PINK AND BLUE DIAMONDS FROM THE ARGYLE DIAMOND MINE IN THE EAST KIMBERLEY REGION OF WESTERN AUSTRALIA";}
    else { $disclaimer = "PINK DIAMONDS FROM THE ARGYLE DIAMOND MINE IN THE EAST KIMBERLEY REGION OF WESTERN AUSTRALIA";}

    $content = array (
        0 => $row['sku'],
        1 => $row['edl9'],
        2 => $specification,
        3 => $disclaimer,    
      );
    
      fputcsv($fp, $content);
    }

fclose($fp);
$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<center><h2>PK Jewellery ID CARD updated!</h2><br>";
echo "Total of ".$count." Products Exported<br><br>";
echo "<a href='https://pim.samsgroup.info/production/pk-id.csv'><b>View on Web</a><br><br>";
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
