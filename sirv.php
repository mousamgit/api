<?php
include_once('connect.php');

$query = 'SELECT * FROM pim WHERE (brand = "sapphire dreams" AND specifications LIKE "%cert%");';
$result = mysqli_query($con, $query) or die(mysqli_error($con));

$csvFilePath = $_SERVER['DOCUMENT_ROOT'] . '/sirv/sirv.csv';
$xmlFilePath = $_SERVER['DOCUMENT_ROOT'] . '/sirv/sirv.xml';

$csvFile = fopen($csvFilePath, 'w');

$headers = array("SKU", "Brand", "Measurement", "Stone_Intensity", "Product_Type", "Stone_Specifications", "Stone_Shape", "Collections", "Carat_Weight", "Stone_Colour", "Treatment", "Description", "Centre_Stone_SKU");
fputcsv($csvFile, $headers);

$numrows = mysqli_num_rows($result);

while ($row = mysqli_fetch_assoc($result)) {
    // Metafield: Stone Specifications
    if (strpos($row[specifications], "Certificate") !== false) {
        $stone_specifications = "ID No.: " . str_replace("SDS", "", $row[sku]) . "<br>Colour: " . strtoupper($row[colour]) . "<br>Shape: " . strtoupper($row[shape]) . "<br>Weight: " . $row[carat] . "ct<br>Size: " . $row[measurement] . "<br>Origin: AUSTRALIA";
    } else {
        $stone_specifications = "";
    }

    // Descriptions, if loose sapphire generate description else import from field description
    if (strtolower($row[type]) == "loose sapphires") {
        $description = (strtolower($row[treatment]) == "unheated")
            ? "An unheated Australian " . ucfirst(strtolower($row[shape])) . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . "."
            : "An Australian " . ucfirst(strtolower($row[shape])) . " cut " . $row[colour] . " sapphire weighing " . $row[carat] . "ct and measures " . $row[measurement] . ".";
    } else {
        $description = $row['description'];
    }

    $content = array(
        0 => $row[sku],
        1 => $row[brand],
        2 => $row[measurement],
        3 => $row[carat],
        4 => $row[type],
        5 => $stone_specifications,
        6 => $row[shape],
        7 => $row[collections],
        8 => $row[carat],
        9 => $row[colour],
        10 => $row[treatment],
        11 => $description,
        12 => $row[centre_stone_sku]
    );

    fputcsv($csvFile, $content);
}

fclose($csvFile);

// Convert CSV to XML
$csvContent = file_get_contents($csvFilePath);
$csvRows = explode("\n", $csvContent);
$xmlData = new SimpleXMLElement('<data></data>');

foreach ($csvRows as $csvRow) {
    if (!empty($csvRow)) {
        $xmlRow = $xmlData->addChild('row');
        $csvFields = str_getcsv($csvRow);

        foreach ($headers as $index => $header) {
            $xmlRow->addChild($header, $csvFields[$index]);
        }
    }
}

// Save XML file
$xmlData->asXML($xmlFilePath);

$count = $numrows;

date_default_timezone_set('Australia/Sydney');
echo "SIRV Export Completed!<br>";
echo "Total Products Uploaded: " . $count . "<br>";
echo date("Y-m-d G:i a");

// Close database connection
mysqli_close($con);
?>
