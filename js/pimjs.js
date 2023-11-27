const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: ["sku","product_title","brand","type","colour","clarity","carat","shape","measurement","wholesale_aud","stone_price_retail_aud","image1"],
            filterurl: '/',
            filters: []
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
        },
        addFilter() {
            // Create a new app instance for the rowfilter component
            const filterApp = Vue.createApp({});
            // Mount the rowfilter component and push it to the filters array
            this.filters.push(filterApp.component('rowfilter'));
            filterApp.mount(); // Mount the component (this is required to create a new instance)
            
            console.log(filters);
        },
        removeFilter(index) {
            // Remove the filter at the specified index from the array
            this.filters.splice(index, 1);
        },
        applyFilters() {
            // Implement logic to apply filters
            // You can iterate over this.filters and access the selected columns and values
            // Use this information to construct the filter conditions for your SQL query
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