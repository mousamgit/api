<?php
    $file_url = 'http://pim.samsgroup.info/marketing/marketing-incomplete.csv';

    // Send headers to force download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="marketing-incomplete.csv"');

    // Read the file and output it directly to the browser
    readfile($file_url);
?>