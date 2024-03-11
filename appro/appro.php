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
$contact = getValue('appro', 'id', $approid, 'contact');
$notes = getValue('appro', 'id', $approid, 'notes');
?>
<div  id="app"  class="container ">
    <div class="form-design appro-update">
    <a href="./appro_list.php" >back to appro list</a>
    <div class="header mt-3">Appro: <?php echo $appro ?></div>
    <div class="wrapper-box">
    <div class="row">
        <div class="col-md-2">Customer: </div>
        <div class="col-md-10">
            <form class="editform"  v-if="isediting('a','customer')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="customer">
            <input type="hidden" name="oldValue" value="<?php echo $customer ?>">
            <input name="newValue" type="text" value="<?php echo $customer ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $customer ?><a  @click="editdata('a','customer')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">Order Number: </div>
        <div class="col-md-4"> 
            <form class="editform"  v-if="isediting('a','order')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="ordernumber">
            <input type="hidden" name="oldValue" value="<?php echo $order ?>">
            <input name="newValue" type="text" value="<?php echo $order ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $order ?><a  @click="editdata('a','order')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>         
        </div>
        <div class="col-md-2">Status: </div>
        <div class="col-md-4">  
            <form class="editform"  v-if="isediting('a','itemstatus')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="itemstatus">
            <input type="hidden" name="oldValue" value="<?php echo $itemstatus ?>">
                        <select name="newValue">
                <option value="shipped">shipped</option>
                <option value="completed">completed</option>
            </select>
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $itemstatus ?><a  @click="editdata('a','itemstatus')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>     
        </div>
    </div>
    <div class="row">    
        <div class="col-md-2">Date Entered: </div>
        <div class="col-md-4"> 
            <form class="editform"  v-if="isediting('a','dateentered')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="dateentered">
            <input type="hidden" name="oldValue" value="<?php echo $dateentered ?>">
            <input name="newValue" type="date" value="<?php echo $dateentered ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $dateentered ?><a  @click="editdata('a','dateentered')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>   
        </div>
        <div class="col-md-2">Date Due: </div>
        <div class="col-md-4"> 
            <form class="editform"  v-if="isediting('a','datedue')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="datedue">
            <input type="hidden" name="oldValue" value="<?php echo $datedue ?>">
            <input name="newValue" type="date" value="<?php echo $datedue ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $datedue ?><a  @click="editdata('a','datedue')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>   
        </div>
    </div>
    <div class="row">    
        <div class="col-md-2">Representation: </div>
        <div class="col-md-4"> 
            <form class="editform"  v-if="isediting('a','representation')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="representation">
            <input type="hidden" name="oldValue" value="<?php echo $representation ?>">
            <input name="newValue" type="text" value="<?php echo $representation ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else><?php echo $representation ?><a  @click="editdata('a','representation')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>   
        </div>
        <div class="col-md-2">Contact Person: </div>
        <div class="col-md-4"> 
            <form class="editform"  v-if="isediting('a','contact')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="contact">
            <input type="hidden" name="oldValue" value="<?php echo $contact ?>">
            <input name="newValue" type="text" value="<?php echo $contact ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else><?php echo $contact ?><a  @click="editdata('a','contact')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>   
        </div>
    </div>
    <div class="row">    
        <div class="col-md-2">Notes: </div>
        <div class="col-md-10"> 
            <form class="editform"  v-if="isediting('a','notes')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="colName" value="notes">
            <input type="hidden" name="oldValue" value="<?php echo $notes ?>">
            <input name="newValue" type="text" value="<?php echo $notes ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $notes ?><a  @click="editdata('a','notes')"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>   
        </div>
        </div>
    <div class="row"> 
    <div class="col-md-12">Items: </div>
        <div class="col-md-12 itemcell">
        <div class="table itemtable">
            <div class="row firstrow">
                <div class="cell">Sku</div>
                <div class="cell">Price</div>
                <div class="cell">Discount</div>
                <div class="cell">Qty</div>
                <div class="cell">Total</div>
            </div>

            <?php

    $records_per_page = 100;
    $itemQuery = " SELECT * from approitems WHERE  `approid`= '$appro'";
    $itemresult = getResult($itemQuery , $records_per_page);
    ?>


