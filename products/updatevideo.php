<?php
include '../login_checking.php';
include '../functions.php';
require('../connect.php');

$sql = "SELECT sku FROM pim Where retail_exclusive = 1"; 
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../header.php'; ?>
    <title>updating animation column</title>
</head>
<body class="updatevideo">
    <span>updating animation column...</span>
<?php
 echo '<table>';
// Check if there are records in the result set
if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {


        $spinFile = 'https://samsgroup.sirv.com/SD-Product/Sapphire%20Dreams%20Products/' . $row["sku"] .'/'. $row["sku"] .  '.spin';
        $spinExists = get_headers($spinFile)[0] === 'HTTP/1.1 200 OK';
        if ($spinExists) {
            
            updateValue('pim','sku',$row["sku"],'animation','available');
            echo  '<tr><td>' .$row["sku"].'</td><td>available</td></tr>';
        }
        else{

            updateValue('pim','sku',$row["sku"],'animation','unavailable');
            echo  '<tr><td>' .$row["sku"].'</td><td>unavailable</td></tr>';
        }

    }
    
    

} else {
    echo '0 results';
}
echo '</table>';
echo 'updating completed click <a href="https://pim.samsgroup.info/">here</a> to go back to home page';
?>
</body>
</html>