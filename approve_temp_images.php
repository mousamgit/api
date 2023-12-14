<?php
include_once ('connect.php');
$pieces = $_POST['check'];

foreach ($pieces as $key => $value)
{
    //echo $key.":".$value."<br>";
    $values = explode(":", $value);
    if( strpos($values[1],"_") > 0 ) 
    {
        $number = substr(strstr($values[1],'_'),1,1);
    }
    else
    {
        $number = 1; 
    }

    $temp = $_SERVER['DOCUMENT_ROOT']."/temp-images/".$values[1];
    $targetFolder = $_SERVER['DOCUMENT_ROOT']."-images/".$values[1];
    rename($temp, $targetFolder);

    $newImage = "https://samsgroup.info/pim-images/".$values[1];

    echo $values[1]." has been updated. Check it here: <a href='".$newImage."' target='_blank'> Click Here </a><br>";

    $sql = " UPDATE pim SET image".$number."='".$newImage."' where sku = '".$values[0]."';";
    $result = mysqli_query($con, $sql);
}

?>