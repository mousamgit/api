<?php
include '../login_checking.php';
include '../functions.php';
require('../connect.php');
    $approid = $_GET['id'];
    $username = $_SESSION["username"];

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <?php include '../header.php'; ?>
    <script src="./js/appro.js" ></script>
    <title>Appro</title>
</head>
<body>
<?php include '../topbar.php'; ?>
<?php
$appro = getValue('appro', 'id', $approid, 'appro');
$customer = getValue('appro', 'id', $approid, 'customer');
$order = getValue('appro', 'id', $approid, 'ordernumber');
$itemstatus = getValue('appro', 'id', $approid, 'itemstatus');
$dateentered  = getValue('appro', 'id', $approid, 'dateentered');
$datedue = getValue('appro', 'id', $approid, 'datedue');
$representation = getValue('appro', 'id', $approid, 'representation');
$notes = getValue('appro', 'id', $approid, 'notes');
?>
<div  id="app"  class="pim-padding">
    
    <div class="row">
        <div class="col-md-4">Appro ID: 
            <form class="editform"  v-if="isediting('appro')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="appro">
            <input type="hidden" name="oldValue" value="<?php echo $appro ?>">
            <input name="newValue" type="text" value="<?php echo $appro ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('appro')" ><?php echo $appro ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>    
        </div>
        <div class="col-md-4">Customer: 
            <form class="editform"  v-if="isediting('customer')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="customer">
            <input type="hidden" name="oldValue" value="<?php echo $customer ?>">
            <input name="newValue" type="text" value="<?php echo $customer ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('customer')" ><?php echo $customer ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>            
        </div>
        <div class="col-md-4">Order Number: 
            <form class="editform"  v-if="isediting('order')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="order">
            <input type="hidden" name="oldValue" value="<?php echo $order ?>">
            <input name="newValue" type="text" value="<?php echo $order ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('order')" ><?php echo $order ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>         
        </div>
        <div class="col-md-4">Status: 
            <form class="editform"  v-if="isediting('itemstatus')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="itemstatus">
            <input type="hidden" name="oldValue" value="<?php echo $itemstatus ?>">
                        <select name="newValue">
                <option value="shipped">shipped</option>
                <option value="completed">completed</option>
            </select>
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('itemstatus')" ><?php echo $itemstatus ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>     
        </div>
        <div class="col-md-4">Date Entered:
            <form class="editform"  v-if="isediting('dateentered')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="dateentered">
            <input type="hidden" name="oldValue" value="<?php echo $dateentered ?>">
            <input name="newValue" type="date" value="<?php echo $dateentered ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('dateentered')" ><?php echo $dateentered ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>   
        </div>
        <div class="col-md-4">Date Due: 
            <form class="editform"  v-if="isediting('datedue')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="datedue">
            <input type="hidden" name="oldValue" value="<?php echo $datedue ?>">
            <input name="newValue" type="date" value="<?php echo $datedue ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('datedue')" ><?php echo $datedue ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>   
        </div>
        <div class="col-md-4">Representation: 
            <form class="editform"  v-if="isediting('representation')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="representation">
            <input type="hidden" name="oldValue" value="<?php echo $representation ?>">
            <input name="newValue" type="text" value="<?php echo $representation ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('representation')" ><?php echo $representation ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>   
        </div>
        <div class="col-md-12">Notes: 
            <form class="editform"  v-if="isediting('notes')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="colName" value="notes">
            <input type="hidden" name="oldValue" value="<?php echo $notes ?>">
            <input name="newValue" type="text" value="<?php echo $notes ?>">
            <button type="submit" >Save</button></form>
            <a class="editfield" v-else @click="editdata('notes')" ><?php echo $notes ?><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>   
        </div>
    </div>
    <h2>Items:</h2>

    
    <div class="table itemtable">
            <div class="row firstrow">
                <div class="cell">Sku</div>
                <div class="cell">Price</div>
                <div class="cell">Qty</div>
                <div class="cell">Total</div>
            </div>
            
            <?php 
    $serializedItems = getValue('appro', 'id', $approid, 'items');

$items = unserialize($serializedItems);

// Check if the deserialization was successful
if ($items !== false && is_array($items)) {
    $itemindex = 0;
    echo '<div class="row">';
    foreach ($items as $item) {

        foreach ($item as $field => $value) {
            if ($field == 'itemcode' && $itemindex != 0) {
                echo '</div><div class="row"><div class="cell">'.$value.'</div>';
            }
            else{
                echo '<div class="cell">'.$value.'</div>';
            }
            $itemindex ++;
        }
        
    }
    echo '</div>';
} else {
    echo "Error: Unable to unserialize items data.";
}

    ?>
            
            <div class="row">
                <div class="cell">Total</div>

                <div class="cell"></div>
                <div class="cell"><?php echo getValue('appro', 'appro', $approid, 'totalquantity') ?></div>
                <div class="cell"><?php echo getValue('appro', 'appro', $approid, 'totalprice') ?></div>
            </div>

        </div>
        <div class="comment-container">
            <?php

  $records_per_page = 100;
  $baseQuery = " SELECT * from approcomment WHERE  `approid`=$approid ORDER BY `approcomment`.`date` DESC, `approcomment`.`time` DESC ";
  $commentresult = getResult($baseQuery , $records_per_page);
  $total_pages = getTotalPages($baseQuery , $records_per_page);


  while ($commentrow = mysqli_fetch_assoc($commentresult)){

    echo "<div>".$commentrow[date]."</div>";
    echo "<div>".$commentrow[time]."</div>";
    echo "<div>".$commentrow[user]."</div>";
    echo "<div>".$commentrow[comment]."</div>";

}
            ?>
        <form   action="add_comments.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <textarea id="comment" name="comment" col="5"></textarea>
            <button type="submit" >Save</button></form>
        </form>
            
        </div>
</div>
</div>
<script>
const callmyapp = myapp.mount('#app');
</script>
</body>
</html>