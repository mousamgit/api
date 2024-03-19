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
        .box-container {
            position: relative;
            display: inline-block;
            padding: 0px;
            border: 1px solid #ccc;
            border-radius: 0px;
        }

        .box-content {
            min-width:50px;
            display: none; /* Hide the box content by default */
            position: absolute;
            top: 20px; /* Position the box above the container */
            left: 100%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 0px;
            border: 1px solid #ccc;
            border-radius: 0px;
            z-index: 999; /* Set a high z-index value to ensure the box appears on top */
        }

        .box-container:hover .box-content {
            display: block; /* Show the box content on hover */
        }
    </style>
</head>
<body>
<?php include 'topbar.php'; ?>
<div id="index"><index></index></div>
<script type="module" src="./homepage/js/index.js" defer></script>
</body>
</html>
