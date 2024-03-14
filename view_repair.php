<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';
    $id = $_GET['id'];
?>
<html>
    <head>
        <?php include 'header.php'; ?>
        <title> SGA PIM - View Repair Job </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
        <script>
            $(function() {
                $('.delete_button').click(function() {
                    return window.confirm("Are you sure you'd like to delete this repair?");
                });
            });
            $(document).ready(function(){
                var addButton = $('.add_button'); //Add button selector for jewellery
                var addButtonw = $('.add_buttonw'); //Add button selector for watches
                var wrapper = $('.item-container-j'); //Input field wrapper for jewellery
                var wrapperW = $('.item-container-w');
                var fieldJ = '<div class="items-j"><select name="jewellery-tasks[]" id="jewellery-task" class="l-input select-design" required><option value="" disabled selected>Select a task</option><option value="broken_earring_post">Broken Earring Post</option><option value="solder_broken_necklace">Broken Necklace / Solder</option><option value="clean_assemble_cast">Clean & Assemble Cast</option><option value="plating">Plating (Rhodium/Gold)</option><option value="repolish_stone">Re-Polish Stone / Abraded</option><option value="reshank">Re-Shank</option><option value="resize_under_3">Resize 1-3 Finger Sizes</option><option value="resize_more_3">Resize more than 3+ Finger Sizes</option><option value="reset_set_stone">Set/Reset Stone</option></select><input type="text" class="r-input" id="taskprice[]"" name="taskprice[]" placeholder="Input Cost"><button class="remove_button"><i class="fa-solid fa-x"></i></button></div>'; //New input field html  
                var fieldW = '<div class="items-w"><select name="watch-tasks[]" id="watch-task" class="l-input select-design" required> <option value="" disabled selected>Select a task</option><option value="battery replacement">Battery Replacement</option><option value="bracelet or strap replacement">Bracelet or Strap Replacement</option><option value="link removal">Link Removal</option><option value="new case">New Case</option><option value="new crown">New Case & Band</option><option value="new clasp">New Clasp</option><option value="new crown">New Crown</option><option value="new dial">New Dial</option><option value="new glass">New Glass</option><option value="new hands">New Hands</option><option value="new pin screw band">New Pin or Screw for Band</option><option value="new stem crown">New Stem & Crown</option><option value="new movement">New Movement</option><option value="pressure test">Pressure Test</option><option value="overhaul">Overhaul</option><option value="service">Service</option></select><input type="text" class="r-input" id="taskprice[]"" name="taskprice[]" placeholder="Input Cost"><button class="remove_button"><i class="fa-solid fa-x"></i></button></div>';
                
                // Once add button is clicked
                $(addButton).click(function(){ $(wrapper).append(fieldJ); //Add field html 
                });
                $(addButtonw).click(function(){ $(wrapperW).append(fieldW); //Add field html 
                });
                
                // Once remove button is clicked
                $(wrapper).on('click', '.remove_button', function(e){
                    e.preventDefault();
                    $(this).parent('div').remove(); //Remove field html
                });
                $(wrapperW).on('click', '.remove_button', function(e){
                    e.preventDefault();
                    $(this).parent('div').remove(); //Remove field html
                });
            });
        </script>
    </head>
    <body>
    <?php include 'topbar.php'; ?>
    <?php 
        $query = " SELECT * from repairs WHERE id = '".$id."'";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));

      while($row = mysqli_fetch_assoc($result)){ ?>

    <div class="pim-padding">
        <form action="update_repairjob.php" class="form-design" method="post" enctype="multipart/form-data">
        <div style="float:left; width:50%;"><a href="https://pim.samsgroup.info/repairs.php"><i class="fa-solid fa-left-long"></i> View All Repairs</a></div>
        <div style="float:right; text-align: right; width: 49%"><a class="delete_button" href="/delete_repair.php?id=<?php echo $id; ?>"><i class="fa-solid fa-trash"></i> Delete this Repair</a></div><br>
        
        <br>
            <div class="form-row header"> Repair Job <?php echo $row[job_number]; ?></div>
            <div class="l-div">
            <div class="wrapper-box">
                <div class="form-row subheader">Job Details</div>
                <div class="form-row">
                    <div class="cell-l">Job Number:</div> 
                    <div class="cell-r"><input type="text" name="job_number" id="job_number" value="<?php echo $row[job_number]; ?>"></div>
                    <?php $jobnumber = $row[job_number]; ?>
                </div>
                <div class="form-row">
                    <div class="cell-l">Created by:</div> 
                    <div class="cell-r"><?php echo $row[team_member]; ?></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Date Added:</div> 
                    <div class="cell-r"><?php echo $row[added_date]; ?></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Due Date:</div> 
                    <div class="cell-r"><input type="date" name="due_date" id="due_date" value="<?php echo $row[due_date]; ?>"></div>
                </div>
                <div class="form-row subheader" style="margin-top: 80px;"><span>Customer Details</span></div>
                <div class="form-row">
                    <div class="cell-l">Customer Code:</div> 
                    <div class="cell-r"><input type="text" name="cust_code" id="cust_code" value="<?php echo $row[cust_code]; ?>"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Customer Name:</div>
                    <div class="cell-r"><input type="text" name="cust_name" id="cust_name" value="<?php echo $row[cust_name]; ?>"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Customer Reference:</div> 
                    <div class="cell-r"><input type="text" name="reference_number" id="reference_number" value="<?php echo $row[cust_ref]; ?>"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l"><label>Contact Details:</div>
                    <div class="cell-r"><input type="text" name="contact" id="contact" value="<?php echo $row[contact]; ?>"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l"><label>Address:</div>
                    <div class="cell-r"><input type="text" name="address" id="address" value="<?php echo $row[address]; ?>"></div>
                </div>
                <div class="form-row subheader" style="margin-top: 80px;"><span>Product Details</span></div>
                <div class="form-row">
                    <div class="cell-l">Product Code:</div>
                    <div class="cell-r"><input type="text" name="product" id="product" value="<?php echo $row[product]; ?>"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Add more Images: </div>
                    <div class="cell-r"><input type="file" name="files[]" id="files" class="file-input-large" multiple></div><br><br>
                    <?php 
                        $img = rtrim($row[images],",");
                        $images = explode(",",$img); 
                        if (!empty($img) )
                        {
                            foreach ($images as $key=>$val)
                            {
                                echo '<div class="cell-l">Image:</div>';
                                echo '<div class="cell-r">';
                                echo "<img src='https://pim.samsgroup.info/" . $val . "'><br>";
                                echo "<input type='checkbox' id='files[]' name='files[]' value='" . $val . "' style='width:20px;' checked> <label for='files[]'>Keep Image</label>";
                                echo '</div>';
                            }
                        }
                    ?>
               </div>     
                </div>
            </div>
            <div class="r-div">
                <div class="wrapper-box">
                <?php 
                    switch ($row[status]) {
                        case 'received':
                            $select = 1;
                            break;
                        case 'cancelled':
                            $select = 2;
                            break;
                        case 'jeweller_aram':
                            $select = 3;
                            break;
                        case 'polisher_genlik':
                            $select = 4;
                            break;
                        case 'watchmaker_sy':
                            $select = 5;
                            break;
                        case 'shipped':
                            $select = 6;
                            break;
                        case 'pending approval':
                            $select = 7;
                            break;
                        case 'quote':
                            $select = 8;
                            break;
                        case 'completed':
                            $select = 9;
                            break;
                        }
                ?>
                <div class="form-row">
                    <div class="cell-l">Status:</div>
                    <div class='cell-r'>
                        <select id='status' name='status' class="select-design" style="width:100%;" required>
                        <option value="received" <?php if($select == 1){ echo "selected"; }?> >Received</option>
                            <option value="cancelled" <?php if($select == 2){ echo "selected"; }?> >Cancelled</option>
                            <option value="jeweller_aram" <?php if($select == 3){ echo "selected"; }?> >Jeweller - Aram</option>
                            <option value="polisher_genlik" <?php if($select == 4){ echo "selected"; }?> >Polisher - Genlik</option>
                            <option value="watchmaker_sy" <?php if($select == 5){ echo "selected"; }?> >Watchmaker - SY</option>
                            <option value="shipped" <?php if($select == 6){ echo "selected"; }?> >Shipped</option>
                            <option value="pending approval" <?php if($select == 7){ echo "selected"; }?> >Pending Approval</option>
                            <option value="quote" <?php if($select == 8){ echo "selected"; }?> >Quote</option>
                            <option value="completed" <?php if($select == 9){ echo "selected"; }?> >Completed</option>

                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Tracking:</div>
                    <div class='cell-r'><input type='text' id='tracking' name='tracking' value='<?php echo $row[tracking];?>'></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Notes:</div>
                    <div class='cell-r'><input type='text' id='notes' name='notes' value='<?php echo $row[notes];?>'></div>
                </div><br><br>
                
                <?php
                    $task = rtrim($row[tasks],"-");
                    $tasklist = explode("-",$task);
                    foreach ($tasklist as $val)
                    {
                        $tasks[] = explode(",",$val);
                    }
                    if ($row[repair_type] == "jewellery")
                    {
                        echo '<div class="form-row">';
                        echo '<div id="jewellery" class="task jewellery j-tasks" style="display:block !important;">';
                        echo '<div class="task-header" style="display:block;">Jewellery Repair Tasks<button class="add_button"><i class="fa-solid fa-plus"></i></button></div>';
                        echo '<div id="item-container-j" class="item-container-j" >';
                        for ($i = 0; $i < count($tasklist); $i++)
                        {
                            echo "<div class='items-j'><div class='l-input' style='padding:10px;'>".$tasks[$i][0]."<input type='hidden' id='jewellery-tasks[]' name='jewellery-tasks[]' value='".$tasks[$i][0]."'></div><input type='text' class='r-input' id='taskprice[]' name='taskprice[]' value='".$tasks[$i][1]."'><button class='remove_button'><i class='fa-solid fa-x'></i></button></div>";
                        }
                        echo '</div></div>';
                    }
                    else{
                        echo '<div class="form-row">';
                        echo '<div id="watch" class="task watch w-tasks" style="display:block !important;">';
                        echo '<div class="task-header">Watch Repair Tasks<button class="add_buttonw"><i class="fa-solid fa-plus"></i></button></div>';
                        echo '<div id="item-container-w" class="item-container-w">';
                        for ($i = 0; $i < count($tasklist); $i++)
                        {
                            echo "<div class='items-w'><div class='l-input' style='padding:10px;'>".$tasks[$i][0]."<input type='hidden' class='l-input' id='watch-tasks[]' name='watch-tasks[]' value='".$tasks[$i][0]."'></div><input type='text' class='r-input' id='taskprice[]' name='taskprice[]' value='".$tasks[$i][1]."'><button class='remove_button'><i class='fa-solid fa-x'></i></button></div>";
                        }
                        echo '</div></div>';
                    }
                ?>
                </div>
                <div class="form-row">
                    <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
                    <input type="hidden" id="type" name="type" value="<?php echo $row[repair_type];?>">
                    <input type="hidden" id="user" name="user" value="<?php echo $username;?>">
                    <button type="submit" id="submit" name="Submit" class="submit-btn">Update Repair</button>
                </div>
            </div>
            
        </form><br><br><br><br>
        <div class="wrapper-box">
            <div class="form-row subheader">Extra Notes and Log</div>
            <table id="myTable" class="producttable">
                <thead>
                    <tr>
                        <th style="color:#fff; font-size:14px;">Date</th>
                        <th style="color:#fff; font-size:14px;">User</th>
                        <th style="color:#fff; font-size:14px;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * from repairs_log where id='".$id."' ORDER BY `repairs_log`.`date` DESC";
                        $result = mysqli_query($con, $query) or die(mysqli_error($con));
                        while ($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row[date]."</a></td>";
                            echo "<td>".$row[user]."</td>";
                            echo "<td>".$row[notes]."</td>";
                            echo "</tr>";
                        }
                    ?>
                 </tbody>
            </table>
            <form action="update_repairnotes.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
                <input type="hidden" id="jobnumber" name="jobnumber" value="<?php echo $jobnumber;?>">
                <input type="hidden" id="user" name="user" value="<?php echo $username;?>">
                <div class="form-row">
                    <div class="cell-l" style="padding:10px 0px;">Add Extra Note:</div>
                    <div class='cell-r'><input type='text' id='lognotes' name='lognotes' placeholder="Add a Note"></div>
                </div>
                <button type="submit" id="submit" name="Submit" class="submit-btn-sml">Submit Extra Note</button>
            </form>
        </div>
    <?php } ?>
    </div></div><br><br>
    </body>
</html>