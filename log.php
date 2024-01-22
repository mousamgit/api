<?php

    /* to use the log function we need to have these variables: 
    $logsku = SKU of product
    $logheader = header of the column changed
    $newrecord = the new value that the product will change to
    */
    date_default_timezone_set("Australia/Sydney");
    $current = strtotime("now");
    $date = date("Y-m-d", $current);
    $time = date("G:i:s", $current);

    $quantityfields = ["master_qty", "warehouse_qty", "mdqty", "psqty", "usdqty", "allocated_qty", "shopify_qty"];

    if(in_array($logheader,$quantityfields)) { $newrecord = number_format($newrecord,"2"); }

    $searchsql = "SELECT sku,$logheader from pim where sku = '$logsku'";
    $searchresult = mysqli_query($con,$searchsql) or die(mysqli_error($con));
    while ($row = mysqli_fetch_array($searchresult, MYSQLI_ASSOC)) {
        $oldrecord = $row[$logheader]; // pull old value
    }

    //echo $date." ".$time." ".$logsku." ".$logheader." ".$oldrecord." ".$newrecord." ".$username."<br>";

    $logsql = " INSERT into pimlog (date,time,sku,field,oldrecord,newrecord,user) VALUES ('$date','$time','$logsku','$logheader','$oldrecord','$newrecord','$username')";
    $logresult = mysqli_query($con,$logsql) or die(mysqli_error($con)); 

?>