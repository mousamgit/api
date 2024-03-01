<?php
    $file_url = 'http://pim.samsgroup.info/rephopper/rephopper.csv';

    // Send headers to force download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="rephopper.csv"');

    // Read the file and output it directly to the browser
    readfile($file_url);
?>