<?php

class ProductDetailHandler {
    private $con;
    private $productId;
    private $itemsPerPage = 100;

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
        $this->productId = $queryParameters['id'] ?? 0;
    }

    public function getProductDetails() {
        $products = $this->getProducts();
        $productFilter = $this->getProductFilter();
        $productValues = $this->getProductValues();
        $totalRows = $this->getTotalRows();
        $columnValuesRow = $this->getColumnValuesRow();
        $totalProductValues = $this->getTotalRowValues();
        $filterNames = $this->getFilters();
        $this->con->close();

        header('Content-Type: application/json');
        echo json_encode(['total_product_values'=>$totalProductValues,'products' => $products, 'product_details' => $productFilter, 'product_values' => $productValues, 'total_rows' => $totalRows, 'column_values_row' => $columnValuesRow,'filter_names'=>$filterNames]);
    }

    private function getProducts() {
        $products = [];
        $productQuery = $this->con->query("SELECT * FROM products where id=" . $this->productId);

        if ($productQuery->num_rows > 0) {
            while ($row = $productQuery->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return $products;
    }

    private function getProductFilter() {
        $productFilter = [];

        $productFilterQuery = $this->con->query("SELECT * FROM product_filter where status=1 and product_id=" . $this->productId . " and user_name = '".$_SESSION['username']."' order by index_no ASC");

        if ($productFilterQuery->num_rows > 0) {
            while ($row = $productFilterQuery->fetch_assoc()) {
                $productFilter[] = $row;
            }
        }

        return $productFilter;
    }

    private function getProductValues() {
        $productValues = [];
        $filterConditionCombined = $this->getFilterConditionCombined();
        $columnValuesRow = $this->getColumnValuesRow();
        $offset = (($_GET['page'] ?? 1) - 1) * $this->itemsPerPage;

        $productDetailQuery = $this->con->query("SELECT DISTINCT " . implode(',', $columnValuesRow) . " FROM pim " . $filterConditionCombined . " AND sku != '' LIMIT $offset, $this->itemsPerPage");
        if ($productDetailQuery->num_rows > 0) {
            while ($row = $productDetailQuery->fetch_assoc()) {
                $productValues[] = $row;
            }
        }
        return $productValues;
    }

    private function getTotalRows() {
        $totalRowsQuery = $this->con->query("select DISTINCT sku FROM pim " . $this->getFilterConditionCombined());
        return $totalRowsQuery->num_rows;
    }
    private function getTotalRowValues() {
        $productValuesTotal =[];
        $filterConditionCombined = $this->getFilterConditionCombined();
        $columnValuesRow = $this->getColumnValuesRow();
        $productDetailQuery = $this->con->query("SELECT DISTINCT " . implode(',', $columnValuesRow) . " FROM pim " . $filterConditionCombined . " AND sku != '' ");
        if ($productDetailQuery->num_rows > 0) {
            while ($row = $productDetailQuery->fetch_assoc()) {
                $productValuesTotal[] = $row;
            }
        }
        return $productValuesTotal;
    }

    private function getColumnValuesRow() {
        $columnValuesRow = [];
        $userOrderedColumns = $this->con->query("select column_name from user_columns where user_name ='".$_SESSION["username"]."' order by order_no ASC");

        if ($userOrderedColumns->num_rows > 0) {
            while ($row = $userOrderedColumns->fetch_assoc()) {
                $columnValuesRow[]=$row['column_name'];
            }
        }
        if(!in_array('sku',$columnValuesRow))
        {
            $columnValuesRow[]='sku';
        }

        $userColumns = $this->con->query("select columns from users where username ='".$_SESSION["username"]."'");
        $columnValuesRowCustomer='';
        if ($userColumns->num_rows > 0) {
            while ($row = $userColumns->fetch_assoc()) {
                $columnValuesRowCustomer = str_replace('"','',$row['columns']);
            }
            $columnValuesRowCustomer = explode(",",$columnValuesRowCustomer);

        }
        foreach ($columnValuesRowCustomer as $key =>$cval)
        {
            if(!in_array($cval,$columnValuesRow))
            {
                $columnValuesRow[] = $cval;
            }
        }
        $checkIfColumns = $this->con->query("select attribute_name from product_filter where status=1 and product_id =" . $this->productId. " and user_name ='".$_SESSION["username"]."'");

        if ($checkIfColumns->num_rows > 0) {
            while ($row = $checkIfColumns->fetch_assoc()) {
                if (!in_array($row['attribute_name'], $columnValuesRow)) {
                    $columnValuesRow[] = $row['attribute_name'];
                }
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
        $filterFetch = $this->con->query("SELECT * FROM product_filter WHERE status=1 and product_id=" . $this->productId . " and user_name='".$_SESSION['username']."' ORDER BY index_no ASC");

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

        return $filterConditionCombined;
    }
}

$productDetailHandler = new ProductDetailHandler();
$productDetailHandler->getProductDetails();

?>
