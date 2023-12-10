<?php
include_once ('connect.php');

$result = mysqli_query($con,"DELETE FROM pim WHERE deletion = 1") or die(mysqli_error($con));

?>