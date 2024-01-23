
<section id="content1">
    <p class="product-content">
      <div class="table-column">
        <table class="product-table">
          <tr><td class="title" colspan="1000">Main Information</td></tr>
          <tr><td class="l"> SKU: </td> <td><?php echo $sku; ?></td></tr>
          <tr><td class="l"> Product Title: </td> <td><?php echo $row[product_title]; ?></td></tr>
          <tr><td class="l"> Brand: </td> <td><?php echo $row[brand]; ?></td></tr>
          <tr><td class="l"> Type: </td> <td><?php echo $row[type]; ?></td></tr>
        </table>
        <table class="product-table">
          <tr><td class="title" colspan="1000">Quick Specs</td></tr>
          <tr><td class="l"> Specifications: </td> <td><?php echo $row[specifications]; ?></td></tr>
        </table>
        <table class="product-table">
          <tr><td class="title" colspan="1000">E-commerce</td></tr>
          <tr><td class="l"> Description: </td> <td><?php echo $row[description]; ?></td></tr>
          <tr><td class="l"> Tags: </td> <td><?php echo $row[tags]; ?></td></tr>
          <tr><td class="l"> Collections 2: </td> <td><?php echo $row[collections_2]; ?></td></tr>
        </table>
      </div>
      <div class="table-column">
        <table class="product-table">
          <tr><td class="title" colspan="1000">Pricing</td></tr>
          <?php if(strpos(strtolower($row[type]),"loose") !== false) { ?>
            <tr><td class="l"> Purchase Cost per Carat ($AUD): </td> <td>$ <?php echo number_format($row[purchase_cost_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> Wholesale Price per Carat ex GST ($AUD): </td> <td>$ <?php echo number_format($row[wholesale_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> <b>Wholesale Stone Price ex GST ($AUD):</b> </td> <td>$ <?php echo number_format($row[stone_price_wholesale_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> Retail Price per Carat ex GST ($AUD): </td> <td>$ <?php echo number_format($row[retail_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> <b>Retail Stone Price ex GST ($AUD):</b> </td> <td>$ <?php echo number_format($row[stone_price_retail_aud], 2, '.', ','); ?></td></tr>
          <?php } else { ?>
            <tr><td class="l"> Purchase Cost ($AUD): </td> <td>$ <?php echo number_format($row[purchase_cost_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> Manufacturing Cost: </td> <td>$ <?php echo number_format($row[manufacturing_cost_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> Wholesale Price ex GST ($AUD): </td> <td>$ <?php echo number_format($row[wholesale_aud], 2, '.', ','); ?></td></tr>
            <tr><td class="l"> Retail Price ex GST ($AUD): </td> <td>$ <?php echo number_format($row[retail_aud], 2, '.', ','); ?></td></tr>
          <?php } ?>
        </table>
        <table class="product-table">
          <tr><td class="title" colspan="1000">Quantity</td></tr>
          <tr><td class="l"> Master: </td> <td><?php echo $row[master_qty]; ?></td></tr>
          <tr><td class="l"> Warehouse: </td> <td><?php echo $row[warehouse_qty]; ?></td></tr>
          <tr><td class="l"> Mark Dimmock: </td> <td><?php echo $row[mdqty]; ?></td></tr>
          <tr><td class="l"> Peter Seskin: </td> <td><?php echo $row[psqty]; ?></td></tr>
          <tr><td class="l"> USD Warehouse: </td> <td><?php echo $row[usdqty]; ?></td></tr>
          <tr><td class="l"> Allocated: </td> <td><?php echo $row[allocated_qty]; ?></td></tr>
        </table>
      </div>
    </p>
</section>