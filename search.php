<?php
  include 'login_checking.php';
  include 'functions.php';
  require ('connect.php');
  if(isset($_GET['val'])) 
  {
    $searchterm=$_GET['val'];
  }
  elseif (isset($_GET["var"]))
  {
    $searchterm=$_GET["var"];
  }
?>
<head>
    <?php include 'header.php'; ?>
    <title>Searching for <?php echo $searchterm; ?></title>
    <script>
        function toggle_div(id) {
            var divelement = document.getElementById(id);
            if(divelement.style.display == 'block')
                divelement.style.display = 'none';
            else
                divelement.style.display = 'block';
            }
    </script>
</head>
<?php include 'topbar.php'; ?>

<?php 

    $sku = strtoupper($searchterm);
    $title = strtolower($searchterm);

    $query = " SELECT * from pim where sku like '%$sku%' or product_title like '%$title%'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    ?>
    <div style="padding:0px 20px;">
    
    <div class="search-div desktop-only">
    <button  onclick="toggle_div('select_columns');" style="display:inline-block;">Edit Columns</button>    
        <form action="search.php" method="get" name="searchpim" style="display:inline-block;">
            <input type="text" class="search-input" name="val" placeholder="Search another SKU or Product Name"></input>
            <input type="submit" class="search-submit" value="Submit">
            
        </form>
    </div> 
    <div class="mobile-only">
        <button  onclick="toggle_div('select_columns');" style="display:inline-block;">Edit Columns</button> 
    </div> 
        <?php
            $count = mysqli_num_rows($result);
            if($count != 0){
            $columnsql = "SELECT searchcolumns FROM users where username = '$username'";
            $columnresult = mysqli_query($con, $columnsql) or die(mysqli_error($con));
            $columnrow = mysqli_fetch_array($columnresult, MYSQLI_ASSOC);
            $columns = explode(',',$columnrow[searchcolumns]);
        ?>
        <div id="select_columns" style="display:none; text-align:center; background-color:#f9f9f9; padding: 20px 50px; margin: 50px auto;">
            <form action="update_search_columns.php" method="post" enctype="multipart/form-data">
                <b>Select your Columns</b><br><br>
                    <input type="hidden" id="user" name="user" value="<?php echo $username;?>">
                    <input type="hidden" id="searchterm" name="searchterm" value="<?php echo $searchterm;?>" >
                    <input type="checkbox" name="product_title" value="product_title" <?php if(in_array("product_title",$columns)){ echo "checked"; } ?>> Product Title&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="quantities" value="quantities" <?php if(in_array("quantities",$columns)){ echo "checked"; } ?>> Quantities&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="retail" value="retail" <?php if(in_array("retail",$columns)){ echo "checked"; } ?>> Retail Prices<br>
                    <input type="checkbox" name="specifications" value="specifications" <?php if(in_array("specifications",$columns)){ echo "checked"; } ?>> Specifications&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="tags" value="tags" <?php if(in_array("tags",$columns)){ echo "checked"; } ?>> Tags&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="wholesale" value="wholesale" <?php if(in_array("wholesale",$columns)){ echo "checked"; } ?>> Wholesale Prices<br><br>
                    <button type="submit" id="submit" name="Submit" class="submit-btn">Update Columns</button>
            </form>
        </div>  
        <center><h2 class="desktop-only">Search results for <?php echo $searchterm; ?></h2></center>
        <table class="sga-table">
            <thead>
                <tr>
                    <th class="desktop-only">SKU</th>
                    <?php if(in_array("product_title",$columns)){ ?> <th class="desktop-only">Product Name</th> <?php } ?>
                    <?php if(in_array("quantities",$columns)){ ?> <th class="desktop-only">Quantities</th> <?php } ?>
                    <?php if(in_array("specifications",$columns)){ ?> <th class="desktop-only">Specifications</th> <?php } ?>
                    <?php if(in_array("tags",$columns)){ ?> <th class="desktop-only">Tags</th> <?php } ?>
                    <?php if(in_array("wholesale",$columns)){ ?> <th class="desktop-only">Wholesale</th> <?php } ?>
                    <?php if(in_array("retail",$columns)){ ?> <th class="desktop-only">Retail Prices</th> <?php } ?>
                    <th class="mobile-only">Search Results for <?php echo $searchterm; ?></th>
                </tr>
            </thead>
            <tbody>
        <?php


                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<tr>";

                            echo "<td class='desktop-only'><b><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a></b></td>";
                            if(in_array("product_title",$columns)){ echo "<td class='desktop-only'>". $row[product_title] ."</td>"; }
                            if(in_array("quantities",$columns)){ echo "<td class='desktop-only'> WH: ". $row[warehouse_qty] ."<br> MD: ". $row[mdqty] . "<br> PS: ". $row[psqty] . "</td>"; }
                            if(in_array("specifications",$columns)){ echo "<td class='desktop-only'>". $row[specifications] ."</td>"; }
                            if(in_array("tags",$columns)){ echo "<td class='desktop-only'>". $row[tags] ."</td>"; }
                            if(in_array("wholesale",$columns)){ if(strpos(strtolower($row[type]), "loose") !== false) { echo "<td class='desktop-only'> W/S PPC: $" . $row[wholesale_aud] . "<br> W/S Stone: $" . $row[stone_price_wholesale_aud] . "</td>";  } else { echo "<td class='desktop-only'> W/S ex GST: $" . $row[wholesale_aud] . "</td>"; }  }
                            if(in_array("retail",$columns)){ if(strpos(strtolower($row[type]), "loose") !== false) { echo "<td class='desktop-only'> RRP PPC: $" . $row[retail_aud] . "<br> RRP Stone: $" . $row[stone_price_retail_aud] . "</td>";  } else { echo "<td class='desktop-only'> RRP ex GST: $" . $row[retail_aud] . "</td>"; }  }
                            echo "<td class='mobile-only'><b><a href='https://pim.samsgroup.info/product.php?sku=".$row[sku]."'>". $row[sku] ."</a></b><br><br>". $row[product_title] ."</td>";

                     echo "</tr>";
                }
                echo "</tbody></table>";
            }
            else{ echo "<br><br><center>The query ". $searchterm . " returned no results.</center>";}
        ?>
        </div>

        


