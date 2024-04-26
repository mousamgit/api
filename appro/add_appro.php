<?php
include '../login_checking.php';
    include '../functions.php';
    $username = $_SESSION["username"];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    

    <?php include '../header.php'; ?>
    <script src="./js/appro.js" ></script>
    <script src="./js/approitem.js" ></script>
    <script src="../autofill/autofill.js"></script>
    <title>add appro</title>
</head>
<body>
<?php include '../topbar.php'; ?>
<div id="app" class="pim-padding">
    <div class="container">
    <form action="process_appro.php" method="post" class="appro-form form-design">
    <input type="hidden" name="username" value="<?php echo $username ?>">
    <div class="form-row header">Add Appro Form </div>
    <div class="wrapper-box">
        <div class="row">
            <div class="col-md-2 form-cell">
        <label for="customer_name">Customer Name:</label>
        
            </div>
            <div class="col-md-10 form-cell">
            <autofill  :col1="'code'" :col2="'company'" :db="'customer'" :inputname="'customer_name'"></autofill>

        </div>

            <div class="col-md-2 form-cell">
        <label for="appro_id">Appro ID:</label>
       
        </div>
        <div class="col-md-4 form-cell"> <input type="text" id="appro_id" name="appro_id" required></div>
            <div class="col-md-2 form-cell">
        <label for="order_number">Order Number:</label>
        
        </div>
        <div class="col-md-4 form-cell"><input type="text" id="order_number" name="order_number" required></div>
        <div class="col-md-2 form-cell">
        <label for="status">Status:</label>
 
        </div>
        <div class="col-md-4 form-cell">
        <select id="status" name="status">
            <option value="shipped">shipped</option>
            <option value="completed">completed</option>
            <option value="left in store">left in store</option>
        </select>
        </div>    
        <div class="col-md-2 form-cell">
        <label for="representation">Representation:</label>
        
        </div>
        <div class="col-md-4 form-cell"><input type="text" id="representation" name="representation" required></div>
        <div class="col-md-2 form-cell">
        <label for="contact">Contact Person:</label>
        
        </div>
        <div class="col-md-4 form-cell"><input type="text" id="contact" name="contact" ></div>    

        <div class="col-md-2 form-cell">
        <label for="date_entered">Date Entered:</label>
        
        </div>
        <div class="col-md-4 form-cell"><input type="date" id="date_entered" name="date_entered" required></div> 

        <div class="col-md-2 form-cell">
        <label for="due_date">Due Date:</label>
        
            </div>
           
            <div class="col-md-4 form-cell"> <input type="date" id="due_date" name="due_date" required></div>  
            </div>
<div class="row">   
<div class="col-md-2 form-cell">  <label for="notes">Notes:</label></div>
            <div class="col-md-10 form-cell">
       
        <textarea id="notes" name="notes" col="5"></textarea>
            </div>
        </div>
    </div>
    <div class="wrapper-box mt-3 mb-3">
        <div class="table itemtable">
            <div class="row firstrow">
                <div class="cell">Sku</div>
                <div class="cell">Product Name</div>
                <div class="cell">Price</div>
                <div class="cell">Discount(%)</div>
                <div class="cell">Qty</div>
                <div class="cell">Total</div>
            </div>
            <approitem v-for="(item, index) in items" :key="index" @update-index="updateindex(index)" @update-qty="updateqty" @update-price="updateprice"></approitem>
            <div class="row">
                <div class="cell">Total</div>
                <div class="cell"></div>
                <div class="cell"></div>
                <div class="cell"></div>
                <div class="cell inputcell"><input type="text" id="total_quantity" name="total_quantity" :value="totalQuantity" readonly> </div>
                <div class="cell inputcell"><input type="text" id="total_price" name="total_price"  :value="totalPrice"  readonly></div>
            </div>

        </div>
        <a class="btn add-item" @click="additem()">Add item</a>
    </div>

            

        
        
        <input type="submit" class="submit-btn" value="Submit">
        
    </form>


</form>
</div>
</div>
<script>
const callmyapp = myapp.mount('#app');

</script>
</body>
</html>
