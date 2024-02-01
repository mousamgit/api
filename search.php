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
    <div class="container">

    <div class="search-div desktop-only">
        <form action="search.php" method="post" name="searchpim" >
            <input type="text" class="search-input" name="search-term" placeholder="Search another SKU or Product Name"></input>
            <input type="submit" class="search-submit" value="Submit">
        </form>
    </div>    
        <?php
            $count = mysqli_num_rows($result);
            if($count != 0){
        ?>
        <center><h2 class="desktop-only">Search results for <?php echo $searchterm; ?></h2></center>
        <table class="sga-table">
            <thead>
                <tr>
                    <th class="desktop-only">SKU</th>
                    <th class="desktop-only">Product Name</th>
                    <th class="mobile-only">Search Results for <?php echo $searchterm; ?></th>
                </tr>
            </thead>
            <tbody>
        <?php
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<tr>";
                        if($row[sku] == $sku){ 
                            echo "<td class='desktop-only'><b><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a></b></td><td class='desktop-only'>". $row[product_title] ."</td>";
                            echo "<td class='mobile-only'><b><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a></b><br><br>". $row[product_title] ."</td>";
                        }
                        else{ 
                            echo "<td class='desktop-only'><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a></td><td class='desktop-only'><b>". $row[product_title] ."</b></td>"; 
                            echo "<td class='mobile-only'><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a><br><br><b>". $row[product_title] ."</b></td>";
                        }
                        echo "</tr>";
                }
                echo "</tbody></table>";
            }
            else{ echo "<br><br><center>The query ". $searchterm . " returned no results.</center>";}
        ?>
        </div>

        


