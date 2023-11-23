const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: ["sku","product_title","brand","type","wholesale_aud","stone_price_retail_aud","image1"]
        }
    },
    methods: {
        toggleColumn(colName) {
            const index = this.activeColumns.indexOf(colName);
            if (index !== -1) {
                this.activeColumns.splice(index, 1);
            } else {
                this.activeColumns.push(colName);
            }
            console.log(this.activeColumns);
        }
    }
});
// $(document).ready( function () { 
//     // $('#myTable').DataTable();
//     $('.colfilter').click(function(){
//         if($(this).hasClass('active')){$(this).removeClass('active')}
//         else{$(this).addClass('active')}
//     });
// } );