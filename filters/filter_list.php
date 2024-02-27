<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../login_checking.php';
include '../functions.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>
    <script src="../js/pimjs.js" ></script>
    <script src="../js/filter.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>

    <title>PIM - Filter List</title>
</head>
<body>
<?php include '../topbar.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div id="filterList">
            <filter-list></filter-list>
        </div>
    </div>

</div>
<script type="module" src="../js/components/Filters/FilterList.js" defer></script>
</body>
</html>
