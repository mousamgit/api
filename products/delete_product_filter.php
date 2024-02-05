<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$deleteProductFilterQuery = "DELETE FROM product_filter WHERE id=" . $data['productDetId'];
$filterCondition='where 1=1';

if ($con->query($deleteProductFilterQuery) === TRUE) {
    $new_filter_data = $con->query("select * from product_filter where product_id =". $data['productId']);
    if ($new_filter_data->num_rows > 1) {
        while ($attribute_value = $new_filter_data->fetch_assoc()) {
            if ($attribute_value["filter_type"] == "=") {
                $filterCondition .= " ".$attribute_value['op_value'].' '.$attribute_value["attribute_name"].' = "'.$attribute_value['attribute_condition'].'"';
            } elseif ($attribute_value["filter_type"] == "!=") {
                $filterCondition .= " ".$attribute_value['op_value'].' '.$attribute_value["attribute_name"].' != "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == ">") {
                $filterCondition .= " ".$attribute_value['op_value'].' '.$attribute_value["attribute_name"].' > "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == "<") {
                $filterCondition .= " ".$attribute_value['op_value'].' '.$attribute_value["attribute_name"].' < "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == "includes") {
                $filterCondition .= " ".$attribute_value['op_value'].' '.$attribute_value["attribute_name"].' like "%'.$attribute_value['attribute_condition'].'%"';
            } elseif ($attribute_value['filter_type'] == 'between') {
                $filterCondition .= " ".$attribute_value['op_value']." ".$attribute_value['attribute_name']." between ".$attribute_value['range_from']." and ".$attribute_value['range_to']."";
            } else {
                $filterCondition .= " ".$attribute_value['op_value']." LENGTH(".$attribute_value['attribute_name'].") > 0";
            }
        }
    }
    elseif ($new_filter_data->num_rows ==1) {
        $opval= 'AND';
        while ($attribute_value = $new_filter_data->fetch_assoc()) {
            $con->query("update product_filter set op_value =".$opval." where product_id=".$data['productId']);
            if ($attribute_value["filter_type"] == "=") {
                $filterCondition .= " ".$opval.' '.$attribute_value["attribute_name"].' = "'.$attribute_value['attribute_condition'].'"';
            } elseif ($attribute_value["filter_type"] == "!=") {
                $filterCondition .= " ".$opval.' '.$attribute_value["attribute_name"].' != "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == ">") {
                $filterCondition .= " ".$opval.' '.$attribute_value["attribute_name"].' > "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == "<") {
                $filterCondition .= " ".$opval.' '.$attribute_value["attribute_name"].' < "'.$attribute_value['attribute_condition'].'"';
            }
            elseif ($attribute_value["filter_type"] == "includes") {
                $filterCondition .= " ".$opval.' '.$attribute_value["attribute_name"].' like "%'.$attribute_value['attribute_condition'].'%"';
            } elseif ($attribute_value['filter_type'] == 'between') {
                $filterCondition .= " ".$opval." ".$attribute_value['attribute_name']." between ".$attribute_value['range_from']." and ".$attribute_value['range_to']."";
            } else {
                $filterCondition .= " ".$opval." LENGTH(".$attribute_value['attribute_name'].") > 0";
            }
        }

    }

    $updateProduct = $con->query("update products set filter_condition='".$filterCondition."' where id =".$data['productId']);
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
