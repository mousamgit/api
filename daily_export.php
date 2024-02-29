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
include_once('connect.php'); // Assuming 'connect.php' contains your database connection code

$query = 'SELECT * FROM pim';
$result = mysqli_query($con, $query) or die(mysqli_error($con));

$filepath = $_SERVER['DOCUMENT_ROOT'] . '/daily_export.csv';
$fp = fopen($filepath, 'w');

$num_column = mysqli_num_fields($result);

$csv_header = '';
for ($i = 0; $i < $num_column; $i++) {
    $csv_header .= '"' . mysqli_fetch_field_direct($result, $i)->name . '",';
}
$csv_header .= "\n";

fwrite($fp, $csv_header);

while ($row = mysqli_fetch_row($result)) {
    $csv_row = '';
    for ($i = 0; $i < $num_column; $i++) {
        $csv_row .= '"' . $row[$i] . '",';
    }
    $csv_row .= "\n";
    fwrite($fp, $csv_row);
}

fclose($fp);

$error = mysqli_error($con);
if ($error != "") {
    echo "Error Occurred: " . $error . "<br>";
}

$count = mysqli_num_rows($result);
date_default_timezone_set('Australia/Sydney');
echo "<h2>Daily SGA PIM Export Completed!</h2><br>";
echo "Total of " . $count . " Products Exported<br><br>";
echo "<a style='font-weight:bold;' href='https://samsgroup.info/export/daily_export.csv'>View on Web</a><br><br>";
echo date("Y-m-d G:i a")."<br>";
$endScriptTime=microtime(TRUE);
$totalScriptTime=$endScriptTime-$startScriptTime;
echo 'Processed in: '.number_format($totalScriptTime, 4).' seconds<br><br>';

?>
</div>
</body>
</html>