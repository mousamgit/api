<?php

class listDetailHandler {
    private $con;
    private $db_name='';
    private $listId;
    private $itemsPerPage = 100;

    private $primary_table='products';
    private $key_name='id';
    private $filter_table = 'product_filter';
    private $filter_page_name = 'products';
    private $order_column_name = 'id';
    private $order_column_value = 'ASC';

    public function __construct() {
        // Error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // Database connection
        require_once('./connect.php');
        require_once('./login_checking.php');
        $this->con = $con;



        // Getting the referring URL
        $currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        // parse_url to extract query parameters
        $urlParts = parse_url($currentUrl);
        parse_str($urlParts['query'] ?? '', $queryParameters);

        // Extracting the channel_id parameter
        $this->listId = $queryParameters['id'] ?? 0;

        $data = json_decode(file_get_contents("php://input"), true);
        $this->primary_table = $data['primary_table'];
        $this->filter_table = $data['filter_table'];
        $this->key_name = $data['key_name'];
        $this->filter_page_name = 'products';
        $this->column_table = $data['column_table'];
        $this->order_column_name = $data['order_column_name'];
        $this->order_column_value = $data['order_column_value'];
    }

    public function getlistDetails() {
        $lists = $this->getlists();
        $listFilter = $this->getlistFilter();
        $listValues = $this->getlistValues();
        $listValuesTotal = $this->getTotalRowValues();
        $totalRows = $this->getTotalRows();
        $columnValuesRow = $this->getColumnValuesRow();
        $filterNames = $this->getFilters();
        $this->con->close();

        header('Content-Type: application/json');
        echo json_encode(['list_values_total'=>$listValuesTotal,'list_details' => $listFilter, 'list_values' => $listValues, 'total_rows' => $totalRows, 'column_values_row' => $columnValuesRow,'filter_names'=>$filterNames]);
    }

    private function getlists() {
        $lists = [];
        $listQuery = $this->con->query("SELECT * FROM products where id=" . $this->listId);
        if ($listQuery->num_rows > 0) {
            while ($row = $listQuery->fetch_assoc()) {
                $lists[] = $row;
            }
        }
        return $lists;
    }

    private function getlistFilter() {
        $listFilter = [];
        $listFilterQuery = $this->con->query("SELECT * FROM product_filter where status=1 and product_id=" . $this->listId . " and user_name = '".$_SESSION['username']."' order by index_no ASC");

        if ($listFilterQuery->num_rows > 0) {
            while ($row = $listFilterQuery->fetch_assoc()) {
                $listFilter[] = $row;
            }
        }
        return $listFilter;
    }

    private function getlistValues() {
        $order_column_name='sku';
        $order_column_value='ASC';
        $data = json_decode(file_get_contents("php://input"), true);

        if(count($data)>0)
        {
            $order_column_name = $data['order_column_name'];
            $order_column_value = $data['order_column_value'];
        }

        $listValues = [];
        $filterConditionCombined = $this->getFilterConditionCombined();
        $columnValuesRow = $this->getColumnValuesRow();
        $offset = (($_GET['page'] ?? 1) - 1) * $this->itemsPerPage;



            $listDetailQuery = $this->con->query("SELECT DISTINCT " . implode(',', $columnValuesRow) . " FROM ".$this->primary_table." " . $filterConditionCombined . " AND ".$this->key_name." != '' GROUP BY ".$this->key_name." order by ".$this->order_column_name." ".$this->order_column_value." LIMIT ".$offset.", ".$this->itemsPerPage."");


        if ($listDetailQuery->num_rows > 0) {
            while ($row = $listDetailQuery->fetch_assoc()) {
                $listValues[] = $row;
            }
        }
        return $listValues;
    }

