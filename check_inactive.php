<html>
<head>
  <title> Inactive SKUs not found in AssetWin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
<form class="form-horizontal" action="delete_inactive.php" method="post" enctype="multipart/form-data">

<?php
include_once ('connect.php');
$filename = "APP_WebA2022.csv";
$filedirect = dirname($_SERVER['DOCUMENT_ROOT']).'/files/'.$filename;

$file = file_get_contents($filedirect);

$sql = "SELECT sku FROM pim";
$result = mysqli_query($con, $sql);

$count = 1;

echo "<h1>Inactive SKUs not found in Assetwin</h1>";
echo "<p>This page lists all of the SKUs within our PIM system that are no longer on Asset. This page will allow you to delete the item if necessary.</p>";
echo "<center><table border=1 cellspacing=0 cellpadding=10>";
echo "<tr>";
echo "<thead>";
echo "<th>Number</th>";
echo "<th>SKU</th>";
echo "<th>Delete</th>";
echo "</thead>";
echo "</tr>";

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  if (strpos($file, $row[sku]) == false) {
    echo "<tr>";
    echo "<td align=center>".$count."</td><td>".$row[sku]."</td><td align=center><input type='checkbox' id='checkbox' value='".$row[sku]."' name='checkbox[]'></td>";
    $count++;
    echo "</tr>";
  }
}
echo "</table></center><br>";

?>

<button type="submit" id="submit" name="Submit" data-loading-text="Loading...">Delete selected SKUs</button>
</form>
</div>
</body>
</html>
