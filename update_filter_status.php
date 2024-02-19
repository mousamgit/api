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
}
else
{
    $filterConditions = [];
    $groupedConditions = [];
    $filterConditionCombined = '';
    $whereValue = 'WHERE 1=1 AND';
    $filterFetch = $con->query("SELECT * FROM product_filter WHERE status =1 and user_name = '".$_SESSION['username']."' and product_id=" . $productId . " ORDER BY index_no ASC");
    $user_filter_check = $con->query("select id from user_filter_details where user_name = '".$_SESSION['username']."'");
    $filter_id_array =[];
    if($user_filter_check->num_rows >0)
    {
        while ($userFilter = $user_filter_check->fetch_assoc())
        {
            $filter_id_array[] = $userFilter['filter_id'];
        }
    }

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
            $productId=0;
            $filter_type=$prevAttributeValue['filter_type'];
            $attribute_name=$prevAttributeValue['attribute_name'];
            $attribute_condition=$prevAttributeValue['attribute_condition'];
            $range_from=$prevAttributeValue['range_from'];
            $range_to=$prevAttributeValue['range_to'];
            $data_type_value=$prevAttributeValue['data_type_value'];
            $op_value=$prevAttributeValue['op_value'];
            $index_no=$prevAttributeValue['index_no'];
            $user_nameo=$prevAttributeValue['user_name'];
            $filter_id_product=$prevAttributeValue['id'];

             if(!in_array($filter_id_product,$filter_id_array))
             {
             $sql1 = "INSERT INTO user_filter_details (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`,`index_no`,`user_name`,`id`) 
              VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type_value','$op_value','$index_no','$user_name','$filter_id_product')";
                 $con->query($sql1);
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
    $user_id = getValue('users','username', $user_name,'id');

    $filterConditionCombined = str_replace("AND () OR", "AND", $filterConditionCombined);
    $sql = "INSERT INTO user_filters (`user_id`, `filter_value`) 
                VALUES ('$user_id', '$filterConditionCombined')";
}


if ($con->query($sql) === TRUE ) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}


// Close the database connection
$con->close();
?>
