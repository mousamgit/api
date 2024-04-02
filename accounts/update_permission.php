<?php

require('../connect.php');
include '../functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'];

    $fields = [
        'addproduct' => truefalse($_POST['addproduct']),
        'deleteproduct' => truefalse($_POST['deleteproduct']),
        'editproduct' => readCheckBox($_POST['editproduct']),
        'showcolumns' => readCheckBox($_POST['showcolumns']),

    ];


    // updateValue('permissions', 'role', $role, 'editproduct', $selectedColumnsArray);

    foreach ($fields as $field => $value) {
        updateValue('permissions', 'role', $role, $field, $value);
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
function readCheckBox($value){
    $selectedColumnsArray = '';
    if (isset($value) && is_array($value)) {
        // Loop through the selected checkboxes
        foreach ($value as $selectedColumn) {
            $selectedColumnsArray .= '"'.$selectedColumn.'",';
        }
    }
    return $selectedColumnsArray;
}

?>
