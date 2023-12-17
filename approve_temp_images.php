<html>
<head>
  <title>Images Uploaded</title>
  <?php include 'header.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; text-align:center; }
    .top-bar { display:block; padding:20px; text-align:left; background-color:#fafafa; margin-bottom:100px;}
    .main-box { width:50%; margin:0 auto; padding:50px; text-align:center; }
    table {border: 1px solid #000; width:100%;}
    th, td { width: 12.5%; height: 12.5%; border: 1px solid #000; padding:10px;}
    input[type=checkbox] { height:20px; width:20px; }
    input.checkAll { height: 100px; width: 100px; }
    .image-box:hover img { transform: scale(2); transition: all 0.3s ease-in-out;}
    .image-box { border-bottom:1px solid #000; padding:20px 0px; }
    .exists { color:green; font-size:8px; }
    .warning { color:red; font-size:8px; }
  </style>
</head>
<body>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div>

<h2>Check Images and Upload</h2>

<div class="main-box">
<?php
include_once ('connect.php');
$pieces = $_POST['check'];

foreach ($pieces as $key => $value)
{
    //echo $key.":".$value."<br>";
    $values = explode(":", $value);
    if( strpos($values[1],"_") > 0 ) 
    {
        $number = substr(strstr($values[1],'_'),1,1);
    }
    else
    {
        $number = 1; 
    }

    $temp = $_SERVER['DOCUMENT_ROOT']."/temp-images/".$values[1];
    $targetFolder = $_SERVER['DOCUMENT_ROOT']."-images/".$values[1];
    rename($temp, $targetFolder);

    $newImage = "https://samsgroup.info/pim-images/".$values[1];

    echo "<div class='image-box'><img src='".$newImage."' width=150px> ".$values[1]." has been updated. Link: <a href='".$newImage."' target='_blank'>".$newImage."</a></div>";

    $sql = " UPDATE pim SET image".$number."='".$newImage."' where sku = '".$values[0]."';";
    $result = mysqli_query($con, $sql);
}

?>
</div>
</body>
</html>