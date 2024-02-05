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

//
//         foreach ($data["attribute"] as $key => $attribute_value) {
//
//
//        }
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
            $sql = "INSERT INTO product_filter (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`) 
                VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type','$operator')";

            if($con->query($sql) == TRUE)
            {
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
                    elseif ($attribute_value['condition_type'] == 'group')
                    {
                    $insert_id=$con->insert_id;
                    $productId= $attribute_value['previous_row']['product_id'];
                    $previous_filters = $con->query("select * from product_filter where product_id=".$productId." and id <".$insert_id);
                    $next_filters_q = $con->query("select * from product_filter where product_id=".$productId." and id >=".$insert_id);

                    if ($previous_filters->num_rows > 0) {
                        $filterCondition ='where 1=1 AND (1=1';
                        while ($prev_attribute_value = $previous_filters->fetch_assoc()) {
                            if ($prev_attribute_value["filter_type"] == "=") {
                                $filterCondition .= " ".$prev_attribute_value['op_value'].' '.$prev_attribute_value["attribute_name"].' = "'.$prev_attribute_value['attribute_condition'].'"';
                            } elseif ($prev_attribute_value["filter_type"] == "!=") {
                                $filterCondition .= " ".$prev_attribute_value['op_value'].' '.$prev_attribute_value["attribute_name"].' != "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == ">") {
                                $filterCondition .= " ".$prev_attribute_value['op_value'].' '.$prev_attribute_value["attribute_name"].' > "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == "<") {
                                $filterCondition .= " ".$prev_attribute_value['op_value'].' '.$prev_attribute_value["attribute_name"].' < "'.$prev_attribute_value['attribute_condition'].'"';
                            }
                            elseif ($prev_attribute_value["filter_type"] == "includes") {
                                $filterCondition .= " ".$prev_attribute_value['op_value'].' '.$prev_attribute_value["attribute_name"].' like "%'.$prev_attribute_value['attribute_condition'].'%"';
                            } elseif ($prev_attribute_value['filter_type'] == 'between') {
                                $filterCondition .= " ".$prev_attribute_value['op_value']." ".$prev_attribute_value['attribute_name']." between ".$prev_attribute_value['range_from']." and ".$prev_attribute_value['range_to']."";
                            } else {
                                $filterCondition .= " ".$prev_attribute_value['op_value']." LENGTH(".$prev_attribute_value['attribute_name'].") > 0";
                            }
                        }
                        $filterCondition.=') ';
                    }

                        if ($next_filters_q->num_rows > 0) {
                            $filterCondition .= $operator;
                            $filterCondition .=' (1=1 ';
                            while ($next_filters = $next_filters_q->fetch_assoc()) {
                                $next_filters_operator=$next_filters['op_value'];
                                if($next_filters['id']==$insert_id)
                                {
                                    $next_filters_operator = 'AND';
                                }
                                if ($next_filters["filter_type"] == "=") {
                                    $filterCondition .= " ".$next_filters_operator.' '.$next_filters["attribute_name"].' = "'.$next_filters['attribute_condition'].'"';
                                } elseif ($next_filters["filter_type"] == "!=") {
                                    $filterCondition .= " ".$next_filters_operator.' '.$next_filters["attribute_name"].' != "'.$next_filters['attribute_condition'].'"';
                                }
                                elseif ($next_filters["filter_type"] == ">") {
                                    $filterCondition .= " ".$next_filters_operator.' '.$next_filters["attribute_name"].' > "'.$next_filters['attribute_condition'].'"';
                                }
                                elseif ($next_filters["filter_type"] == "<") {
                                    $filterCondition .= " ".$next_filters_operator.' '.$next_filters["attribute_name"].' < "'.$next_filters['attribute_condition'].'"';
                                }
                                elseif ($next_filters["filter_type"] == "includes") {
                                    $filterCondition .= " ".$next_filters_operator.' '.$next_filters["attribute_name"].' like "%'.$next_filters['attribute_condition'].'%"';
                                } elseif ($next_filters['filter_type'] == 'between') {
                                    $filterCondition .= " ".$next_filters_operator." ".$next_filters['attribute_name']." between ".$next_filters['range_from']." and ".$next_filters['range_to']."";
                                } else {
                                    $filterCondition .= " ".$next_filters_operator." LENGTH(".$next_filters['attribute_name'].") > 0";
                                }
                            }
                            $filterCondition.=') ';
                        }
                }
            }


//            if ($con->query($sql) === FALSE) {
//                $success = false;
//                break;
//            }

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
