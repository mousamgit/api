<html>
<head>
  <title>The following items have been deleted</title>
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

<?php
include_once ('connect.php');

if(isset($_POST['checkbox'])) $sku= $_POST['checkbox'];

//var_dump(array_values($sku));

foreach ($sku as $key => $value)
{
  $sql = " UPDATE pim SET deletion='1' WHERE sku = '".$value."'; ";
  $result = mysqli_query($con, $sql);
  echo $value." has been marked for deletion.<br>";
}

echo "The deletion will take place everyday at 12AM.";

?>

</div>
</body>
</html>
