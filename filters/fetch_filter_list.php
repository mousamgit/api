<?php

class FilterDetailsHandler {
    private $con;
    private $itemsPerPage = 10;
    public function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        require('../connect.php');
        require_once('../login_checking.php');
        require('../functions.php');

        $this->con = $con;

        $currentUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $urlParts = parse_url($currentUrl);
        parse_str($urlParts['query'] ?? '', $queryParameters);


    }

    public function getFilterDetails() {
        $rows = $this->getRows();
        $columns = $this->getColumns();
        $total_rows = $this->getTotalRows();
        $this->con->close();

        header('Content-Type: application/json');
        echo json_encode(['rows' => $rows,'columns'=>$columns,'total_rows'=>$total_rows]);
    }
    private function getTotalRows() {
        $totalRowsQuery = $this->con->query("select DISTINCT id FROM user_filters");
        return $totalRowsQuery->num_rows;
    }
    public function getRows()
    {
        $offset = (($_GET['page'] ?? 1) - 1) * $this->itemsPerPage;

        $rows = [];
        $filterQuery = $this->con->query("SELECT distinct filter_name,id as filter_no,(select username from users where users.id =user_filters.user_id)user_name FROM user_filters LIMIT $offset, $this->itemsPerPage");

        if ($filterQuery->num_rows > 0) {
            while ($row = $filterQuery->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
    public function getColumns()
    {
        require('../connect.php');
        $columns = [];

        $filterQuery = $this->con->query("SELECT COLUMN_NAME as colName FROM information_schema.columns
                       WHERE table_schema = '".$name."' AND table_name = 'user_filters'");

        if ($filterQuery->num_rows > 0) {
            while ($row = $filterQuery->fetch_assoc()) {
                if($row['colName'] != 'filter_value' && $row['colName'] != 'user_id')
                {
                    if($row['colName']=='id')
                    {
                        $columns[] = 'filter_no';
                    }
                    else
                    {
                        $columns[] = $row['colName'];
                    }
                }

            }
            array_push($columns,'user_name');
        }

       return $columns;
    }


}

$filterDetailHandler = new FilterDetailsHandler();
$filterDetailHandler->getFilterDetails();

?>
