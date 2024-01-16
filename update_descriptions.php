<?php
  include 'login_checking.php';
  include 'functions.php';
?>

<html>
<head>
<?php include 'header.php'; ?>
  <title>SGA Marketing - Missing Descriptions or Tags</title>
  <?php include 'header.php'; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/dancss.css">
</head>
<body>
<?php include 'topbar.php'; ?>

<h2>Missing Descriptions or Tags</h2>

<div class="main-box">

<?php

include ('connect.php');

 if(isset($_POST["Submit"])){

    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $collections_2 = $_POST['collections_2'];
    $sku = $_POST['sku'];

    array_walk_recursive($description, function(&$value){
        $value = htmlspecialchars(trim($value)); // remove special characters
    });

    for ($i = 0; $i < count($sku); $i++)
    {

        //TEST - to see if description is the same // if not - log it
        $searchdesc = "SELECT sku,description from pim where sku = '$sku[$i]'";
        $resultdesc = mysqli_query($con,$searchdesc) or die(mysqli_error($con));
        while ($rows = mysqli_fetch_array($resultdesc, MYSQLI_ASSOC)) {
            if ($rows[description] != $description[$i]) { $logsku = $sku[$i]; $logheader = "description"; $newrecord = $description[$i]; include 'log.php'; }
        }
        //TEST - to see if tags is the same // if not - log it
        $searchtags = "SELECT sku,tags from pim where sku = '$sku[$i]'";
        $resulttags = mysqli_query($con,$searchtags) or die(mysqli_error($con));
        while ($rows = mysqli_fetch_array($resulttags, MYSQLI_ASSOC)) {
            if ($rows[tags] != $tags[$i]) { $logsku = $sku[$i]; $logheader = "tags"; $newrecord = $tags[$i]; include 'log.php'; }
        }
        //TEST - to see if collections_2 is the same // if not - log it
        $searchcollection = "SELECT sku,collections_2 from pim where sku = '$sku[$i]'";
        $resultcollection = mysqli_query($con,$searchcollection) or die(mysqli_error($con));
        while ($rows = mysqli_fetch_array($resultcollection, MYSQLI_ASSOC)) {
            if ($rows[collections_2] != $collections_2[$i]) { $logsku = $sku[$i]; $logheader = "collections_2"; $newrecord = $collections_2[$i]; include 'log.php'; }
        }

        $sql = " UPDATE pim SET description='$description[$i]', tags='$tags[$i]', collections_2='$collections_2[$i]'  where sku='$sku[$i]';"; 
        $result = mysqli_query($con, $sql) or die(mysqli_error($con)) ;

        echo "Updated ".$sku[$i];
        echo "<ul>";
        echo "<li><b>Description:</b> ".$description[$i]."</li>";
        echo "<li><b>Tags:</b> ".$tags[$i]."</li>";
        echo "<li><b>Collections 2:</b> ".$collections_2[$i]."</li>";
        echo "</ul><br>";

        $line1 = $sku[$i]."\n";
        $line2 = "Description:". $description[$i]."\n";
        $line3 = "Tags: ". $tags[$i]."\n";
        $line3 = "Collections 2: ". $collections_2[$i]."\n";
        $line5 = "\n\n";

    }
 }
 else{
    echo "Nothing Submitted";
 }
?>

</div>
</body>
</html>