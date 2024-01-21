<?php
include 'login_checking.php';
include 'functions.php';
$username = $_SESSION["username"];
$filters = getValue('users', 'username', $username, 'filters');

$newfilter = valuefromString($_SERVER['REQUEST_URI'], 'filter=', 1);
$updatedfilter = $filters.$newfilter.',';
updateValue('users','username',$username,'filters',$updatedfilter);
echo $updatedfilter;

?>