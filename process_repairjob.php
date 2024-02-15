<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';
?>

<html>
    <head>
        <?php include 'header.php'; ?>
        <title> SGA PIM - Repair Job Processing... </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    </head>
    <body>
        <?php include 'topbar.php'; ?>
        <?php
            /* Posted Values to Variables */
            if(isset($_POST['job_number'])) $job_number=$_POST['job_number'];
            if(isset($_POST['reference_number'])) $reference_number=$_POST['reference_number'];
            if(isset($_POST['due_date'])) $date=$_POST['due_date'];
            if(isset($_POST['cust_code'])) $cust_code=$_POST['cust_code'];
            if(isset($_POST['cust_name'])) $cust_name=$_POST['cust_name'];
            if(isset($_POST['contact'])) $contact=$_POST['contact'];
            if(isset($_POST['product'])) $product=$_POST['product'];
            if(isset($_POST['type'])) $type=$_POST['type'];
            if(isset($_POST['jewellery-tasks'])) $jewellery_tasks=$_POST['jewellery-tasks'];
            if(isset($_POST['watch-tasks'])) $watch_tasks=$_POST['watch-tasks'];
            if(isset($_POST['taskprice'])) $taskprice=$_POST['taskprice'];
            if(isset($_POST['notes'])) $notes=$_POST['notes'];

            /* Check Files Uploaded */
            if(isset($_POST['Submit'])){ 
                // File upload configuration 
                $targetDir = "repair-images/"; 
                $allowTypes = array('jpg','png','jpeg','gif'); 
                $fileNames = array_filter($_FILES['files']['name']); 
            }
            if(!empty($fileNames)){ 
                foreach($_FILES['files']['name'] as $key=>$val){ 
                    // File upload path 
                    $fileName = basename($_FILES['files']['name'][$key]);
                    date_default_timezone_set("Australia/Sydney");
                    $imageDate = date("Y-m-d-H-i-s"); 
                    $targetFilePath = $targetDir . $job_number."-".$cust_code."-".$imageDate."-".$fileName;
                    $fileSize = $_FILES["files"]["tmp_name"][$key];
        
                    // Check whether file type is valid 
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                    if(filesize($fileSize) > 0)
                    {
                        if(in_array($fileType, $allowTypes)){ 
                            // Upload file to server 
                            move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath);
                            $uploaded_files[] = $targetFilePath;
                            $success++;
                            $count++;
                        }
                        else {
                            $failed_files[] = $fileName;
                            $failed++;
                            $count++;
                        }
                    }
                    else {
                        $failed_files[] = $fileName;
                        $failed++;
                        $count++;
                    }
                }
            }
            $images = "";
            foreach ($uploaded_files as $key=>$val)
            {
                $images .= $val.",";
            }

            $tasks = "";
            if($type == "jewellery"){ $tasks1 = $jewellery_tasks; }
            else { $tasks1 = $watch_tasks; }

            for ($i = 0; $i < count($tasks1); $i++)
            {
                $tasks .= $tasks1[$i].",".$taskprice[$i]."-";
            }

            if(!empty($job_number)){
                $sql = "INSERT into repairs (job_number, cust_code, cust_ref, cust_name, contact, product, images, repair_type, tasks, team_member, due_date, status, notes) VALUES ('$job_number', '$cust_code', '$reference_number', '$cust_name', '$contact', '$product', '$images', '$type', '$tasks', '$username', '$date' , 'created', '$notes') ";
                $result = mysqli_query($con, $sql) or die(mysqli_error($con));
            }
        ?>
        <div class="pim-padding">
            <div style="float:left; width:49%;"><a href="https://pim.samsgroup.info/repairs.php"><i class="fa-solid fa-left-long"></i> View All Repairs</a></div>
            <?php 
                $quickLookup = "SELECT id from repairs where job_number='".$job_number."' and cust_code='".$cust_code."' and cust_name='".$cust_name."' and cust_ref='".$reference_number."' ";
                $quickResult = mysqli_query($con, $quickLookup) or die(mysqli_error($con));
                        while ($row = mysqli_fetch_assoc($quickResult)){
                            echo '<div style="float:right; width:49%; text-align:right;"><a href="https://pim.samsgroup.info/view_repair.php?id='.$row[id].'">View Added Repair Job'.$job_number.' <i class="fa-solid fa-right-long"></i></a></div>';
                        }
            ?>
            
            <table class="sga-table producttable">
            <thead><tr><td colspan="1000"><h2>Repair added successfully!</h2></td></tr></thead>
            <tbody>
            <tr><td class="l"> Job Number: </td> <td><b><?php echo $job_number; ?></b></td></tr>
            <tr><td class="l"> Customer Code: </td> <td><?php echo $cust_code; ?></td></tr>
            <tr><td class="l"> Contact: </td> <td><?php echo $contact; ?></td></tr>
            <tr><td class="l"> Reference: </td> <td><?php echo $reference_number; ?></td></tr>
            <tr><td class="l"> Type: </td> <td><?php echo $type; ?></td></tr>
            </tbody>
            </table>
        </div>
        <?php exit(); ?>
    </body>
</html>
