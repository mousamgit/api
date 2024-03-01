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
include_once('mkdir.php');

$filepath = dirname($_SERVER['DOCUMENT_ROOT']) . '/matrixify-export/sd-deletion.csv';
$fp = fopen($filepath, 'w');

$headers = array("Variant SKU", "Handle", "Command");
fputcsv($fp, $headers);

function readCSVFromURL($url) {
    $data = [];
    $header = null;

    $file = fopen($url, 'r');
    if ($file) {
        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            if ($header === null) {
                $header = $row;
            } else {
                $data[] = $row;
            }
        }
        fclose($file);
    }

    return $data;
}

function compareCSV($csv1Data, $csv2Data) {
    $csv3 = [];

    foreach ($csv1Data as $row1) {
        $found = false;
        foreach ($csv2Data as $row2) {
            if ($row1['Handle'] === $row2['Handle']) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $row1[] = 'DELETE';
            $csv3[] = $row1;
        }
    }

    return $csv3;
}

$csv1URL = 'http://samsgroup.info/export/sd-shopify.csv';
$csv2URL = 'http://samsgroup.info/matrixify-export/SD_Export.csv';

$csv1Data = readCSVFromURL($csv1URL);
$csv2Data = readCSVFromURL($csv2URL);

$csv3Data = compareCSV($csv1Data, $csv2Data);

foreach ($csv3Data as $row) {
    fputcsv($fp, $row);
}

fclose($fp);

$count = count($csv3Data);
echo "<h2>SD SKUs Prepared for Deletion</h2><br>";
echo "Total Products for deletion: " . $count . "<br>";
echo "<a style='font-weight:bold;' href='https://pim.samsgroup.info/matrixify-export/sd-deletion.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a") . "<br>";

?>
</div>
</body>
</html>