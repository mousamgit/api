<?php
include 'functions.php';
$urlData = $_GET;
$records_per_page = 100;
$baseQuery = getQuery('pim',$records_per_page);
$result = getResult($baseQuery , $records_per_page);
$total_pages = getTotalPages($baseQuery , $records_per_page);
?>

<html>
  <head>
    <title> SGA PIM </title>
   
    <?php include 'header.php'; ?>
    
<script src="./js/pimjs.js" ></script>
<script src="./js/filter.js" ></script>
  </head>

  <body>
<div id="app">
<div style="width:100%; border:1px solid #000; margin:10px; padding:20px;">
  <div style="display:inline-block; width:10%;"><a href="import.php">[ Import Products ]</a></div>
  <div style="display:inline-block; width:10%;"><a href="update.php">[ Update Products ]</a></div>
</div>

<?php


// Loop through the URL parameters and display the data

  $row=mysqli_fetch_assoc($result);
  echo '<div class="showcols" ><h2>Column Filter</h2><div class="colscontainer">';
  foreach ($row as $colName => $val) { 
    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
    echo '<a class="btn colfilter" @click="toggleColumn(\'' . $escapedColName . '\')" :class="{ active: !activeColumns.includes(\'' . $escapedColName . '\') }">'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</a>'; 
  } // show column headers
  echo '</div></div>';
  echo '<div class="showrows" ><h2>Row Filter</h2><div class="rowscontainer">
  <rowfilter v-for="(filter, index) in filters" :key="index" @remove-filter="removeFilter()" :dataindex="index" @findindex="updateindex(index)"  @title-changed="updatetitle"  @type-changed="updatetype" @value-changed="updatevalue" @from-changed="updatefrom"  @to-changed="updateto" ></rowfilter>
  </div>
  <div class="filter-btn-container"> <a class="btn add-condition" @click="addFilter()">Add Condition</a><a class="btn filter" @click="applyFilters" >Filter</a><a class="btn filter" href="/pim/" >Clear All filters</a></div>
  </div>';


  echo '<table id=myTable class=display><thead><tr>';
 
  foreach ($row as $colName => $val) { 
    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
    echo '<th :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }">'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</th>'; 
  } // show column headers
  echo '</tr></thead><tbody>';
  mysqli_data_seek($result,0); //reset counter to 0
  while($row = mysqli_fetch_assoc($result)){
    echo '<tr>';
    foreach ($row as $colName => $val ) {
      $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
      if( $colName == "sku" ){ echo '<td col="'.$colName.'" :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }"><a href="/pim/product.php?sku='.$row[$colName].'">'.$row[$colName].'</a></td>';}
      elseif (strpos($colName, "image") !==  false  && $row[$colName] != "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }"><a href="'.$row[$colName].'" target=_blank><image src="'.$row[$colName].'" width=150px></a></td>';}
      elseif (strpos($colName, "image") !==  false  && $row[$colName] == "" ){ echo '<td class="img-cell" col="'.$colName.'"  :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }" align=center>No Image</td>';}
      else { echo '<td  col="'.$colName.'" :class="{ hidden: !activeColumns.includes(\'' . $escapedColName . '\') }">'.$row[$colName].'</td>'; }
    }
    echo '</tr>';
  }
  echo '</tbody></table>';

  // Pagination links
echo '<div class="pagination">';
// if(strpos($_SERVER['REQUEST_URI'], "?") !==  false){
//     $pageurl = $_SERVER['REQUEST_URI'] . 'page=';
// }
// else{
//   $pageurl = '?page=';
// }

for ($page = 1; $page <= $total_pages; $page++) {
  $urlData ['page'] = $page;
  $pageurl =  '?' . http_build_query($urlData);
    echo '<a href="' . $pageurl . '">' . $page . '</a>';
}
echo '</div>';
?>
</div>

<script>
const callmyapp = myapp.mount('#app');
</script>
</body>
</html>