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

                <form class="form-horizontal" action="process.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Export Data for Stockists</legend>

                        <!-- Insert SKUs -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">SKUs</label>
                            <div class="col-md-4">
                                <textarea id="sku" name="sku" cols="80" rows="10" onclick="this.select()" required>Insert SKUs separated by comma or new line.
eg. BPR-RDSSB0101, BPR-RDDSB0202
or
BPR-RDSSB0101
BPR-RDSSB0202
                                </textarea>
                            </div>
                        </div>

                        <!-- Customer Code -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Type in Customer Code</label>
                            <div class="col-md-4">
                                <input type="text" name="custcode" id="custcode" class="input-large" required>
                            </div>
                        </div>

                        <!-- Headers -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Headers</label>
                            <div class="col-md-4"><br><br>
                              <b>Main Headers</b><br>
                              <input type="checkbox" id="headers-sku" name="headers-sku" checked> <label for="sku">SKU</label><br>
                              <input type="checkbox" id="description" name="description" checked> <label for="description">Description</label><br>
                              <input type="checkbox" id="brand" name="brand" checked> <label for="brand">Brand</label><br>
                              <input type="checkbox" id="title" name="title" checked> <label for="title">Product Title</label><br>
                              <input type="checkbox" id="type" name="type" > <label for="type">Product Type</label><br>
                              <input type="checkbox" id="specs" name="specs" checked> <label for="specs">Product Specifications</label><br>
                              <input type="checkbox" id="tags" name="tags" > <label for="tags">Tags</label><br>
                              <input type="checkbox" id="ws" name="ws"> <label for="ws">Wholesale Price ex GST (AUD)</label><br>
                              <input type="checkbox" id="rrp" name="rrp" checked> <label for="rrp">Retail Price Incl GST (AUD)</label><br>
                              <input type="checkbox" id="carat" name="carat" > <label for="carat">Carat Weight</label><br>
                              <input type="checkbox" id="shape" name="shape" > <label for="shape">Stone Shape</label><br>
                              <input type="checkbox" id="colour" name="colour" > <label for="colour">Stone Colour</label><br>
                              <input type="checkbox" id="clarity" name="clarity" > <label for="clarity">Stone Clarity</label><br>
                              <input type="checkbox" id="metal" name="metal" > <label for="metal">Metal Composition</label><br>
                              <input type="checkbox" id="measurement" name="measurement" > <label for="measurement">Measurement</label><br>
                              <input type="checkbox" id="stonespecs" name="stonespecs" > <label for="stonespecs">Stone Specifications</label><br>

                              <br><br>
                              <b>Images</b><br>
                              <input type="checkbox" id="image1" name="image1" checked> <label for="image1">Main Image</label><br>
                              <input type="checkbox" id="image2" name="image2" checked> <label for="image2">Image 2</label><br>
                              <input type="checkbox" id="image3" name="image3" checked> <label for="image3">Image 3</label><br>
                              <input type="checkbox" id="image4" name="image4" checked> <label for="image4">Image 4</label><br>
                              <input type="checkbox" id="image5" name="image5" checked> <label for="image5">Image 5</label><br>
                              <input type="checkbox" id="image6" name="image6" checked> <label for="image6">Image 6</label><br>
                              <input type="checkbox" id="packagingimg" name="packagingimg" checked> <label for="packagingimg">Packaging Image</label><br>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Submit Request</label>
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
