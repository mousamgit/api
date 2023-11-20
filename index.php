<?php
  require_once ('connect.php');
  $query = ' SELECT * from `pim` ';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));
?>

<html>
  <head>
    <title> SGA PIM </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  </head>
  <script type="text/javascript">$(document).ready( function () { $('#myTable').DataTable();} );</script>
  <body>

<div style="width:100%; border:1px solid #000; margin:10px; padding:20px;">
  <div style="display:inline-block; width:10%;"><a href="import.php">[ Import Products ]</a></div>
  <div style="display:inline-block; width:10%;"><a href="update.php">[ Update Products ]</a></div>
</div>

<?php
  echo '<table id=myTable class=display><thead><tr>';
  $row=mysqli_fetch_assoc($result);
  foreach ($row as $colName => $val) { echo '<th>'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</th>'; } // show column headers
  echo '</tr></thead><tbody>';
  mysqli_data_seek($result,0); //reset counter to 0
  while($row = mysqli_fetch_assoc($result)){
    echo '<tr>';
    foreach ($row as $colName => $val ) {
      if( $colName == "sku" ){ echo '<td><a href="/pim/product.php?sku='.$row[$colName].'">'.$row[$colName].'</a></td>';}
      elseif ($colName == "image1" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image2" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image3" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image4" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image5" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image6" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "packaging_image" && $row[$colName] != "" ) { echo '<td><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif ($colName == "image1" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "image2" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "image3" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "image4" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "image5" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "image6" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      elseif ($colName == "packaging_image" && $row[$colName] == "" ) { echo '<td align=center>No Image</td>';}
      else { echo '<td>'.$row[$colName].'</td>'; }
    }
    echo '</tr>';
  }
  echo '</tbody></table>';
?>

</body>
</html>
