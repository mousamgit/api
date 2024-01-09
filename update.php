<?php
  include 'login_checking.php';
  include 'functions.php';
?>



<html lang="en">

<head>
<?php include 'header.php'; ?>
    <link rel="stylesheet" href="/css/dancss.css">
    <title>Update Products from PIM</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

</head>
<?php include 'topbar.php'; ?>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div>

<?php
 include ('connect.php');
 $query = 'SELECT * from pim';
 $result = mysqli_query($con, $query) or die(mysqli_error($con));
 $row=mysqli_fetch_assoc($result);

 echo "<br>";
 echo "<div style='border:1px solid #000; padding:20px; margin:0 auto; width:500px;'><h3>Available Headers</h3>";
 foreach ($row as $colName => $val) { echo $colName.", "; } // show column headers
 echo "</div>";
?>

<body><br><br>
    <div id="wrap">
        <div class="container">
            <div class="row">

                <form class="form-horizontal" action="updatecsv.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Update or Add to PIM</legend>

                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
            <?php
               get_all_records();
            ?>
        </div>
    </div>
</body>

</html>
