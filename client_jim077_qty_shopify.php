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

  $query = 'SELECT * FROM pim WHERE (client_jim077_qty > 0 AND brand IN ("blush pink diamonds", "pink kimberley diamonds","sapphire dreams") AND image1 <> "" AND image1 IS NOT NULL AND collections <> "melee" AND collections <> "sdm" AND collections <> "sdl") OR (client_jim077_qty <= 0 AND client_sgastock = 1 AND collections_2 <> "steve" && collections_2 <> "discontinued" AND brand <> "classique watches" AND brand <>"shopify cl" AND image1 <> "" AND image1 IS NOT NULL AND collections <> "melee" AND collections <> "sdm" AND collections <> "sdl")';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  $filepath = $_SERVER['DOCUMENT_ROOT'] . '/client_export/jim077_inventory_import.csv';
  $fp = fopen($filepath, 'w');

  $headers = array("Handle","Title","Option1 Name", "Option1 Value","Option2 Name","Option2 Value","Option3 Name","Option3 Value","SKU","HS Code","COO","Location","Incoming","Unavailable","Committed","Available","On hand");
  $header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";
  fputcsv($fp, $headers);

  $csv_row ='';
  $numrows = mysqli_num_rows($result);
  while($row = mysqli_fetch_assoc($result)){


          // Create handle
          $handle = "";
          if ( strtolower($row['brand']) == "sapphire dreams")
            if ( strtolower($row['type']) == "loose sapphires") { $handle .= $row['shape'] . "-" . $row['colour'] . "-australian-sapphire-" . $row['sku'] . "-matthews-jewellers";}
            else { $handle .= $row['product_title'] . "-" . $row['sku'] . "-matthews-jewellers";}
          elseif ( strtolower($row['brand']) == "argyle origin diamonds" || strtolower($row['brand']) == "argyle pink diamonds") { $handle .= "argyle-pink-diamond-" . $row['shape'] . "-" . $row['colour'] . "-" . $row['clarity'] . "-" . $row['sku'] . "-matthews-jewellers";}
          elseif ( strtolower($row['brand'])== "pink kimberley diamonds" || strtolower($row['brand']) == "blush pink diamonds") { $handle .= $row['product_title'] . "-" . $row['sku'] . "-matthews-jewellers";}
          else { $handle .= $row['product_title'] . "-" . $row['sku'] . "-matthews-jewellers";}
          $handle = str_replace([" ","--"],"-",strtolower($handle));   
          
          //product title
          $title = $row['product_title'];
          if ( strtolower($row['brand']) == "sapphire dreams")
            if ( strtolower($row['type']) == "loose sapphires" && strtolower($row['treatment']) == "unheated") { $title = "Australian Sapphire ". $row['shape']." 1=".$row['carat']."ct ".$row['colour'] . " NH";}
            elseif ( strtolower($row['type']) == "loose sapphires" && strtolower($row['treatment']) !== "unheated") { $title = "Australian Sapphire ". $row['shape']." 1=".$row['carat']."ct ".$row['colour']; }
            elseif ( strtolower($row['type']) !== "loose sapphires" && strpos(strtolower($row['collections_2']), "variants") !== false) { $title = ucwords($title_mod) . " " . $row['shape'] . " Sapphire " . ucwords($type_mod);}
          if ( $row['collections'] == "TPR" || $row['collections'] == "TDR") { $title = str_replace("pink diamond","tender diamond", $row['product_title']);}

          //if SGA Stock >= 5 qty = 1 else 0
          if ( $row['client_jim077_qty'] > 0 ) { $inventoryQty = $row['client_jim077_qty'];}
          else{
            if ( $row['client_sgastock'] == 1 ){
              if ( $row['shopify_qty'] >= 3) { $inventoryQty = 1; }
              else { $inventoryQty = 0;}
            }          
          }

                $content = array (
                    0 => $handle,
                    1 => $title,
                    2 => "Title",
                    3 => "Default Title",
                    4 => "",
                    5 => "",
                    6 => "",
                    7 => "",
                    8 => $row['sku'],
                    9 => "",
                    10 => "",
                    11 => "Matthews Group",
                    12 => 0,
                    13 => 0,
                    14 => 0,
                    15 => $inventoryQty,
                    16 => $inventoryQty
            
                );
                fputcsv($fp, $content);
        
    }      
  

  fclose($fp);
  $count = mysqli_num_rows($result) -1;
  date_default_timezone_set('Australia/Sydney');
  echo "<h2>JIM077: Matthews Jewellers Shopify Inventory QTY CSV Exported!</h2><br>";
  echo "Total Products Exported to CSV: ".$count."<br><br>";
  echo "<a style='font-weight:bold;' href='https://pim.samsgroup.info/client_export/jim077_inventory_import.csv'>View on Web</a><br><br>";
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