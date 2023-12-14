<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
else{
    include 'functions.php';
    $urlData = $_GET;
    $username = $_SESSION["username"];
    $records_per_page = 100;
    $baseQuery = getQuery('pim',$records_per_page);
    $result = getResult($baseQuery , $records_per_page);
    $total_pages = getTotalPages($baseQuery , $records_per_page);
    $usercol = getValue('users', 'username', $username, 'columns');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <script src="./js/pimjs.js" ></script>
    <script src="./js/filter.js" ></script>
    <title>Homepage</title>
</head>
<body>
    <header>
        <div class="welcome_info">Welcome, <?php echo $_SESSION["username"]; ?>!</div>
        <div class="header_nav">
            <a href="logout.php" >Logout</a>
            <a href="import.php" >Import Products</a>
            <a href="update.php" >Update Products</a>
            <a href="profile.php" >User Profile</a>
            
        </div>
    </header>
    <div id="app">
<div class="filter-functions">
<a class="show-filter" @click="showhidecols()">Column Filter</a>
<a class="show-filter" @click="showhiderows()">Row Filter</a>
<?php
// Loop through the URL parameters and display the data

  $row=mysqli_fetch_assoc($result);
  echo '<div class="row"><div class="showrows col-md-6" v-show="show_row_filter"><div class="rowscontainer">
  <rowfilter v-for="(filter, index) in filters" :key="index" @remove-filter="removeFilter()" :dataindex="index" @findindex="updateindex(index)"  @title-changed="updatetitle"  @type-changed="updatetype" @value-changed="updatevalue" @from-changed="updatefrom"  @to-changed="updateto" ></rowfilter>
  </div><div class="filter-btn-container"> <a class="btn add-condition" @click="addFilter()">Add Condition</a><a class="btn filter" @click="applyFilters" >Filter</a><a class="btn filter" href="/pim/" >Clear All Filters</a></div></div>';
  
  echo '<div class="showcols colscontainer col-md-6" v-show="show_col_filter">';
  foreach ($row as $colName => $val) { 
    $escapedColName = htmlspecialchars($colName, ENT_QUOTES, 'UTF-8');
    echo '<a class="btn colfilter" @click="toggleColumn(\'' . $escapedColName . '\')" :class="{ active: !activeColumns.includes(\'' . $escapedColName . '\') }">'.mb_convert_case(str_replace("_"," ",$colName), MB_CASE_TITLE).'</a>'; 
  } // show column headers
  echo '</div></div></div>';

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
var usercol = [<?php echo $usercol; ?>];
const callmyapp = myapp.mount('#app');
</script>
    
</body>
</html>
