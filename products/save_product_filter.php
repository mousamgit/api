<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../connect.php');
require_once('../login_checking.php');

class ProductFilterManager
{
    private $con;

    public function __construct($dbConnection)
    {
        $this->con = $dbConnection;
    }

    public function saveFunction($data)
    {

        if(count($data['attribute']) >0)
        {
            if ($data['attribute'][0]['type'] === 'edit') {
                $this->updateCase($data);
            } else {
                $this->insertCase($data);
            }
        }

    }

    private function insertCase($data)
    {
        $success = false;
        $productId = $data['id'] ?? 0;
        $user_name = $_SESSION["username"];

        foreach ($data["attribute"] as $key => $attribute_value) {
            $attribute_name = $attribute_value['attribute_name'];
            $filter_type = $attribute_value['filter_type'];
            $data_type = $attribute_value['data_type'];
            $operator = $attribute_value['operator'];
            $range_from = '';
            $range_to = '';
            $attribute_condition = '';
            if ($attribute_value["filter_type"] == "=" || $attribute_value["filter_type"] == "!=" || $attribute_value["filter_type"] == ">" || $attribute_value["filter_type"] == "<" || $attribute_value["filter_type"] == "includes" || $attribute_value["filter_type"] == "dont_includes") {
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
            $sql = "INSERT INTO product_filter (`product_id`, `filter_type`, `attribute_name`, `attribute_condition`, `range_from`,`range_to`,`data_type_value`,`op_value`,`index_no`,`user_name`) 
                VALUES ('$productId', '$filter_type', '$attribute_name', '$attribute_condition', '$range_from','$range_to','$data_type','$operator','$indexNo','$user_name')";

            if ($this->con->query($sql) == TRUE) {
                if (!($attribute_value['condition_type'] == 'group' || $attribute_value['condition_type'] == 'normal')) {
                    $update_product_filter = "update product_filter set index_no=index_no+1 where index_no>=" . $indexNo . " and id !=" . $this->con->insert_id;
                    $this->con->query($update_product_filter);
                }
                $success = true;
            }
        }
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $this->con->error]);
        }
    }

    private function updateCase($data)
    {

        foreach ($data["attribute"] as $key => $attribute_value) {
            $attribute_name = $attribute_value['attribute_name'];
            $filter_type = $attribute_value['filter_type'];
            $data_type = $attribute_value['data_type'];
            $operator = $attribute_value['operator'];
            $range_from = '';
            $range_to = '';
            $attribute_condition = '';
            if ($attribute_value["filter_type"] == "=" || $attribute_value["filter_type"] == "!=" || $attribute_value["filter_type"] == ">" || $attribute_value["filter_type"] == "<" || $attribute_value["filter_type"] == "includes" || $attribute_value["filter_type"] == "dont_includes") {
                $attribute_condition = $attribute_value['attribute_condition'];
            } elseif ($attribute_value['filter_type'] == 'between') {
                $range_from = $attribute_value['rangeFrom'];
                $range_to = $attribute_value['rangeTo'];
            } else {
                $attribute_condition = '';
            }
            $sql = "UPDATE product_filter SET  
            attribute_name = '" . $attribute_name . "',
            filter_type = '" . $filter_type . "',
            attribute_condition = '" . $attribute_condition . "', 
            range_from = '" . $range_from . "',
            range_to = '" . $range_to . "',
            data_type_value = '" . $data_type . "' 
            WHERE id = " . $attribute_value['id'];
            $this->con->query($sql);
            $success = true;
        }
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $this->con->error]);
        }
    }
}

$productFilterManager = new ProductFilterManager($con);

$data = json_decode(file_get_contents("php://input"), true);

$productFilterManager->saveFunction($data);

$con->close();

?>
