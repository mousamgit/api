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



<?php 

    $sku = strtoupper($searchterm);
    $title = strtolower($searchterm);

    $query = " SELECT * from pim where sku = '$sku' or product_title like '%$title%'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    ?>
    
    <div class="search-div">
        <form action="search.php" method="post" name="searchpim" >
            <label for="fname">Search Again: </label>
            <input type="text" class="search-input" name="search-term" placeholder="Type in SKU or Product Name"></input>
            <input type="submit" class="search-submit" value="Submit">
        </form>
    </div>    
        <?php
            $count = mysqli_num_rows($result);
            if($count != 0){
        ?>
        <center><h2>Search results for <?php echo $searchterm; ?></h2></center>
        <table class="sga-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
        <?php
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<tr>";
                        if($row[sku] == $sku){ echo "<td><b>". $row[sku] ."</b></td><td>". $row[product_title] ."</td><td>[ <a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>View Product</a> ]</td>"; }
                        else{ echo "<td>". $row[sku] ."</td><td><b>". $row[product_title] ."</b></td><td>[ <a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>View Product</a> ]</td>"; }
                        echo "</tr>";
                }
                echo "</tbody></table>";
            }
            else{ echo "<br><br><center>The query ". $searchterm . " returned no results.</center>";}
        ?>
        


