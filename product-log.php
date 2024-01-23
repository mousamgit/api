<section id="content4">
    <p class="product-content">
        <?php 
            $logquery = " SELECT * from pimlog WHERE SKU = '".$sku."'";
            $logresult = mysqli_query($con, $logquery) or die(mysqli_error($con));
            
        ?>
        <table id="myTable" class="data-table">
            <thead>
            <tr>
                <td>Date</td>
                <td>Time</td>
                <td>Field</td>
                <td>Old Record</td>
                <td>New Record</td>
                <td>User</td>
            </tr>
            </thead>
            <tbody>
                <?php 
                    while ($logrow = mysqli_fetch_assoc($logresult)){
                        echo "<tr>";
                        echo "<td>".$logrow[date]."</td>";
                        echo "<td>".$logrow[time]."</td>";
                        echo "<td>".$logrow[field]."</td>";
                        echo "<td>".$logrow[oldrecord]."</td>";
                        echo "<td>".$logrow[newrecord]."</td>";
                        echo "<td>".$logrow[user]."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </p>
</section>

<script>
    $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>