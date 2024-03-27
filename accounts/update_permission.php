<?php

require('../connect.php');
include '../functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];
$addproduct = truefalse($_POST['addproduct']);

    
updateValue('permissions','role',$role,'addproduct',$addproduct);
echo "New record created successfully";
header("Location: https://pim.samsgroup.info/accounts/account_list.php");


exit();

}
else{
    echo 'error';
}
function truefalse($value){
    if($value){ return 1;}
    else{return 0;}
}

?>
