<?php $startScriptTime=microtime(TRUE); include '../login_checking.php'; include '../functions.php'; ?>
<html>
<head>
<?php include '../header.php'; ?>
<title> Data Export </title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Open Sans', sans-serif; }
  table { border: 2px solid #000; }
  th { font-size: 12px; font-weight: 700; border: 1px solid #000; padding:0px 40px; }
  td { font-size: 10px; font-weight: 400; border: 1px solid #000; padding: 10px; }
</style>
</head>

<body>


<?php
  include '../topbar.php';
  include ('../connect.php');

  if(isset($_POST['sku'])) $sku=$_POST['sku'];
  if(isset($_POST['custcode'])) $custcode=$_POST['custcode'];

  //main headers
  if(isset($_POST['headers-sku'])) $headers_sku=$_POST['headers-sku'];
  if(isset($_POST['description'])) $description=$_POST['description'];
  if(isset($_POST['brand'])) $brand=$_POST['brand'];
  if(isset($_POST['title'])) $title=$_POST['title'];
  if(isset($_POST['type'])) $type=$_POST['type'];
  if(isset($_POST['specs'])) $brand=$_POST['specs'];
  if(isset($_POST['tags'])) $title=$_POST['tags'];
  if(isset($_POST['ws'])) $ws=$_POST['ws'];
  if(isset($_POST['rrp'])) $rrp=$_POST['rrp'];
  if(isset($_POST['carat'])) $carat=$_POST['carat'];
  if(isset($_POST['shape'])) $shape=$_POST['shape'];
  if(isset($_POST['colour'])) $colour=$_POST['colour'];
  if(isset($_POST['clarity'])) $clarity=$_POST['clarity'];
  if(isset($_POST['metal'])) $metal=$_POST['metal'];
  if(isset($_POST['measurement'])) $measurement=$_POST['measurement'];
  if(isset($_POST['stonespecs'])) $stonespecs=$_POST['stonespecs'];

  //image headers
  if(isset($_POST['image1'])) $image1=$_POST['image1'];
  if(isset($_POST['image2'])) $image2=$_POST['image2'];
  if(isset($_POST['image3'])) $image3=$_POST['image3'];
  if(isset($_POST['image4'])) $image4=$_POST['image4'];
  if(isset($_POST['image5'])) $image5=$_POST['image5'];
  if(isset($_POST['image6'])) $image6=$_POST['image6'];
  if(isset($_POST['packagingimg'])) $packagingimg=$_POST['packagingimg'];

  $filename = strtolower($custcode)."-".time().".csv";
  $path = getcwd();
  $path = substr($path, 0, strpos($path, "public_html"));
  $root = $path . "public_html/";
  $filepath = $root . 'export/customerdata/export-'.$filename;
  echo $filepath;
  $dlfile = "https://samsgroup.info/export/customerdata/export-".$filename;

  $fp = fopen($filepath, 'w');





  //inputted skus
  $skuarray = str_replace(array("\r", "\n"), ',', $sku);
  $skuarray = array_filter(explode(',', $skuarray));
  //var_dump(array_values($skuarray));

  echo "<h1>Data Export for ".strtoupper($custcode)."</h1><br><br>";

  echo "<b>URL of CSV File</b> <input type='text' size='100' value='".$dlfile."' readonly>";
  echo '<a  href="../download.php?file='.$dlfile.'" target="_new" ><button>Download CSV File</button></a>';

  
  echo "<table cellspacing=0>";
  echo "<tr>";
  echo "<thead>";
  // add headers to html table
  if (isset($_POST['headers-sku'])){ echo "<th>SKU</th>"; $headers[] = "SKU"; }
  if (isset($_POST['description'])){ echo "<th>Description</th>"; $headers[] = "Description"; }
  if (isset($_POST['brand'])){ echo "<th>Brand</th>"; $headers[] = "Brand"; }
  if (isset($_POST['title'])){ echo "<th>Product Title</th>"; $headers[] = "Product Title"; }
  if (isset($_POST['type'])){ echo "<th>Product Type</th>"; $headers[] = "Product Type"; }
  if (isset($_POST['specs'])){ echo "<th>Specifications</th>"; $headers[] = "Specifications"; }
  if (isset($_POST['tags'])){ echo "<th>Tags</th>"; $headers[] = "Tags"; }
  if (isset($_POST['ws'])){ echo "<th>Wholesale Price ex GST (AUD)</th>"; $headers[] = "Wholesale Price ex GST (AUD)"; }
  if (isset($_POST['rrp'])){ echo "<th>Retail Price Incl GST (AUD)</th>"; $headers[] = "Retail Price Incl GST (AUD)"; }
  if (isset($_POST['carat'])){ echo "<th>Carat</th>"; $headers[] = "Carat"; }
  if (isset($_POST['shape'])){ echo "<th>Shape</th>"; $headers[] = "Shape"; }
  if (isset($_POST['colour'])){ echo "<th>Colour</th>"; $headers[] = "Colour"; }
  if (isset($_POST['clarity'])){ echo "<th>Clarity</th>"; $headers[] = "Product Type"; }
  if (isset($_POST['metal'])){ echo "<th>Specifications</th>"; $headers[] = "Clarity"; }
  if (isset($_POST['measurement'])){ echo "<th>Measurement</th>"; $headers[] = "Measurement"; }
  if (isset($_POST['stonespecs'])){ echo "<th>Stone Specifications</th>"; $headers[] = "Stone Specifications"; }
  if (isset($_POST['image1'])){ echo "<th>Main Image</th>"; $headers[] = "Main Image"; }
  if (isset($_POST['image2'])){ echo "<th>Image 2</th>"; $headers[] = "Image 2"; }
  if (isset($_POST['image3'])){ echo "<th>Image 3</th>"; $headers[] = "Image 3"; }
  if (isset($_POST['image4'])){ echo "<th>Image 4</th>"; $headers[] = "Image 4"; }
  if (isset($_POST['image5'])){ echo "<th>Image 5</th>"; $headers[] = "Image 5"; }
  if (isset($_POST['image6'])){ echo "<th>Image 6</th>"; $headers[] = "Image 6"; }
  if (isset($_POST['packagingimg'])){ echo "<th>Packaging Image</th>"; $headers[] = "Packaging Image"; }

  // add headers to CSV

  /*$header_length = count($headers);
  $csv_header = '';
  for ($i = 0; $i < $header_length; $i++) { $csv_header .= '"' . $headers[$i] . '",'; }
  $csv_header .= "\n";*/
  fputcsv($fp, $headers);

  echo "</thead>";
  echo "</tr>";

  foreach ($skuarray as $value)
  {
    $sql = " SELECT * from pim where sku = '".$value."';";
    $result = mysqli_query($con,$sql);
    echo "<tr>";
    while($row = mysqli_fetch_assoc($result)){
      unset($content);
      if (isset($_POST['headers-sku'])){ echo "<td>".$row[sku]."</td>"; $content[] = $row[sku];  }
      if (isset($_POST['description'])){ echo "<td>".$row[description]."</td>"; $content[] = $row[description]; }
      if (isset($_POST['brand'])){ echo "<td>".$row[brand]."</td>"; $content[] = $row[brand]; }
      if (isset($_POST['title'])){ echo "<td>".$row[product_title]."</td>"; $content[] = $row[product_title]; }
      if (isset($_POST['type'])){ echo "<td>".$row[type]."</td>"; $content[] = $row[type]; }
      if (isset($_POST['specs'])){ echo "<td>".$row[specifications]."</td>"; $content[] = $row[specifications]; }
      if (isset($_POST['tags'])){ echo "<td>".$row[tags]."</td>"; $content[] = $row[tags]; }
      if (isset($_POST['ws'])){ echo "<td>".$row[wholesale_aud]."</td>"; $content[] = $row[wholesale_aud]; }
      if (isset($_POST['rrp'])){ echo "<td>".$row[retail_aud]."</td>"; $content[] = $row[retail_aud]; }
      if (isset($_POST['carat'])){ echo "<td>".$row[carat]."</td>"; $content[] = $row[carat]; }
      if (isset($_POST['shape'])){ echo "<td>".$row[shape]."</td>"; $content[] = $row[shape]; }
      if (isset($_POST['colour'])){ echo "<td>".$row[colour]."</td>"; $content[] = $row[colour]; }
      if (isset($_POST['clarity'])){ echo "<td>".$row[clarity]."</td>"; $content[] = $row[clarity]; }
      if (isset($_POST['metal'])){ echo "<td>".$row[metal_composition]."</td>"; $content[] = $row[metal_composition]; }
      if (isset($_POST['measurement'])){ echo "<td>".$row[measurement]."</td>"; $content[] = $row[measurement]; }
      if (isset($_POST['stonespecs'])){
        if ( preg_match("/Cert/i", $row[specifications]) > 0){
          $stone_info = "ID No.: ".str_replace("SDS","",$row[sku])."<br>Colour: ".strtoupper($row[colour]).str_replace("Unheated"," NH",$row[treatment])."<br>Shape: ".strtoupper($row[shape])."<br>Weight: ".$row[carat]."ct<br>Size: ".$row[measurement]."<br>Origin: AUSTRALIA";
          $stone_info = str_replace("  "," ",$stone_info);
          echo "<td>".$stone_info."</td>";
          $content[] = $stone_info;
        }else { echo "<td></td>"; $content[] = ""; }
      }
      if (isset($_POST['image1'])){ if ($row[image1] != ""){ echo "<td><img src='".$row[image1]."' width=200></td>";$content[] = $row[image1]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['image2'])){ if ($row[image2] != ""){ echo "<td><img src='".$row[image2]."' width=200></td>";$content[] = $row[image2]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['image3'])){ if ($row[image3] != ""){ echo "<td><img src='".$row[image3]."' width=200></td>";$content[] = $row[image3]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['image4'])){ if ($row[image4] != ""){ echo "<td><img src='".$row[image4]."' width=200></td>";$content[] = $row[image4]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['image5'])){ if ($row[image5] != ""){ echo "<td><img src='".$row[image5]."' width=200></td>";$content[] = $row[image5]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['image6'])){ if ($row[image6] != ""){ echo "<td><img src='".$row[image6]."' width=200></td>";$content[] = $row[image6]; } else { echo "<td></td>";$content[] = ""; } }
      if (isset($_POST['packagingimg'])){ if ($row[packaging_image] != ""){ echo "<td><img src='".$row[packaging_image]."' width=200></td>";$content[] = $row[packaging_image]; } else { echo "<td></td>";$content[] = ""; } }
      fputcsv($fp, $content);
    }
    echo "</tr>";
  }
  fclose($fp);
  echo "</table>";

 ?>

</body>
</html>
