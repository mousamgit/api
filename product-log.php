<section id="content4">
    <p class="product-content">
        <table id="myTable" class="data-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Field</th>
                <th>Old Record</th>
                <th>New Record</th>
                <th>User</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    while ($logrow = mysqli_fetch_assoc($logresult)){
                        echo "<tr>";
                        echo "<td>".$logrow[date]."</td>";
                        echo "<td>".$logrow[time]."</td>";
                        echo "<td>".$logrow[field]."</td>";
                        if(strpos(strtolower($logrow[oldrecord]),".jpg") !== false) { echo "<td><img src='".$logrow[oldrecord]."' width=150px></td>"; } else { echo "<td>".$logrow[oldrecord]."</td>"; }
                        if(strpos(strtolower($logrow[newrecord]),".jpg") !== false) { echo "<td><img src='".$logrow[newrecord]."' width=150px></td>"; } else { echo "<td>".$logrow[newrecord]."</td>"; }
                        echo "<td>".$logrow[user]."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Field</th>
                <th>Old Record</th>
                <th>New Record</th>
                <th>User</th>
            </tr>
                </tfoot>
        </table>
    </p>
</section>

<script>
    new DataTable('#myTable', {
    initComplete: function () {
        this.api()
            .columns()
            .every(function () {
                let column = this;
 
                // Create select element
                let select = document.createElement('select');
                select.add(new Option(''));
                column.footer().replaceChildren(select);
 
                // Apply listener for user change in value
                select.addEventListener('change', function () {
                    var val = DataTable.util.escapeRegex(select.value);
 
                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
 
                // Add list of options
                column
                    .data()
                    .unique()
                    .sort()
                    .each(function (d, j) {
                        select.add(new Option(d));
                    });
            });
    }
});
</script>