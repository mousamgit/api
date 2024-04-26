<?php
  include '../login_checking.php';
  include '../functions.php';
?>
<head>
<?php include '../header.php'; ?>
</head>
<?php include '../topbar.php'; ?>
<div class="pim-padding">
<?php
 
 $startScriptTime=microtime(TRUE);
 include ('connect.php');
 /*$del = "TRUNCATE TABLE `pimRAW`";*/

$count = 0;

 if(isset($_POST['custcode'])) $custcode=$_POST['custcode'];
 if(isset($_POST['imgtype'])) $imgtype=$_POST['imgtype'];

 if(isset($_POST["Submit"])){

    $filename=$_FILES["file"]["tmp_name"];
     if($_FILES["file"]["size"] > 0)
     {
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
          $sku[] = $getData[0];
          $count++;
        }
        fclose($file);

     }
  }

  $zip = new ZipArchive();
  $zip_file_name = 'export/images-'.$imgtype."-".strtolower($custcode)."-".time().".zip";
  $web_imgFile = $_SERVER['DOCUMENT_ROOT']."/../pim-images/";
  $high_imgFile = $_SERVER['DOCUMENT_ROOT']."/../highres/";
  $web_imgURL = 'https://samsgroup.info/pim-images/';


  if($zip->open($zip_file_name, ZipArchive::CREATE) === true){
    foreach ($sku as $value)
    {
      $imgCount = 0;
      $totalImg = 0;
      echo $value." - ";
      if($imgtype == "webres"){
        $directImg = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value.".jpg");
        $directImg2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_2.jpg");
        $directImg3 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_3.jpg");
        $directImg4 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_4.jpg");
        $directImg5 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_5.jpg");
        $directImg6 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_6.jpg");
        $directImg22 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-2.jpg");
        $directImg33 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-3.jpg");
        $directImg44 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-4.jpg");
        $directImg55 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-5.jpg");
        $directImg66 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-6.jpg");
        $directImgPNG = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value.".png");
        $directImgPNG2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_2.png");
        $directImgPNG3 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_3.png");
        $directImgPNG4 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_4.png");
        $directImgPNG5 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_5.png");
        $directImgPNG6 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_6.png");
        $directImgJPEG = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value.".png");
        $directImgJPEG2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_2.png");
        $directImgJPEG3 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_3.png");
        $directImgJPEG4 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_4.png");
        $directImgJPEG5 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_5.png");
        $directImgJPEG6 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_6.png");
        $directImgopenfront = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_openfront.png");
        $directImgdashopenfront = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-openfront.png");
        $directImgclosed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_closed.png");
        $directImgdashclosed = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-closed.png");
        $directImgmain = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_main.png");
        $directImgdashmain = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-main.png");
        $directImgopenback = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."_openback.png");
        $directImgdashopenback = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $web_imgFile.$value."-openback.png");
        
        if ( file_exists($directImg) ){ $zip->addFile($directImg, $value.".jpg"); $imgCount++; }
        if ( file_exists($directImg2) ){ $zip->addFile($directImg2, $value."_2.jpg"); $imgCount++; }
        if ( file_exists($directImg3) ){ $zip->addFile($directImg3, $value."_3.jpg"); $imgCount++; }
        if ( file_exists($directImg4) ){ $zip->addFile($directImg4, $value."_4.jpg"); $imgCount++; }
        if ( file_exists($directImg5) ){ $zip->addFile($directImg5, $value."_5.jpg"); $imgCount++; }
        if ( file_exists($directImg6) ){ $zip->addFile($directImg6, $value."_6.jpg"); $imgCount++; }
        if ( file_exists($directImg22) ){ $zip->addFile($directImg22, $value."-2.jpg"); $imgCount++; }
        if ( file_exists($directImg33) ){ $zip->addFile($directImg33, $value."-3.jpg"); $imgCount++; }
        if ( file_exists($directImg44) ){ $zip->addFile($directImg44, $value."-4.jpg"); $imgCount++; }
        if ( file_exists($directImg55) ){ $zip->addFile($directImg55, $value."-5.jpg"); $imgCount++; }
        if ( file_exists($directImg66) ){ $zip->addFile($directImg66, $value."-6.jpg"); $imgCount++; }
        if ( file_exists($directImgPNG) ){ $zip->addFile($directImgPNG, $value.".png"); $imgCount++; }
        if ( file_exists($directImgPNG2) ){ $zip->addFile($directImgPNG2, $value."_2.png"); $imgCount++; }
        if ( file_exists($directImgPNG3) ){ $zip->addFile($directImgPNG3, $value."_3.png"); $imgCount++; }
        if ( file_exists($directImgPNG4) ){ $zip->addFile($directImgPNG4, $value."_4.png"); $imgCount++; }
        if ( file_exists($directImgPNG5) ){ $zip->addFile($directImgPNG5, $value."_5.png"); $imgCount++; }
        if ( file_exists($directImgPNG6) ){ $zip->addFile($directImgPNG6, $value."_6.png"); $imgCount++; }
        if ( file_exists($directImgJPEG) ){ $zip->addFile($directImgJPEG, $value.".JPEG"); $imgCount++; }
        if ( file_exists($directImgJPEG2) ){ $zip->addFile($directImgJPEG2, $value."_2.JPEG"); $imgCount++; }
        if ( file_exists($directImgJPEG3) ){ $zip->addFile($directImgJPEG3, $value."_3.JPEG"); $imgCount++; }
        if ( file_exists($directImgJPEG4) ){ $zip->addFile($directImgJPEG4, $value."_4.JPEG"); $imgCount++; }
        if ( file_exists($directImgJPEG5) ){ $zip->addFile($directImgJPEG5, $value."_5.JPEG"); $imgCount++; }
        if ( file_exists($directImgJPEG6) ){ $zip->addFile($directImgJPEG6, $value."_6.JPEG"); $imgCount++; }
        if ( file_exists($directImgopenfront) ){ $zip->addFile($directImgopenfront, $value."_openfront.png"); $imgCount++; }
        if ( file_exists($directImgdashopenfront) ){ $zip->addFile($directImgdashopenfront, $value."-openfront.png"); $imgCount++; }
        if ( file_exists($directImgclosed) ){ $zip->addFile($directImgclosed, $value."_closed.png"); $imgCount++; }
        if ( file_exists($directImgdashclosed) ){ $zip->addFile($directImgdashclosed, $value."-closed.png"); $imgCount++; }
        if ( file_exists($directImgmain) ){ $zip->addFile($directImgmain, $value."_main.png"); $imgCount++; }
        if ( file_exists($directImgdashmain) ){ $zip->addFile($directImgdashmain, $value."-main.png"); $imgCount++; }
        if ( file_exists($directImgopenback) ){ $zip->addFile($directImgopenback, $value."_openback.png"); $imgCount++; }
        if ( file_exists($directImgdashopenback) ){ $zip->addFile($directImgdashopenback, $value."-openback.png"); $imgCount++; }
      }
      elseif($imgtype == "highres"){
        $directImg = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value.".jpg");
        $directImg2 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."_2.jpg");
        $directImg3 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."_3.jpg");
        $directImg4 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."_4.jpg");
        $directImg5 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."_5.jpg");
        $directImg6 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."_6.jpg");
        $directImg22 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."-2.jpg");
        $directImg33 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."-3.jpg");
        $directImg44 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."-4.jpg");
        $directImg55 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."-5.jpg");
        $directImg66 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $high_imgFile.$value."-6.jpg");
        if ( file_exists($directImg) ){ $zip->addFile($directImg, $value.".jpg"); $imgCount++; }
        if ( file_exists($directImg2) ){ $zip->addFile($directImg2, $value."_2.jpg"); $imgCount++; }
        if ( file_exists($directImg3) ){ $zip->addFile($directImg3, $value."_3.jpg"); $imgCount++; }
        if ( file_exists($directImg4) ){ $zip->addFile($directImg4, $value."_4.jpg"); $imgCount++; }
        if ( file_exists($directImg5) ){ $zip->addFile($directImg5, $value."_5.jpg"); $imgCount++; }
        if ( file_exists($directImg6) ){ $zip->addFile($directImg6, $value."_6.jpg"); $imgCount++; }
        if ( file_exists($directImg22) ){ $zip->addFile($directImg22, $value."-2.jpg"); $imgCount++; }
        if ( file_exists($directImg33) ){ $zip->addFile($directImg33, $value."-3.jpg"); $imgCount++; }
        if ( file_exists($directImg44) ){ $zip->addFile($directImg44, $value."-4.jpg"); $imgCount++; }
        if ( file_exists($directImg55) ){ $zip->addFile($directImg55, $value."-5.jpg"); $imgCount++; }
        if ( file_exists($directImg66) ){ $zip->addFile($directImg66, $value."-6.jpg"); $imgCount++; }
      }

      if ($imgCount == 0) { echo "<span style='color:red;'> No Images Available - Please check SKU or with Watch/Graphics Team</span><br>"; }
      else { echo $imgCount." images found. <br>"; }
      

    }
    $zip->close();
  }
  echo "# of SKUs: ".$count."<br><br>";
  $endScriptTime=microtime(TRUE);
  $totalScriptTime=$endScriptTime-$startScriptTime;
  echo 'Generated in: '.number_format($totalScriptTime, 4).' seconds<br><br>';
  echo "<b>URL of Zipped File</b> <input type='text' size='100' value='https://pim.samsgroup.info/stockist-images/".$zip_file_name."' readonly>";
  echo "<br><a href='https://pim.samsgroup.info/stockist-images/'><- Go Back</a>";


 ?>
</div>