<?php

    for ($i = 0; $itemrow = mysqli_fetch_assoc($itemresult); $i++) :
    ?>
    <div class="row">
        <div class="cell"><?php echo $itemrow[itemcode] ?></div>
        <div class="cell inputcell">
            <form class="editform"  v-if="isediting('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[itemprice]; ?>')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="itemid" value="<?php echo $itemrow[id]; ?>">
            <input type="hidden" name="colName" value="itemprice">
            <input type="hidden" name="oldValue" value="<?php echo $itemrow[itemprice] ?>">
            <input name="newValue" type="text" value="<?php echo $itemrow[itemprice] ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $itemrow[itemprice] ?><a  @click="editdata('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[itemprice]; ?>')"><i class="fa fa-pencil" aria-hidden="true"></i></a></i></div>
        </div>
        <div class="cell inputcell">
            <form class="editform"  v-if="isediting('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[discount]; ?>')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="itemid" value="<?php echo $itemrow[id]; ?>">
            <input type="hidden" name="colName" value="discount">
            <input type="hidden" name="oldValue" value="<?php echo $itemrow[discount] ?>">
            <input name="newValue" type="text" value="<?php echo $itemrow[discount] ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $itemrow[discount] ?><a  @click="editdata('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[discount]; ?>')"><i class="fa fa-pencil" aria-hidden="true"></i></a></i></div>
        </div>
        <div class="cell inputcell">
            <form class="editform"  v-if="isediting('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[itemquantity]; ?>')"  action="update_appro.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <input type="hidden" name="appro" value="<?php echo $appro ?>">
            <input type="hidden" name="itemid" value="<?php echo $itemrow[id]; ?>">
            <input type="hidden" name="colName" value="itemquantity">
            <input type="hidden" name="oldValue" value="<?php echo $itemrow[itemquantity] ?>">
            <input name="newValue" type="text" value="<?php echo $itemrow[itemquantity] ?>">
            <button type="submit" ><i class="fa fa-check" aria-hidden="true"></i></button></form>
            <div class="editfield" v-else ><?php echo $itemrow[itemquantity] ?><a  @click="editdata('<?php echo $itemrow[id]; ?>','<?php echo $itemrow[itemquantity]; ?>')"><i class="fa fa-pencil" aria-hidden="true"></i></a></i></div>
        </div>
        <div class="cell">{{ calculateTotal('<?php echo $itemrow[itemquantity] ?>','<?php echo $itemrow[itemprice] ?>','<?php echo $itemrow[discount] ?>') }}</div>
    </div>    

  <?php endfor; ?>

  <div class="row firstrow">
                <div class="cell">Total</div>
                <div class="cell"></div>
                <div class="cell"></div>
                <div class="cell">{{ calculateQty() }}</div>
                <div class="cell">{{ calculateSum() }}</div>
  </div>

    
   

        </div>

        </div>
    </div>
</div>
    
        <div class="comment-container">
            <h2>Comments</h2>
            <?php

  $baseQuery = " SELECT * from approcomment WHERE  `approid`=$approid ORDER BY `approcomment`.`date` DESC, `approcomment`.`time` DESC ";
  $commentresult = getResult($baseQuery , $records_per_page);
  $total_pages = getTotalPages($baseQuery , $records_per_page);


  while ($commentrow = mysqli_fetch_assoc($commentresult)){

    echo '<div class="comment-box">';
    echo '<div class="comment-header">';
    echo '<div class="comment-user"><span class="username">'.$commentrow[user].' </span>says:</div>';
    echo '<div class="comment-date">'.$commentrow[date].$commentrow[time].'</div>';
    echo '</div>';
    echo '<div class="comment-content">'.$commentrow[comment].'</div>';
    echo '</div>';

}
            ?>
        <form  class="comment-form" action="add_comments.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username ?>">
            <input type="hidden" name="id" value="<?php echo $approid ?>">
            <textarea id="comment" name="comment" col="5" required></textarea>
            <button class="btn" type="reset" >Reset</button><button class="btn" type="submit" >Submit</button></form>
        </form>
            
        </div>
</div>
</div>
</div>
<script>
const callmyapp = myapp.mount('#app');
</script>
</body>
</html>