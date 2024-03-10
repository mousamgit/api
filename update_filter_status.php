<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// save_channel.php

// Include your database connection logic here
require_once('./connect.php');
require_once('./login_checking.php');
require_once('./functions.php');

// Get the POST data from the Vue.js application
$data = json_decode(file_get_contents("php://input"), true);

// Extract channel data
$productId = 0;
$user_name = $_SESSION['username'];

// Getting the referring URL
$currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// parse_url to extract query parameters
$urlParts = parse_url($currentUrl);

parse_str($urlParts['query'] ?? '', $queryParameters);

// Extracting the channel_id parameter
$value = $data['value'];
if($value ==0)
{
   $sql="update product_filter set status =0 where product_id =".$productId." and user_name ='".$user_name."'";
   $sql1="update user_columns set status =0 where filter_from =0 and user_name ='".$user_name."'";
   $con->query($sql);
   $con->query($sql1);
   $success=true;
}
else
{
    $filterConditions = [];
    $groupedConditions = [];
    $filterConditionCombined = '';
    $whereValue = 'WHERE 1=1 AND';
    $filterFetch = $con->query("SELECT * FROM product_filter WHERE status =1 and user_name = '".$_SESSION['username']."' and product_id=" . $productId . " ORDER BY index_no ASC");
    $filter_id_array =[];

    if ($filterFetch->num_rows > 0) {
        while ($prevAttributeValue = $filterFetch->fetch_assoc()) {
            switch ($prevAttributeValue["filter_type"]) {
                case "=":
                case "!=":
                case ">":
                case "<":
                    $condition = $prevAttributeValue['attribute_name'] . ' ' . $prevAttributeValue["filter_type"] . ' "' . $prevAttributeValue['attribute_condition'] . '"';
                    break;
                case "includes":
                    $condition = $prevAttributeValue['attribute_name'] . ' LIKE "%' . $prevAttributeValue['attribute_condition'] . '%"';
                    break;
                case "between":
                    $condition = $prevAttributeValue['attribute_name'] . ' BETWEEN ' . $prevAttributeValue['range_from'] . ' AND ' . $prevAttributeValue['range_to'];
                    break;
                default:
                    $condition = 'LENGTH(' . $prevAttributeValue['attribute_name'] . ') > 0';
                    break;
            }

            if ($prevAttributeValue['op_value'] == 'OR') {
                $filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
                $groupedConditions = [$condition];
            } else {
                $groupedConditions[] = $condition;
            }

        }
        if (!empty($groupedConditions)) {
            $filterConditions[] = '(' . implode(' AND ', $groupedConditions) . ')';
        }

        $filterCondition = implode(' OR ', $filterConditions);
        $filterConditionCombined = $whereValue . ' ' . $filterCondition;

    }

    if (empty($filterConditionCombined)) {
        $filterConditionCombined = 'WHERE 1=1';
    }
    $user_name = $_SESSION['username'];
    $filter_name = $data['filter_name'];
    $user_id = getValue('users','username', $user_name,'id');

    $filterConditionCombined = str_replace("AND () OR", "AND", $filterConditionCombined);
    $sql = "INSERT INTO user_filters (`user_id`, `filter_value`, `filter_name`) 
                VALUES ('$user_id', '$filterConditionCombined', '$filter_name')";


    if ($con->query($sql) === TRUE ) {
        $filter_no = $con->insert_id;

        $con->query("update user_columns set status =0 where filter_from =0 and user_name ='".$user_name."'");
        $con->query("update product_filter set status =0 where product_id=0 and user_name ='".$user_name."'");

        $product_details = $data['product_details'];

        foreach ($product_details as $pkey=> $value)
        {
            $column_name = $value['attribute_name'];
            $order_no = maxOrderNo('user_columns');
            $product_detail_id = $value['id'];
            $con->query("update product_filter set status =1 where product_id =0 and id ='".$value['id']."'");

//            $con->query("update user_columns set status =1, filter_no=".$filter_no." where filter_from =0 and product_detail_id ='".$value['id']."' and filter_no =0");

            $con->query("INSERT INTO user_columns (`user_name`, `column_name`, `order_no`, `status`, `filter_from`, `product_detail_id`, `filter_no` )
                VALUES ('$user_name', '$column_name', $order_no, 1, 0, $product_detail_id,$filter_no)");
        }
        $success=true;
    }

}
if ($success==true) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

$con->close();
?>
