<?php

$file = $_GET['file'];

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $file . '"');
  
    // Output the CSV content
    readfile($file);
    exit; 
?>

<script type="text/javascript">
  window.close() ;
</script> 