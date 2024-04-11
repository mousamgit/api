<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
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
    <script src="/js/pimjs.js" ></script>
    <script src="/js/filter.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script type="module" src="/products/js/ProductFilters.js" ></script>
    <title>Homepage</title>
    <style>
    </style>
</head>
<body>
<?php include 'topbar.php'; ?>
<div id="list">
    <!--         both key name and primary table are compulsary-->
    <list :urlsku="'test'" :primary_table="'pim'" :key_name="'sku'" :show_filter_button="true"></list>
</div>
<script type="module" src="../crud/list.js" defer></script>
</body>
</html>
