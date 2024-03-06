<html>
<head>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Open Sans', sans-serif; }
    table { border: 2px solid #000; }
    th { font-size: 14px; font-weight: 700; border: 1px solid #000; padding:20px 40px; }
    td { font-size: 12px; font-weight: 400; border: 1px solid #000; padding: 20px; }
  </style>

</head>
<body>
<div style="margin: 0 auto; width:600px; padding:20px; background-color:#F9F6F0; text-align:center;">
<?php
$startScriptTime = microtime(TRUE);
include_once ('connect.php');

$filepath = $_SERVER['DOCUMENT_ROOT'] . '/matrixify-export/sd_skus_to_delete.csv';
$fp = fopen($filepath, 'w');

$headers = ["Handle", "Command"];
fputcsv($fp, $headers);

// Define file URLs
$importURL = 'http://samsgroup.info/export/sd-shopify.csv';
$exportURL = 'http://pim.samsgroup.info/matrixify-export/SD_Export.csv';

// Function to fetch CSV content from URL and return content as array
function fetchCSVFromURL($url)
{
    $csvData = [];

    $fileContent = file_get_contents($url);
    $lines = explode(PHP_EOL, $fileContent);

    foreach ($lines as $line) {
        $csvData[] = str_getcsv($line);
    }

    return $csvData;
}

// Fetch CSV files from URLs
$exportData = fetchCSVFromURL($exportURL);
$importData = fetchCSVFromURL($importURL);

// Define column indexes
$exportHandleIndex = 0; // Assuming 'handle' column is at index 0 in exportData
$importHandleIndex = 1; // Assuming 'handle' column is at index 1 in importData

// Extract handles from both files
$exportHandles = array_column(array_slice($exportData, 1), $exportHandleIndex);
$importHandles = array_column(array_slice($importData, 1), $importHandleIndex);

// Find missing handles
$missingHandles = array_diff($exportHandles, $importHandles);

// Output missing handles
foreach ($missingHandles as $handle) {
    fputcsv($fp,$handle);
}

fclose($fp);

$count = count($csv3Data);
echo "<h2>SD SKUs Prepared for Deletion</h2><br>";
echo "Total Products for deletion: " . $count . "<br>";
echo "<a style='font-weight:bold;' href='https://pim.samsgroup.info/matrixify-export/sd_skus_to_delete.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a") . "<br>";

echo "<table border='1'><tr><th>Import Handles</th><th>Export Handles</th><th>Missing Handles</th></tr>";

$maxCount = max(count($importHandles), count($exportHandles), count($missingHandles));

for ($i = 0; $i < $maxCount; $i++) {
    echo "<tr>";
    
    // Import Handles column
    echo "<td>";
    if (isset($importHandles[$i])) {
        echo $importHandles[$i];
    }
    echo "</td>";
    
    // Export Handles column
    echo "<td>";
    if (isset($exportHandles[$i])) {
        echo $exportHandles[$i];
    }
    echo "</td>";
    
    // Missing Handles column
    echo "<td>";
    if (isset($missingHandles[$i])) {
        echo $missingHandles[$i];
    }
    echo "</td>";
    
    echo "</tr>";
}

echo "</table>";



?>
</div>
</body>
</html>