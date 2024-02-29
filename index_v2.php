<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include 'login_checking.php';
include 'functions.php';

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
    <div class="col-md-12">
        <div id="index">
            <index></index>
        </div>
    </div>

</div>
<script type="module" src="./js/components/homepage/index.js" defer></script>
</body>
</html>
