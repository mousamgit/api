<?php
  require_once ('connect.php');
  $query = ' SELECT * from `pim`';
  $result = mysqli_query($con, $query) or die(mysqli_error($con));

  // Assuming $result is your SQL query result
  $records_per_page = 100;
  $total_rows = mysqli_num_rows($result);
  $total_pages = ceil($total_rows / $records_per_page);

  // Get the current page or set it to 1 if not set
  $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

  $offset = ($current_page - 1) * $records_per_page;
  $sql = "SELECT * FROM pim LIMIT $offset, $records_per_page";
  $result = mysqli_query($con, $sql);
?>

<html>
  <head>
    <title> SGA PIM </title>
   
    <?php include 'header.php'; ?>
    

  </head>

  <body>
<div id="app">
<div style="width:100%; border:1px solid #000; margin:10px; padding:20px;">
  <div style="display:inline-block; width:10%;"><a href="import.php">[ Import Products ]</a></div>
  <div style="display:inline-block; width:10%;"><a href="update.php">[ Update Products ]</a></div>
</div>

<?php
  $row=mysqli_fetch_assoc($result);
  echo '<div class="showcols" value="'.$offset.'"><h2>columns you want to show</h2><div class="colscontainer">';
  foreach ($row as $colName => $val) { 
    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
    echo '<a class="btn colfilter" @click="toggleColumn(\'' . $escapedColName . '\')" :class="{ active: activeColumns.includes(\'' . $escapedColName . '\') }">'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</a>'; 
  } // show column headers
  echo '</div></div>';
  echo '<table id=myTable class=display><thead><tr>';
 
  foreach ($row as $colName => $val) { 
    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
    echo '<th :class="{ hidden: activeColumns.includes(\'' . $escapedColName . '\') }">'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</th>'; 
  } // show column headers
  echo '</tr></thead><tbody>';
  mysqli_data_seek($result,0); //reset counter to 0
  while($row = mysqli_fetch_assoc($result)){
    echo '<tr>';
    foreach ($row as $colName => $val ) {
      $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
      if( $colName == "sku" ){ echo '<td :class="{ hidden: activeColumns.includes(\'' . $escapedColName . '\') }"><a href="/pim/product.php?sku='.$row[$colName].'">'.$row[$colName].'</a></td>';}
      elseif (strpos($colName, "image") !==  false  && $row[$colName] != "" ){ echo '<td  :class="{ hidden: activeColumns.includes(\'' . $escapedColName . '\') }"><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif (strpos($colName, "image") !==  false  && $row[$colName] == "" ){ echo '<td  :class="{ hidden: activeColumns.includes(\'' . $escapedColName . '\') }" align=center>No Image</td>';}
      else { echo '<td :class="{ hidden: activeColumns.includes(\'' . $escapedColName . '\') }">'.$row[$colName].'</td>'; }
    }
    echo '</tr>';
  }
  echo '</tbody></table>';

  // Pagination links
echo '<div class="pagination">';
for ($page = 1; $page <= $total_pages; $page++) {
    echo '<a href="?page=' . $page . '">' . $page . '</a>';
}
echo '</div>';
?>
</div>

<script>
const callmyapp = myapp.mount('#app');
</script>
</body>
</html>
