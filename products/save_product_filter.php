<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

//  database connection
require_once('../connect_mousam.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

$currentDateTime = date("Y-m-d H:i:s");


// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$productId = $queryParameters['id'] ?? 2;


$filterCondition = "where 1=1";
$check_if_filter_exists = $con->query("select filter_condition from products where id =".$productId);

if ($check_if_filter_exists->num_rows > 0) {
    while ($row = $check_if_filter_exists->fetch_assoc()) {
        if($row['filter_condition'] != '')
        {
            $filterCondition = $row['filter_condition'];
        }

    }
}


        foreach ($data["attribute"] as $key => $attribute_value) {
            if ($attribute_value["filter_type"] == "=") {
                $filterCondition .= ' and '.$attribute_value["attribute_name"].' = "'.$attribute_value['attribute_condition'].'"';
            } elseif ($attribute_value["filter_type"] == "includes") {
                $filterCondition .= ' and '.$attribute_value["attribute_name"].' like "%'.$attribute_value['attribute_condition'].'%"';
            } elseif ($attribute_value['filter_type'] == 'between') {
                $filterCondition .= " and ".$attribute_value['attribute_name']." between ".$attribute_value['rangeFrom']." and ".$attribute_value['rangeTo']."";
            } else {
                $filterCondition .= " and LENGTH(".$attribute_value['attribute_name'].") > 0";
            }
        }
        foreach ($data["attribute"] as $key => $attribute_value) {
            $attribute_name = $attribute_value['attribute_name'];
            $filter_type = $attribute_value['filter_type'];
            $data_type = $attribute_value['data_type'];
            $range_from = '';
            $range_to = '';
            $attribute_condition = '';
            if ($attribute_value["filter_type"] == "=" || $attribute_value["filter_type"] == "includes") {
                $attribute_condition = $attribute_value['attribute_condition'];
            } elseif ($attribute_value['filter_type'] == 'between') {
                $range_from = $attribute_value['rangeFrom'];
                $range_to = $attribute_value['rangeTo'];
            } else {
                $attribute_condition = 'IS NOT NULL';
            }
            $sql = "INSERT INTO product_filter (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`) 
                VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type','AND')";

            if ($con->query($sql) === FALSE) {
                $success = false;
                break; // Break the loop if one of the queries fails
            }

            $sql2 = "update products set filter_condition ='".$filterCondition."' where id =".$productId;

            $con->query($sql2);
            $success=true;
            break;
        }


if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
