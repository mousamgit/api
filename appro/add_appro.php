<?php
include 'login_checking.php';
    include 'functions.php';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../header.php'; ?>
    <script src="./js/appro.js" ></script>
    <script src="./js/approitem.js" ></script>
    <title>add appro</title>
</head>
<body>
<div id="app" class="pim-padding">
<h1>Add Appro Form</h2>
    <form action="process_appro.php" method="post">
    
        <label for="customer_name">Customer Name:</label><br>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="appro_id">Appro ID:</label><br>
        <input type="text" id="appro_id" name="appro_id" required><br><br>
        
        <label for="order_number">Order Number:</label><br>
        <input type="text" id="order_number" name="order_number" required><br><br>

        <label for="representation">representation:</label><br>
        <input type="text" id="representation" name="representation" required><br><br>

        <label for="date_entered">Date Entered:</label><br>
        <input type="date" id="date_entered" name="date_entered" required><br><br>

        <label for="due_date">Due Date:</label><br>
        <input type="date" id="due_date" name="due_date" required><br><br>
        <div class="table itemtable">
            <div class="row firstrow">
                <div class="cell">Sku</div>
                <div class="cell">Product Name</div>
                <div class="cell">Price</div>
                <div class="cell">Qty</div>
                <div class="cell">Total</div>
            </div>
            <approitem v-for="(item, index) in items" :key="index" @update-index="updateindex(index)" @update-qty="updateqty" @update-price="updateprice"></approitem>
            <div class="row">
                <div class="cell">Total</div>
                <div class="cell"></div>
                <div class="cell"></div>
                <div class="cell"><input type="text" id="total_quantity" name="totalQuantity" :value="totalQuantity" readonly> </div>
                <div class="cell"><input type="text" id="total_price" name="totalPrice"  :value="totalPrice"  readonly></div>
            </div>

        </div>


            

        
        <a class="btn add-item" @click="additem()">Add item</a>
        <input type="submit" value="Submit">
        
    </form>


</form>
</div>

<script>
const callmyapp = myapp.mount('#app');
</script>
</body>
</html>
