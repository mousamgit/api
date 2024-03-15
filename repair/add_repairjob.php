<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';
?>
<html>
    <head>
        <?php include 'header.php'; ?>
        <title> SGA PIM - Add a Repair Job </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
        <script src="./repair.js" ></script>
        <script src="./autofill/autofill.js"></script>

        <script>
            $(function() {
                $('#type').change(function(){
                    $('.task').hide();
                    $('#' + $(this).val()).show();
                if($(this).val() == 'all') {
                    $('.task').show();
                }
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
    <div id="app" class="pim-padding">
        <form action="process_repairjob.php" class="form-design" method="post" enctype="multipart/form-data">
        <div><a href="https://pim.samsgroup.info/repairs.php"><i class="fa-solid fa-left-long"></i> View All Repairs</a></div><br>
            <div class="form-row header"> Add a Repair Job </div>
            <div class="l-div">
            <div class="wrapper-box">
                <div class="form-row subheader">Job Details</div>
                <div class="form-row">
                    <div class="cell-l">Job Number:</div> 
                    <div class="cell-r">
                        <input type="text" name="job_number" id="job_number" placeholder="Enter a Repair Number *" required>
                        <!-- <autofill  :col1="'job_number'"  :db="'repairs'"  :placeholder="'Enter a Repair Number * '"  :inputname="'job_number'" :req="true"></autofill> -->
                    </div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Customer Reference:</div> 
                    <div class="cell-r"><input type="text" name="reference_number" id="reference_number" placeholder="Enter a Reference Number"></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Due Date:</div> 
                    <div class="cell-r"><input type="date" name="due_date" id="due_date" placeholder="DD/MM/YYYY *" required></div>
                </div>
                <div class="form-row subheader" style="margin-top: 80px;"><span>Customer Details</span></div>
                <div class="form-row">
                    <div class="cell-l">Customer Code:</div> 
                    <div class="cell-r"><input type="text" name="cust_code" id="cust_code" placeholder="Enter Customer Code *" required></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Customer Name:</div>
                    <div class="cell-r"><input type="text" name="cust_name" id="cust_name" placeholder="Enter Customer Name" ></div>
                </div>
                <div class="form-row">
                    <div class="cell-l"><label>Contact Details:</div>
                    <div class="cell-r"><input type="text" name="contact" id="contact" placeholder="Enter Contact Number or Email" ></div>
                </div>
                <div class="form-row">
                    <div class="cell-l"><label>Contact Address:</div>
                    <div class="cell-r"><input type="text" name="address" id="address" placeholder="Customer Address" ></div>
                </div>
                <div class="form-row subheader" style="margin-top: 80px;"><span>Product Details</span></div>
                <div class="form-row">
                    <div class="cell-l">Product Code:</div>
                    <div class="cell-r"><input type="text" name="product" id="product" placeholder="Enter Product Code *" required></div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Images: </div>
                    <div class="cell-r"><input type="file" name="files[]" id="files" class="file-input-large" multiple></div>
                </div>
            </div>
        </div>
            <div class="r-div">
            <div class="wrapper-box">
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
                    <div class="cell-l">Repair Type:</div>
                    <div class="cell-r">
                        <select name="type" id="type" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="jewellery">Jewellery Repair</option>
                            <option value="watch">Watch Repair</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="cell-l">Notes:</div>
                    <div class="cell-r"><input type="text" name="notes" id="notes" placeholder="Extra comments or notes"></div>
                </div>
                <div id="jewellery" class="task jewellery j-tasks">
                    <div class="task-header">Jewellery Repair Tasks<button class="add_button"><i class="fa-solid fa-plus"></i></button></div>
                    <div id="item-container-j" class="item-container-j"></div>
                </div>
                <div id="watch" class="task watch w-tasks">
                    <div class="task-header">Watch Repair Tasks<button class="add_buttonw"><i class="fa-solid fa-plus"></i></button></div>
                    <div id="item-container-w" class="item-container-w"></div>
                </div>
                <div class="form-row">
                    <input type="hidden" id="user" name="user" value="<?php echo $username;?>">
                    <button type="submit" id="submit" name="Submit" class="submit-btn">Submit</button>
                </div>
            </div>
            </div>
        </form>
    </div>
    <script>
    const callmyapp = myapp.mount('#app');
</script>
    </body>
</html>