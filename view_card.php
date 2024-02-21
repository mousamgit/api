<?php
    require 'connect.php';
    $id = "SDE-RDDSWT001";
    $query = " SELECT * from pim WHERE sku = '".$id."'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

      while($row = mysqli_fetch_assoc($result)){
        
?>
<head>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; }
    </style>
</head>
<body>
    <div style="width:323px; height: 203px; display:flex; align-items:center;letter-spacing:0.2px;">
        <div style="width:150px; height: 169px; margin:0 auto; background-color:white; display:flex; align-items:center;">
            <img src="<?php echo $row[image1]; ?>" width=120%>
        </div>
        <div style="Width:173px; height:169px; margin:0 auto; background-color:white; padding:10px;">
            <div style="width:173px; height: 139px; margin:0 auto; background-color:white;">
                <div style="font-size:14px; text-transform: uppercase; margin-top:10px; margin-bottom:10px; font-weight:600;"><?php echo $row[sku]; ?></div>
                <div style="font-size:10px; text-transform: uppercase; font-weight:500;"><?php echo $row[product_title] ?></div><br>
                <div style="font-size:8px; text-transform: uppercase;"><?php echo $row[specifications]; ?></div>
            </div>
            <div style="width:173px; height: 30px; margin:0 auto; background-color:white;">
                <div style="font-size:6px;">ALL SAPPHIRE DREAMS SAPPHIRES ARE NATURAL,</div>
                <div style="font-size:6px; margin-bottom: 10px;">ETHICALLY SOURCED AND OF AUSTRALIAN ORIGIN.</div>
                <div style="font-size:6px;">ANY IMAGE PROVIDED IS INDICATIVE OF STYLE ONLY.</div>
            </div>
        </div>
    </div>

<?php

      }
?>
</body>