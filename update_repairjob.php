<?php
    include 'login_checking.php';
    include 'functions.php';
    require 'connect.php';

    if(isset($_POST['id'])) $id=$_POST['id'];
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
    if(isset($_POST['status'])) $status=$_POST['status'];
    if(isset($_POST['tracking'])) $tracking=$_POST['tracking'];
    if(isset($_POST['files'])) $uploaded_files=$_POST['files'];
    if(isset($_POST['user'])) $user=$_POST['user'];
    if(isset($_POST['address'])) $address=$_POST['address'];

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
                    $uploaded_files[] .= $targetFilePath;
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

    $checksql = "SELECT * from repairs where id = '$id' ";
    $checkresult = mysqli_query($con, $checksql) or die(mysqli_error($con));

    while($rowcheck = mysqli_fetch_assoc($checkresult)){ 

        if ($rowcheck[status] != $status)
        {
            $logsql = "INSERT into repairs_log (id, job_number, user, notes) VALUES ('$id', '$job_number', '$user', 'Updated Status: $rowcheck[status] -> $status')";
            $logresult = mysqli_query($con, $logsql) or die(mysqli_error($con));
        }
        if ($rowcheck[tracking] != $tracking)
        {
            $logsql = "INSERT into repairs_log (id, job_number, user, notes) VALUES ('$id', '$job_number', '$user', 'Added tracking number: $tracking')";
            $logresult = mysqli_query($con, $logsql) or die(mysqli_error($con));
        }
        if ($rowcheck[notes] != $notes)
        {
            $logsql = "INSERT into repairs_log (id, job_number, user, notes) VALUES ('$id', '$job_number', '$user', 'Changed customer note: $notes, Old note: $rowcheck[notes]')";
            $logresult = mysqli_query($con, $logsql) or die(mysqli_error($con));
        }
        if ($rowcheck[due_date] != $date)
        {
            $logsql = "INSERT into repairs_log (id, job_number, user, notes) VALUES ('$id', '$job_number', '$user', 'Changed due date: $rowcheck[due_date] -> $date')";
            $logresult = mysqli_query($con, $logsql) or die(mysqli_error($con));
        }

    }

    if(!empty($id)){
        $sql = "UPDATE repairs SET job_number='$job_number', cust_code='$cust_code', cust_ref='$reference_number', cust_name='$cust_name', contact='$contact', product='$product', images='$images', repair_type='$type', tasks='$tasks', due_date='$date' , status='$status', notes='$notes', tracking='$tracking', address='$address' WHERE id='$id'";
        $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    }

    header('Location: https://pim.samsgroup.info/view_repair.php?id='.$id);
    exit();

?>
