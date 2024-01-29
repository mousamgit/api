<?php
  include 'login_checking.php';
  include 'functions.php';
  require ('connect.php');
  if(isset($_POST['search-term'])) $searchterm=$_POST['search-term'];
?>
<head>
    <?php include 'header.php'; ?>
    <title>Searching for <?php echo $searchterm; ?></title>
</head>
<?php include 'topbar.php'; ?>

<center><h2>Search results for <?php echo $searchterm; ?></h2></center>

<?php 

    $sku = strtoupper($searchterm);
    $title = strtolower($searchterm);

    $query = " SELECT * from pim where sku = '$sku' or product_title like '%$title%'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    
    echo "<ul>";
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if($row[sku] == $sku)
        {
            echo "<li><b>". $row[sku] ."</b> - ". $row[product_title] ." - [ <a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>View Product</a> ]</li>";
        }
        else{
            echo "<li><b>". $row[product_title] ."</b> - ". $row[sku] ." - [ <a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>View Product</a> ]</li>";
        }
    }

?>

