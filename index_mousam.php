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
        <div id="index">
          <index></index>
        </div>
    </div>
    <div class="col-md-3">

        <div id="filter">
            <product-filters></product-filters>
        </div>
    </div>
</div>
    <script>
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
    <script type="module" src="./js/components/homepage/index.js" defer></script>
    <script type="module" src="./js/components/product/product_filters.js" defer></script>
</body>
</html>
