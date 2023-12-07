<?php

$folder = dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/export/';

if (!is_dir($folder)) {
  mkdir($folder, 0755);
  echo "Folder Missing! New folder created at ".$folder."<br><br><br>";
  sleep(20);
}



?>
