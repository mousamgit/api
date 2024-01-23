<section id="content2">
    <p class="product-content">
      <div class='table-column'>
        <table class='product-table'>
          <?php 
            $col = mysqli_num_fields($result);
            $col = floor($col/2);
            $i = 0;
            foreach ($row as $colName => $val) { 
              echo "<tr><td class='l'><strong>".$colName."</strong></td><td>".$row[$colName]."</td></tr>"; 
              $i++;
              if ($i == $col)
              {
                echo "</table></div>";
                echo "<div class='table-column'><table class='product-table'>";
              }
            } 
          ?>
        </table>
      </div> 
    </p>
</section>