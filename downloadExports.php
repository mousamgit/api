<?php
// Function to force download a CSV file
function forceDownloadCSV($csvURL, $csvName) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $csvName);
    header('Pragma: no-cache');

    readfile($csvURL);
    exit;
}

// Function to force download an XML file
function forceDownloadXML($xmlURL, $xmlName) {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename=' . $xmlName);
    header('Pragma: no-cache');

    readfile($xmlURL);
    exit;
}

if(isset($_GET['csv']) && !empty($_GET['csv'])) {
    $csvURL = $_GET['csv'];
    $csvName = basename($csvURL);
    forceDownloadCSV($csvURL, $csvName);
} elseif(isset($_GET['xml']) && !empty($_GET['xml'])) {
    $xmlURL = $_GET['xml'];
    $xmlName = basename($xmlURL);
    forceDownloadXML($xmlURL, $xmlName);
} else {
    echo "Invalid request!";
}
?>
