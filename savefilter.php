<?php
include 'login_checking.php';
include 'functions.php';
$username = $_SESSION["username"];
$filters = getValue('users', 'username', $username, 'filters');
$updatedfilter = $filters;
$geturl = $_SERVER['REQUEST_URI'];


if (strpos($geturl, 'filter=') !== false) {
    $newfilter = valuefromString($geturl, 'filter=', 1);
    $updatedfilter = $filters.$newfilter.',';
}
if(strpos($geturl, 'remove=') !== false) {
    $removefilter = valuefromString($geturl, 'remove=', 1).',';
    $updatedfilter = str_replace($removefilter, '', $filters) ;
}
updateValue('users','username',$username,'filters',$updatedfilter);


?>

<script type="text/javascript">
   window.location = '/profile.php'
  </script>