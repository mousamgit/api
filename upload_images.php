<?php
include 'login_checking.php';
    include 'functions.php';
    $urlData = $_GET;
    $username = $_SESSION["username"];
    $records_per_page = 100;
    $baseQuery = getQuery('pim',$records_per_page);
    $result = getResult($baseQuery , $records_per_page);
    $total_pages = getTotalPages($baseQuery , $records_per_page);
    $usercol = getValue('users', 'username', $username, 'columns');
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
?>
<html lang="en">

<head>
    <title> Upload Images into PIM </title>
    <link rel="stylesheet" href="/css/dancss.css">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <?php include 'header.php'; ?>
</head>

<body>
<?php include 'topbar.php'; ?>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div><br><br>


    <div class="form-box">
        <form action="upload_images_temp.php" method="post" name="upload_images" enctype="multipart/form-data">
            <h1>Upload Images to PIM</h1>
            <input type="file" name="files[]" id="files" class="file-input-large" multiple><br><br>
            <button type="submit" id="submit" name="Import" class="button button-red" data-loading-text="Loading...">Import Images</button>
        </form>
    </div>

</body>

</html>
