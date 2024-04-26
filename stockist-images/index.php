<?php
  include '../login_checking.php';
  include '../functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <?php include '../header.php'; ?>
</head>

<body>
<?php include '../topbar.php'; ?>
    <div id="wrap">
        <div class="container">
            <div class="row">

                <form class="form-horizontal" action="customer_img_process.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Grab Web Images for Stockists</legend>

                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>

                        <!-- Customer Code -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Type in Customer Code</label>
                            <div class="col-md-4">
                                <input type="text" name="custcode" id="custcode" class="input-large" required>
                            </div>
                        </div>

                        <!-- Image Type -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Image Type</label>
                            <div class="col-md-4">
                                <select name="imgtype" id="imgtype" class="input-large" required>
                                  <option value="highres">High Resolution (20X20)</option>
                                  <option value="webres">Web Resolution</option>
                                </select>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Submit CSV</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="Submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Submit</button>
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