    private function getTotalRows() {
        $totalRowsQuery = $this->con->query("select DISTINCT ".$this->key_name." FROM  ".$this->primary_table. "   " . $this->getFilterConditionCombined());
        return $totalRowsQuery->num_rows;
    }
    private function getTotalRowValues() {
        $listValuesTotal =[];
        $filterConditionCombined = $this->getFilterConditionCombined();
        $columnValuesRow = $this->getColumnValuesRow();
        $listDetailQuery = $this->con->query("SELECT DISTINCT " . implode(',', $columnValuesRow) . " FROM  ".$this->primary_table. "   " . $filterConditionCombined . " AND ".$this->key_name." != '' ");
        if ($listDetailQuery->num_rows > 0) {
            while ($row = $listDetailQuery->fetch_assoc()) {
                $listValuesTotal[] = $row;
            }
        }
        return $listValuesTotal;
    }

    private function getColumnValuesRow() {
        require_once('./connect.php');
        $columnValuesRow = [];
        $userOrderedColumns = $this->con->query("SELECT COLUMN_NAME as column_name,DATA_TYPE as data_type
                       FROM information_schema.columns
                       WHERE table_schema = 'u288902296_pim' AND table_name = '".$this->column_table."'");
        if($this->primary_table == 'pim')
        {
            $userOrderedColumns = $this->con->query("SELECT column_name FROM  ".$this->column_table. "   WHERE user_name = '".$_SESSION['username']."' AND status = 1 GROUP BY column_name ORDER BY MIN(order_no) ASC;");

        }

        if ($userOrderedColumns->num_rows > 0) {
            while ($row = $userOrderedColumns->fetch_assoc()) {
                $columnValuesRow[]=$row['column_name'];
            }
        }
        if($this->primary_table == 'pim') {
            if (!in_array('sku', $columnValuesRow)) {
                array_unshift($columnValuesRow, 'sku');
            }
        }


        return $columnValuesRow;
    }
    function getFilters()
    {
        require('./functions.php');
        require('./connect.php');
        $filter_names =[];
        $user_id = getValue('users', 'username', $_SESSION['username'], 'id');

        $query="select id,filter_name from user_filters where user_id=".$user_id;
        $filters=$con->query($query);
        if($filters->num_rows>0)
        {
            while($row=$filters->fetch_assoc())
            {
                $filter_names[]=$row;
            }
        }
        return $filter_names;
    }

    public function getFilterConditionCombined() {
        $filterConditions = [];
        $groupedConditions = [];
        $filterConditionCombined = '';
        $whereValue = 'WHERE 1=1 AND';
        $filterFetch = $this->con->query("SELECT * FROM product_filter WHERE status=1 and product_id=" . $this->listId . " and user_name='".$_SESSION['username']."' ORDER BY index_no ASC");

        if ($filterFetch->num_rows > 0) {
            while ($prevAttributeValue = $filterFetch->fetch_assoc()) {
                switch ($prevAttributeValue["filter_type"]) {
                    case ">":
                    case "<":
                        $condition = $prevAttributeValue['attribute_name'] . ' ' . $prevAttributeValue["filter_type"] . ' "' . $prevAttributeValue['attribute_condition'] . '"';
                        break;
                    case "=":
                        $condition = $prevAttributeValue['attribute_name'] . ' in ' . $prevAttributeValue['attribute_condition'];
                        break;
                    case "!=":
                        $condition = $prevAttributeValue['attribute_name'] . ' NOT IN ' . $prevAttributeValue['attribute_condition'];
                        break;
                    case "includes":
                        $condition = $prevAttributeValue['attribute_name'] . ' LIKE "%' . $prevAttributeValue['attribute_condition'] . '%"';
                        break;
                    case "dont_includes":
                        $condition = $prevAttributeValue['attribute_name'] . ' NOT LIKE "%' . $prevAttributeValue['attribute_condition'] . '%"';
                        break;
                    case "between":
                        $condition = $prevAttributeValue['attribute_name'] . ' BETWEEN ' . $prevAttributeValue['range_from'] . ' AND ' . $prevAttributeValue['range_to'];
                        break;
                    case "IS NULL":
                        $condition = 'LENGTH(' . $prevAttributeValue['attribute_name'] . ') = 0';
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
        $filterConditionCombined = str_replace("AND () OR", "AND", $filterConditionCombined);
        return $this->filter_table=='product_filter'?$filterConditionCombined:'where 1=1';
    }
}

$listDetailHandler = new listDetailHandler();
$listDetailHandler->getlistDetails();

?>