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
            $(function(){
    
                var more_fields = `
                    <select name="jewellery-tasks[]" id="jewellery-task-1" required>
                        <option value="" disabled selected>Select a task</option>
                        <option value="plating">Plating (Rhodium/Gold)</option>
                        <option value="reshank">Re-Shank</option>
                        <option value="resize_under_3">Resize 1-3 Finger Sizes</option>
                        <option value="resize_more_3">Resize more than 3+ Finger Sizes</option>
                        <option value="set_stone">Set/Reset Stone</option>
                    </select><br>
                `;
                var more_fields_w = `
                    <select name="jewellery-tasks[]" id="jewellery-task-1" required>
                        <option value="" disabled selected>Select a task</option>
                        <option value="battery replacement">Battery Replacement</option>
                        <option value="bracelet or strap replacement">Bracelet or Strap Replacement</option>
                        <option value="new case">New Case</option>
                        <option value="new crown">New Case & Band</option>
                        <option value="new clasp">New Clasp</option>
                        <option value="new crown">New Crown</option>
                        <option value="new dial">New Dial</option>
                        <option value="new glass">New Glass</option>
                        <option value="new hands">New Hands</option>
                        <option value="new pin screw band">New Pin or Screw for Band</option>
                        <option value="new stem crown">New Stem & Crown</option>
                        <option value="new movement">New Movement</option>
                        <option value="pressure test">Pressure Test</option>
                        <option value="overhaul">Overhaul</option>
                        <option value="service">Service</option>
                    </select><br>
                `;

            $('#add-more-field-jewellery').on('click', (function (e) {
                e.preventDefault();
                $(".j-tasks").append(more_fields);
            }));
            $('#add-more-field-watch').on('click', (function (e) {
                e.preventDefault();
                $(".w-tasks").append(more_fields_w);
            }));

            });
        </script>
    </head>
    <body>
    <?php include 'topbar.php'; ?>
    <div class="pim-padding">
        <form action="process_repairjob.php" method="post" enctype="multipart/form-data">
            Repair Number: <input type="text" name="repair_number" id="repair_number" placeholder="Enter a Repair Number" required><br>
            Customer Code: <input type="text" name="cust_code" id="cust_code" placeholder="Enter Customer Code" required><br>
            Customer Name: <input type="text" name="cust_name" id="cust_name" placeholder="Enter Customer Name" ><br>
            Contact Details: <input type="text" name="contact" id="contact" placeholder="Enter Contact Number or Email" ><br>
            Repair Type: 
                <select name="type" id="type" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="jewellery">Jewellery Repair</option>
                    <option value="watch">Watch Repair</option>
                </select><br>
            Product Code: <input type="text" name="product" id="product" placeholder="Enter Product Code" required><br>
            <div id="jewellery" class="task jewellery j-tasks">
                Jewellery Repair Tasks <button id="add-more-field-jewellery">Add Task</button><br>
            </div>
            <div id="watch" class="task watch w-tasks">
                Watch Repair Tasks <button id="add-more-field-watch">Add Task</button><br>
            </div>
        </form>
    </div>
    </body>
</html>