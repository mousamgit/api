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
$filters = getFilters();
$urlData = $_GET;
$username = $_SESSION["username"];
$records_per_page = 10;
$baseQuery = getQuery('pim');
$result = $productDetails;
$column_values_row = $productDetailHandler->getColumnValuesRow();
$total_rows = $productDetailHandler->getTotalRows();
$total_pages = $total_rows/10;
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
    <style>
        .tooltip-container {
            position: relative;
            display: inline-block;
        }

        .tooltip-content {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            z-index: 1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 14px;
            max-width: 300px; /* Adjust width as needed */
        }

        .tooltip-container:hover .tooltip-content {
            display: block;
        }

    </style>
</head>
<body>
<?php include 'topbar.php'; ?>
<div class="row">
    <div class="col-md-9">

        <div id="app">
            <?php
            foreach ($filters as $fkey=>$fvalue) {
                $fname = 'controlFilters('.$fvalue.')';
                ?>
                <div class="tooltip-container">
                    <button class="btn btn-primary" onclick="<?php echo $fname; ?>">
                        Show Saved Filters <?php echo $fkey+1; ?>
                    </button>
                    <div class="tooltip-content">
                        <!-- Your filtered data goes here -->
                        <?php echo htmlspecialchars(json_encode(getFilterValueHover($fvalue)), ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
                <?php
            }

            echo '<table id=myTable class=display><thead><tr>';

            foreach ($column_values_row as $ckey => $colName) {
//                $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
                echo '<th>'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</th>';
            } // show column headers
            echo '</tr></thead><tbody>';
            foreach ($result as $rkey=>$row)
            {
                echo '<tr>';
                foreach ($column_values_row as $ckey=>$colName)
                {
                    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
                    if( $colName == "sku" ){ echo '<td col="'.$colName.'" ><a href="https://pim.samsgroup.info/product.php?sku='.$row[$colName].'">'.$row[$colName].'</a></td>';}
                    elseif (strpos($colName, "image") !==  false  && $row[$colName] != "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }"><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
                    elseif (strpos($colName, "image") !==  false  && $row[$colName] == "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }" align=center>No Image</td>';}
                    else {
                        echo '<td class="tabledata" row="' . $row['sku'] . '" col="'.$colName.'" >';
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

            ?>

            <div class="row">
                <div class="col-md-6">
                    <?php
                    // Calculate the range of entries being displayed
                    $start_entry = min(($current_page - 1) * $records_per_page + 1, $total_rows);
                    $end_entry = min($current_page * $records_per_page, $total_rows);
                    ?>
                    <p>Showing <?php echo $start_entry; ?>-<?php echo $end_entry; ?> of <?php echo $total_rows; ?> entries</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="mt-3">
                        <div class="btn-group" role="group" aria-label="Pagination">
                            <a class="btn btn-primary <?php echo $current_page <= 1 ? 'disabled' : ''; ?>" href="?page=<?php echo $current_page - 1; ?>" <?php echo $current_page <= 1 ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Prev</a>
                            <?php
                            // Calculate the range of page numbers to display
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($start_page + 4, $total_pages);

                            // If not enough pages to fill the range, adjust the start page
                            if ($end_page - $start_page < 4) {
                                $start_page = max(1, $end_page - 4);
                            }
                            ?>

                            <?php for ($page = $start_page; $page <= $end_page; $page++) : ?>
                                <a class="btn btn-success ml-2 mr-2 <?php echo $page == $current_page ? 'active' : ''; ?>" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            <?php endfor; ?>
                            <a class="btn btn-primary <?php echo $current_page >= ceil($total_rows / $records_per_page) ? 'disabled' : ''; ?>" href="?page=<?php echo $current_page + 1; ?>" <?php echo $current_page >= ceil($total_pages / $records_per_page) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Next</a>
                        </div>
                    </div>
                </div>
            </div>
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
    console.log(usercol);
    const callmyapp = myapp.mount('#app');

    function controlFilters(filter_no) {
        var dataToSend = {
            'filter_no':filter_no
        };
        $.ajax({
            type: 'POST',
            url: 'control_user_filters.php',
            data: dataToSend,
            success: function(response) {
                console.log('Database updated successfully');
                location.reload();
            },
            error: function(xhr, status, error) {

                console.error('Error updating database:', error);
            }
        });
    }


</script>
<script type="module" src="./js/components/product/product_filters.js" defer></script>
</body>
</html>
