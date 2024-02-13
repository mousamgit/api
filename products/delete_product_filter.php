<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);


$deleteProductFilterQuery = "DELETE FROM product_filter WHERE id=" . $data['productDetId'];

$deleting_row = $con->query("select op_value,index_no from product_filter where id=".$data['productDetId']);


if($deleting_row->num_rows >0)
{
    while ($d_value = $deleting_row->fetch_assoc()) {
        $opvalue = $d_value['op_value'];$filterCondition='where 1=1';
        $indexNo = $d_value['index_no'];
    }
    if($opvalue == 'OR')
    {
        $con->query("update product_filter set op_value ='OR' where product_id =". $data['productId']." and index_no > ".$indexNo." limit 1");
    }
}

if ($con->query($deleteProductFilterQuery) === TRUE) {
    $check_if_it_is_single_row = $con->query("select id,op_value from product_filter where product_id =". $data['productId']);
    if($check_if_it_is_single_row->num_rows == 1)
    {
        while ($sing_row_data = $check_if_it_is_single_row->fetch_assoc()) {
            $con->query("update product_filter set op_value ='AND' where id =" . $sing_row_data['id']);
        }
    }
    $success=true;
} else {
    $success=false;
}
if ($success == true) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
