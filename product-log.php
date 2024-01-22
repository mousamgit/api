<section id="content4">
    <p class="product-content">
        <?php 
            $logquery = " SELECT * from pimlog WHERE SKU = '".$sku."'";
            $logresult = mysqli_query($con, $logquery) or die(mysqli_error($con));
            $logrow = mysqli_fetch_assoc($logresult);
        ?>
        <table class="product-table">
            <tr>
                <td>Date</td>
                <td>Time</td>
                <td>Field</td>
                <td>Old Record</td>
                <td>New Record</td>
                <td>User</td>
            </tr>
                <?php foreach ($logrow as $colName => $val) { if ($colName != "sku"){ echo "<td>".$logrow[$colName]."</td>"; } } ?>
        </table>
    </p>
</section>