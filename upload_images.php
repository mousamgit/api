<?php
include 'login_checking.php';
    include 'functions.php';
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

    <div class="form-box body-content">
        <form action="upload_images_temp.php" method="post" name="upload_images" enctype="multipart/form-data">
            <h1>Upload Images to PIM</h1>
            <input type="file" name="files[]" id="files" class="file-input-large" multiple><br><br>
            <button type="submit" id="submit" name="Import" class="button button-red" data-loading-text="Loading...">Import Images</button>
        </form>
    </div>
    <div class="form-box body-content">
            <h2>Update spin animation column</h1>
            <a href="/products/updatevideo.php" class="button button-red" target="_blank">Update</a>
    </div>
</body>

</html>
