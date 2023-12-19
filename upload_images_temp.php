<html>
<head>
  <title>Images Uploaded</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/dancss.css">
  <style>
  </style>

</head>
<body>
<div class="top-bar"><img src="https://samsgroup.info/img/logo/SAMSlogo.png" width=100px></div><br><br>

<?php

$success = 0;
$failed = 0;
$count = 0;

if(isset($_POST['Import'])){ 
    // File upload configuration 
    $targetDir = "temp-images/"; 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    $fileNames = array_filter($_FILES['files']['name']); 

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
    else {
        echo "Error: No Images Uploaded";
    }
}

?>

<div class="box600">
    <h1>Image Upload Results</h1>
    <h3> A total of <?php echo $count; if ($count <= 1){ echo " file has"; }else{ echo " files have"; } ?> been submitted.</h3><br>
    <div class="left">
    View the files you've uploaded by => <a href="/temp_images.php">Clicking Here</a><br><br>
    <?php if ($failed == 0) { echo "<b>Everything has uploaded successfully!</b> <br><br>"; } else { ?>
    <?php echo $failed; if ($failed <= 1){ echo " file has"; }else{ echo " files have"; } ?> failed uploading:
    <ul>
    <?php
        foreach ($failed_files as $key=>$val)
        {
            echo "<li>".$val."</li>";
        }
    ?>
    </ul><br><br>
    <?php } ?>

    <?php if ($success == 0) { echo "<b>Nothing uploaded successfully :(</b> "; } else { ?>
    <?php echo $success; if ($success <= 1){ echo " file has"; }else{ echo " files have"; } ?> been successfully uploaded:
    <ul>
    <?php
        foreach ($uploaded_files as $key=>$val)
        {
            echo "<li>".$val."</li>";
        }
    ?>
    </ul>
    <?php } ?>
    </div>

</div>




</body>
</html>