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
            if(isset($_POST['repair_number'])) $repair_number=$_POST['repair_number'];
            if(isset($_POST['reference_number'])) $reference_number=$_POST['reference_number'];
            if(isset($_POST['reference_number'])) $reference_number=$_POST['reference_number'];

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
                    $targetFilePath = $targetDir . $fileName;
                    $fileSize = $_FILES["files"]["tmp_name"][$key];
        
                    // Check whether file type is valid 
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                    if(filesize($fileSize) > 0)
                    {
                        if(in_array($fileType, $allowTypes)){ 
                            // Upload file to server 
                            move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath);
                            $uploaded_files[] = $fileName;
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
            foreach ($uploaded_files as $key=>$val)
            {
                echo "<li>".$val."</li>";
            }



        ?>
    </body>
</html>
