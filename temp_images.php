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
    .main-box { width:100%; margin:0 auto; padding:50px; text-align:center; }
    table {border: 1px solid #000; width:100%;}
    th, td { width: 12.5%; height: 12.5%; border: 1px solid #000; padding:10px;}
    input[type=checkbox] { height:20px; width:20px; }
    input.checkAll { height: 100px; width: 100px; }
    .image-box:hover img { transform: scale(2); transition: all 0.3s ease-in-out;}
    .exists { color:green; font-size:8px; }
    .warning { color:red; font-size:8px; }
  </style>
  <script>
jQuery(document).ready(function ($) {
    $('.checkAll').click(function(){
        if($(this).prop("checked")){
            $(this).closest('tr').find('input[type="checkbox"]').each(function(){
                $(this).prop("checked", true);
            });
        }
        else{
            $(this).closest('tr').find('input[type="checkbox"]').each(function(){
                $(this).prop("checked", false);
            });
        }

        
    });
});
  </script>
</head>
<body>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div>

<h2>Check Images and Upload</h2>

<div class="main-box">
<form action="approve_temp_images.php" method="post" name="approve_images" enctype="multipart/form-data">

    <table>
        <tr>
            <th>SKU</th>
            <th>Image 1</th>
            <th>Image 2</th>
            <th>Image 3</th>
            <th>Image 4</th>
            <th>Image 5</th>
            <th>Image 6</th>
            <th>Approve All</th>
        </tr>
            <?php
                include_once ('connect.php');

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
                            echo "<td>
                                <input type='checkbox' value='".$keys[$i].":".$value."' name='check[]' class='".$keys[$i]."' /><br><br>
                                <div class='image-box'><img src='https://pim.samsgroup.info/temp-images/".$value."'></div>
                                
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
    </table>
    <br>
    <button type="submit" id="submit" name="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Submit Approved Images</button>
    </form>
</div>
</body>
</html>