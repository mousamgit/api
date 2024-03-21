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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/pimjs.js" ></script>
    <script src="./js/filter.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script type="module" src="./products/js/ProductFilters.js" ></script>
    <title>Homepage</title>
    <style>
        .custom-select {
            position: relative;
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
        }

        .selected-item {
            background-color: #f0f0f0;
            border-radius: 3px;
            padding: 3px 8px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .remove-btn {
            cursor: pointer;
            margin-left: 5px;
        }

        .options {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
        }

        .option {
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'topbar.php'; ?>
<div class="pim-padding">
    <div class="row">
        <div class="col-md-12">
            <div id="index">
                <index></index>
            </div>
        </div>
    </div>
</div>
<script type="module" src="./homepage/js/index_v2.js" defer></script>
</body>
</html>
