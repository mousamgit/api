<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include 'login_checking.php';
include 'functions.php';

// Include the ProductDetailHandler class
require_once('fetch_filtered_data.php');

// Create an instance of the ProductDetailHandler class
$productDetailHandler = new ProductDetailHandler();

$productDetails = $productDetailHandler->getProductValues();

$urlData = $_GET;
$username = $_SESSION["username"];
$records_per_page = 10;
$baseQuery = getQuery('pim');
$result = $productDetails;
$column_values_row = $productDetailHandler->getColumnValuesRow();
$total_pages = getTotalPages($baseQuery , $records_per_page);
$usercol = getValue('users', 'username', $username, 'columns');
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <script src="./js/pimjs.js" ></script>
    <script src="./js/filter.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script type="module" src="./js/components/product/ProductFilters.js" ></script>

    <title>Homepage</title>
</head>
<body>
<?php include 'topbar.php'; ?>
<div class="row">
    <div class="col-md-9">

        <div id="app">

            <?php
            echo '<table id=myTable class=display><thead><tr>';

            foreach ($column_values_row as $colName => $column_values) {
                echo '<th>'.mb_convert_case(str_replace("_"," ",$column_values), MB_CASE_TITLE).'</th>';
            } // show column headers
            echo '</tr></thead><tbody>';
            foreach ($result as $rkey=>$row)
            {
            echo '<tr>';
            foreach ($column_values_row as $ckey=>$colName)
            {
                $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
                    if( $colName == "sku" ){ echo '<td col="'.$colName.'" :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }"><a href="https://pim.samsgroup.info/product.php?sku='.$row[$colName].'">'.$row[$colName].'</a></td>';}
                    elseif (strpos($colName, "image") !==  false  && $row[$colName] != "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }"><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
                    elseif (strpos($colName, "image") !==  false  && $row[$colName] == "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }" align=center>No Image</td>';}
                    else {
                        echo '<td class="tabledata" row="' . $row['sku'] . '" col="'.$colName.'" :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }">';
                        echo '<form class="editform"  v-if="isediting(\'' . $row['sku']. '\', \'' . $colName . '\')"  action="updatetablevalue.php" method="post">';
                        echo '<input type="hidden" name="username" value="' . $username . '">';
                        echo '<input type="hidden" name="sku" value="' . $row['sku'] . '">';
                        echo '<input type="hidden" name="colName" value="' . $colName . '">';
                        echo '<input type="hidden" name="oldValue" value="' .$row[$colName].'">';
                        echo '<input name="colValue" type="text" value="'.$row[$colName].'">';
                        echo '<button type="submit">Submit</button></form>';
                        echo '<a class="editfield" v-else @click="editdata(\'' . $row['sku']. '\', \'' . $colName . '\')" >'.$row[$colName].'<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                        echo '</td>';
                    }

            }
                echo '</tr>';
            }
            echo '</tbody></table>';
            // Pagination links
            echo '<div class="pagination">';
            // if(strpos($_SERVER['REQUEST_URI'], "?") !==  false){
            //     $pageurl = $_SERVER['REQUEST_URI'] . 'page=';
            // }
            // else{
            //   $pageurl = '?page=';
            // }

            for ($page = 1; $page <= $total_pages; $page++) {
                $urlData ['page'] = $page;
                $pageurl =  '?' . http_build_query($urlData);

                if($current_page == $page){
                    echo '<a class="active" href="' . $pageurl . '">' . $page . '</a>';
                }
                else{
                    echo '<a href="' . $pageurl . '">' . $page . '</a>';
                }

            }
            echo '</div>';
            ?>
            ?>

        </div>
    </div>
    <div class="col-md-3">
        <p></p>
        <div id="filter">
            <product-filters></product-filters>
        </div>
    </div>
</div>
    <script>
        var usercol = [<?php echo $usercol; ?>];
        console.log(usercol)
        const callmyapp = myapp.mount('#app');
    </script>
    <script type="module" src="./js/components/product/product_filters.js" defer></script>
</body>
</html>
