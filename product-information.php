<section id="content2">
    <p class="product-content">
      <div class='table-column'>
        <table class='producttable'>
          <?php 
            $col = mysqli_num_fields($result);
            $col = floor($col/2);
            $i = 0;
            foreach ($row as $colName => $val) { 
              echo "<tr><td class='table-subtitle'><strong>".$colName."</strong></td></tr>";
              if ($row[$colName] != "")
              {
                echo "<tr><td class='table-content'>".$row[$colName]."</td></tr>"; 
              }
              else {
                echo "<tr><td class='table-content empty'></td></tr>";
              }
              
              $i++;
              if ($i == $col)
              {
                echo "</table></div>";
                echo "<div class='table-column'><table class='producttable'>";
              }
            } 
          ?>
        </table>
      </div> 
    </p>
</section>