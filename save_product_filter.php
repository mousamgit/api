<?php
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        //  database connection
        require_once('./connect.php');

        // Get the POST data from the Vue.js application
        $data = json_decode(file_get_contents("php://input"), true);

        $currentDateTime = date("Y-m-d H:i:s");

        // Getting the referring URL
        $currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        // parse_url to extract query parameters
        $urlParts = parse_url($currentUrl);

        parse_str($urlParts['query'] ?? '', $queryParameters);


        // Extracting the p_id parameter
        $productId = $queryParameters['id'] ?? 0;

        $op_value='AND';

        $PfilterCondition='';
        foreach ($data["attribute"] as $key => $attribute_value) {
            $attribute_name = $attribute_value['attribute_name'];
            $filter_type = $attribute_value['filter_type'];
            $data_type = $attribute_value['data_type'];
            $operator = $attribute_value['operator'];
            $range_from = '';
            $range_to = '';
            $attribute_condition = '';
            if ($attribute_value["filter_type"] == "=" || $attribute_value["filter_type"] == "!=" || $attribute_value["filter_type"] == ">" || $attribute_value["filter_type"] == "<" || $attribute_value["filter_type"] == "includes") {
                $attribute_condition = $attribute_value['attribute_condition'];
            } elseif ($attribute_value['filter_type'] == 'between') {
                $range_from = $attribute_value['rangeFrom'];
                $range_to = $attribute_value['rangeTo'];
            } else {
                $attribute_condition = '';
            }

            $indexNo = 1;
            if (count($attribute_value['previous_row']) > 0) {
                $indexNo = intval($attribute_value['previous_row']['index_no']) + 1;
            }
            $sql = "INSERT INTO product_filter (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`,`index_no`) 
                VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type','$operator','$indexNo')";

            if ($con->query($sql) == TRUE) {
                if (!($attribute_value['condition_type'] == 'group' || $attribute_value['condition_type'] == 'normal')) {
                    $update_product_filter = "update product_filter set index_no=index_no+1 where index_no>=" . $indexNo . " and id !=" . $con->insert_id;
                    $con->query($update_product_filter);
                }
                $success = true;
            }
        }
if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $con->error]);
}

// Close the database connection
$con->close();
?>
