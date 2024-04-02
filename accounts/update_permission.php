<?php

require('../connect.php');
include '../functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];

    $fields = [
        'addproduct' => $_POST['addproduct'],
        'deleteproduct' => $_POST['deleteproduct'],
        // Add more fields as needed
    ];
    

    foreach ($fields as $field => $value) {
        $roleValue = truefalse($value);
        updateValue('permissions', 'role', $role, $field, $roleValue);
    }




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
