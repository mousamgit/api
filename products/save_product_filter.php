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
$op_value='AND';

if ($check_if_filter_exists->num_rows > 0) {
 while ($row = $check_if_filter_exists->fetch_assoc()) {
    if($row['filter_condition'] != '')
      {
       $filterCondition = $row['filter_condition'];
      }
    }
 }

        $PfilterCondition='';
        foreach ($data["attribute"] as $key => $attribute_value) {
            $attribute_name = $attribute_value['attribute_name'];
            $filter_type = $attribute_value['filter_type'];
            $data_type = $attribute_value['data_type'];
            $operator = $attribute_value['operator'];
            $range_from = '';
            $range_to = '';
            $attribute_condition = '';
            if ($attribute_value["filter_type"] == "=" || $attribute_value["filter_type"] == "!=" || $attribute_value["filter_type"] == ">" || $attribute_value["filter_type"] == "<"  || $attribute_value["filter_type"] == "includes") {
                $attribute_condition = $attribute_value['attribute_condition'];
            } elseif ($attribute_value['filter_type'] == 'between') {
                $range_from = $attribute_value['rangeFrom'];
                $range_to = $attribute_value['rangeTo'];
            } else {
                $attribute_condition = '';
            }

            $indexNo=1;
            if(count($attribute_value['previous_row'])>0)
            {
            $indexNo = intval($attribute_value['previous_row']['index_no']) + 1;
            }
            $sql = "INSERT INTO product_filter (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`,`index_no`) 
                VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type','$operator','$indexNo')";

            if($con->query($sql) == TRUE)
            {
                if(!($attribute_value['condition_type'] =='group' || $attribute_value['condition_type'] =='normal')) {
                    $update_product_filter = "update product_filter set index_no=index_no+1 where index_no>=" . $indexNo . " and id !=" . $con->insert_id;
                    $con->query($update_product_filter);
                }

                    if($attribute_value['condition_type'] == 'normal')
                    {
                        if ($attribute_value["filter_type"] == "=") {
                            $filterCondition .= " ".$attribute_value['operator'].' ('.$attribute_value["attribute_name"].' = "'.$attribute_value['attribute_condition'].'")';
                        }
                        elseif ($attribute_value["filter_type"] == "!=") {
                            $filterCondition .= " ".$attribute_value['operator'].' ('.$attribute_value["attribute_name"].' != "'.$attribute_value['attribute_condition'].'")';
                        }
                        elseif ($attribute_value["filter_type"] == ">") {
                            $filterCondition .= " ".$attribute_value['operator'].' ('.$attribute_value["attribute_name"].' >  '.$attribute_value['attribute_condition'].')';
                        }
                        elseif ($attribute_value["filter_type"] == "<") {
                            $filterCondition .= " ".$attribute_value['operator'].' ('.$attribute_value["attribute_name"].' < '.$attribute_value['attribute_condition'].')';
                        }
                        elseif ($attribute_value["filter_type"] == "includes") {
                            $filterCondition .= " ".$attribute_value['operator'].' ('.$attribute_value["attribute_name"].' like "%'.$attribute_value['attribute_condition'].'%")';
                        } elseif ($attribute_value['filter_type'] == 'between') {
                            $filterCondition .= " ".$attribute_value['operator']." (".$attribute_value['attribute_name']." between ".$attribute_value['rangeFrom']." and ".$attribute_value['rangeTo'].")";
                        }
                        else {
                            $filterCondition .= " ".$attribute_value['operator']." LENGTH(".$attribute_value['attribute_name'].") > 0";
                        }
                    }
                    else
                    {
                    $insert_id=$con->insert_id;
                    $productId= $attribute_value['previous_row']['product_id'];
                    $filters = $con->query("select * from product_filter where product_id=".$productId." order by index_no");
                    if ($filters->num_rows > 0) {
                        $first_and_iteration = true;
                        $filterCondition ='where 1=1 ';
                        while ($prev_attribute_value = $filters->fetch_assoc()) {

                            if($prev_attribute_value['op_value'] == 'OR')
                            {
                                $operator_value = ') OR (1=1 AND';
                            }
                            else
                            {
                                if($first_and_iteration == true)
                                {
                                    $operator_value = 'AND  (1=1 AND';
                                    $first_and_iteration=false;
                                }
                                else{
                                    $operator_value = ' AND ';
                                }


                            }
                            if ($prev_attribute_value["filter_type"] == "=") {
                                $filterCondition .= " ".$operator_value.' '.$prev_attribute_value["attribute_name"].' = "'.$prev_attribute_value['attribute_condition'].'"';
                            } elseif ($prev_attribute_value["filter_type"] == "!=") {
                                $filterCondition .= " ".$operator_value.' '.$prev_attribute_value["attribute_name"].' != "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == ">") {
                                $filterCondition .= " ".$operator_value.' '.$prev_attribute_value["attribute_name"].' > "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == "<") {
                                $filterCondition .= " ".$operator_value.' '.$prev_attribute_value["attribute_name"].' < "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == "includes") {
                                $filterCondition .= " ".$operator_value.' '.$prev_attribute_value["attribute_name"].' like "%'.$prev_attribute_value['attribute_condition'].'%"';
                            } elseif ($prev_attribute_value['filter_type'] == 'between') {
                                $filterCondition .= " ".$operator_value." ".$prev_attribute_value['attribute_name']." between ".$prev_attribute_value['range_from']." and ".$prev_attribute_value['range_to']."";
                            } else {
                                $filterCondition .= " ".$operator_value." LENGTH(".$prev_attribute_value['attribute_name'].") > 0";
                            }
                        }
                        $filterCondition.=') ';
                    }
                    }
            }

        }
        $sql2 = "update products set filter_condition ='".$filterCondition."' where id =".$productId;

        $con->query($sql2);
        $success=true;

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
