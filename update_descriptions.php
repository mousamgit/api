<html>
<head>
  <title>SGA Marketing - Missing Descriptions or Tags</title>
  <?php include 'header.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; text-align:center; }
    .top-bar { display:block; padding:20px; text-align:left; background-color:#fafafa; }
    .main-box { width:600px; margin:0 auto; padding:50px; text-align:left;}
    .button { margin: 20px; display: inline-block; padding: 10px 20px; text-align: center; text-decoration: none; font-size: 16px; font-family: Helvetica; cursor: pointer; border: 1px solid #3498db; color: #3498db; border-radius: 5px; transition: background-color 0.3s; margin-bottom:20px; }
    .button-dark { background-color:#f1f1f1;}
    .button-white { background-color:#fff; }
    table {border: 1px solid #000; width:100%;}
    th, td { border: 1px solid #000; padding:10px; font-size:12px;}
  </style>
</head>
<body>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div>

<h2>Missing Descriptions or Tags</h2>

<div class="main-box">

<?php

include_once ('connect.php');

 if(isset($_POST["Submit"])){
    date_default_timezone_set("Australia/Sydney");
    $current = strtotime("now");
    $timestamp = date("Y-m-d-h-i-s", $current);
    $logname = $timestamp."-descriptions-tags.txt";
    $logfile = dirname(__DIR__) ."/log/marketing/".$logname;
    
    $log = fopen($logfile, "w") or die ("Unable to log to file");

    $line = "Time of Log: ".$timestamp."\n\n";
    fwrite($log,$line);
    
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $sku = $_POST['sku'];

    array_walk_recursive($description, function(&$value){
        $value = htmlspecialchars(trim($value)); // remove special characters
    });

    for ($i = 0; $i < count($sku); $i++)
    {
        $sql = " UPDATE pim SET description='$description[$i]', tags='$tags[$i]' where sku='$sku[$i]';"; 
        $result = mysqli_query($con, $sql) or die(mysqli_error($con)) ;

        echo "Updated ".$sku[$i];
        echo "<ul>";
        echo "<li><b>Description:</b> ".$description[$i]."</li>";
        echo "<li><b>Tags:</b> ".$tags[$i]."</li>";
        echo "</ul><br>";

        $line1 = $sku[$i]."\n";
        $line2 = "Description:". $description[$i]."\n";
        $line3 = "Tags: ". $tags[$i]."\n";
        $line4 = "\n\n";

        fwrite($log,$line1);
        fwrite($log,$line2);
        fwrite($log,$line3);
        fwrite($log,$line4);

    }
    fclose($log);
 }
 else{
    echo "Nothing Submitted";
 }

?>

</div>
</body>
</html>fclose($myfile);