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
    </style>
</head>
<body>
<?php include 'topbar.php'; ?>
<div id="index"><index :urlsku="'test'"></index></div>
<script type="module" src="./homepage/js/index.js" defer></script>
</body>
</html>
