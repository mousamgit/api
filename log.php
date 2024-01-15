<?php

    /* to use the log function we need to have these variables: 
    $sku = SKU of product
    $header = header of the column changed
    $newvalue = the new value that the product will change to
    */
    date_default_timezone_set("Australia/Sydney");
    $current = strtotime("now");
    $date = date("Y-m-d", $current);
    $time = date("h:i:s", $current);

    $searchsql = "SELECT sku,$header from pim where sku = '$sku'";
    $searchresult = mysqli_query($con,$searchsql) or die(mysqli_error($con));
    while ($row = mysqli_fetch_array($searchresult, MYSQLI_ASSOC)) {
        $oldvalue = $row[$head]; // pull old value
    }

    echo $date." ".$time." ".$sku." ".$header." ".$oldvalue." ".$newvalue." ".$username."<br>";

    $logsql = " INSERT into pimlog (date,time,sku,field,oldrecord,newrecord,user) VALUES ('$date','$time','$sku','$header','$oldvalue','$newvalue','$username')";
    $logresult = mysqli_query($con,$logsql) or die(mysqli_error($con)); 

?>