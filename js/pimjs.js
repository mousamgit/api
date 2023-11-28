const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: ["sku","product_title","brand","type","colour","clarity","carat","shape","measurement","wholesale_aud","stone_price_retail_aud","image1"],
            filterurl: '/',
            filters: []
        }
    },
    computed: {
        filterUrl() {
            // Construct the filter URL based on applied filters
            // You may need to adjust this logic based on your specific requirements
            const filterParams = this.filters.map(filter => {
                return `${filter.selectedFilterColumn}=${filter.filterValue}`;
            });

            // Combine filter parameters into a URL
            const filterUrl = '/pim/index.php?' + filterParams.join('&');
            return filterUrl;
        },
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

        },
        removeFilter(index) {
            // Remove the filter at the specified index from the array
            this.filters.splice(index, 1);
        },
        applyFilters() {
            console.log(filterUrl);
        }
    }
});
