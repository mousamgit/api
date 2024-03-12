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
        .column-selector {
            width:300px;
            position: absolute;
            top: 220px; /* Adjust this value to position it just below the table */
            right: 60px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 999;
            max-height:675px;
            overflow-y: auto;
        }
        .column-selector .description {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .column-selector ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .column-selector li {
            margin-bottom: 5px;
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
