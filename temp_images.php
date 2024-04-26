<?php
  include 'login_checking.php';
  include 'functions.php';
?>

<html>
<head>
  <?php include 'header.php'; ?>
  <title>Images Uploaded</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/dancss.css">
  <script>
jQuery(document).ready(function ($) {
    $('.selectall').click(function(){
        if($(this).prop("checked")){
            $('.main-box input[type="checkbox"]').each(function(){
                $(this).prop("checked", true);
            });
        }
        else{
            // $(this).closest('tr').find('input[type="checkbox"]').each(function(){
            $('.main-box input[type="checkbox"]').each(function(){
                $(this).prop("checked", false);
            });
        }
 
    });
});
$(function() {
                $('.delete_button').click(function() {
                    return window.confirm("Are you sure you'd like to delete this image?");
                });
            });
  </script>
</head>
<body>
<?php include 'topbar.php'; ?>

<center><h2>Approve Uploaded Images</h2></center>

<div class="main-box">
<form action="approve_temp_images.php" method="post" name="approve_images" enctype="multipart/form-data">

    <table class="sga-table producttable" >
        <thead>
        <tr>
            <th>SKU</th>
            <th>Image 1</th>
            <th>Image 2</th>
            <th>Image 3</th>
            <th>Image 4</th>
            <th>Image 5</th>
            <th>Image 6</th>
            <th>Approve All <input type="checkbox" value="" name="check[]" id="selectall" class="selectall"></th>
        </tr>
        </thead>
        <tbody>
            <?php
                include ('connect.php');

                $dir = "temp-images/";
                $files = scandir($dir);
                unset($files[0]);
                unset($files[1]);

                //var_dump(array_values($files));

                //get SKUs into SKU array
                foreach ($files as $key)
                {
                    if(!$itemArray)
                    {
                        if( strpos($key,"_") > 0 ) 
                        {
                            $sku = strstr($key,'_',true); // find _ and remove everything after
                            $number = substr(strstr($key,'_'),1,1);
                        }
                        else
                        {
                            $sku = strstr($key,'.',true); // find . and remove everything after
                            $number = 1; 
                        }
                        $itemArray[$sku][$number] = $key;
                    }
                    else
                    {
                        if( strpos($key,"_") > 0 ) 
                        {
                            $sku = strstr($key,'_',true); // find _ and remove everything after
                            $number = substr(strstr($key,'_'),1,1);
                        }
                        else
                        {
                            $sku = strstr($key,'.',true); // find . and remove everything after
                            $number = 1; 
                        }
                        $itemArray[$sku][$number] = $key;
                    }
                    
                }

                $keys = array_keys($itemArray);

                for ($i=0; $i < count($itemArray); $i++)
                {
                    $sql = "SELECT sku FROM pim where sku ='".$keys[$i]."';";
                    $result = mysqli_query($con, $sql);
                    echo "<tr><td>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row[sku] == $keys[$i])
                        {
                            echo "<span class='exists'> ✅ SKU exists in database</span><br>";
                        }
                        else
                        {
                            echo "<span class='warning'>⛔ WARNING: SKU does not exist in database</span><br>";
                        }
                    }

                    echo $keys[$i]."</td>";

                    for ($a=1; $a<=6; $a++)//add "No Image" text to keys without images
                    {
                        if (empty($itemArray[$keys[$i]][$a]))
                        {
                            $itemArray[$keys[$i]][$a] = "No Image";
                        }
                    }
                    
                    ksort($itemArray[$keys[$i]]); // sort all keys numerically

                    foreach($itemArray[$keys[$i]] as $key => $value )
                    {
                        if ($value != "No Image")
                        {
                            $version = Date("Y.m.d.G.i.s");
                            $value1 = $value . "?v=" . $version;
                            echo "<td><label for ='".$value."'>
                                <div style='display:table-cell; vertical-align:middle; padding-right:20px;'> <input type='checkbox' value='".$keys[$i].":".$value."' name='check[]' id='".$value."' class='".$keys[$i]."' /> </div>
                                <div class='image-box' style='display:table-cell; vertical-align:middle;'><img src='https://pim.samsgroup.info/temp-images/".$value1."'></div>
                                <div style='text-align:center; width:100%; font-size:10px; margin-top:10px;'><a href='/delete_tempimage.php?id=".$value."' class='delete_button' style='color:red;'><i class='fa-solid fa-trash-can'></i> Delete this image</a></div>
                                </label>
                                </td>";
                        }
                        else
                        {
                            echo "<td></td>";
                        }
                    }
                    echo "<td align=center><input type='checkbox' name='checkAll' class='checkAll' sku='".$keys[$i]."'></td>";
                    echo "</tr>";
                }
                
            ?>
        </tbody>
    </table>
    <br>
    <center><button type="submit" id="submit" name="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Submit Approved Images</button></center>
    </form>
</div>
</body>
</html>