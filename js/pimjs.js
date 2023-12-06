const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: ["sku","product_title","brand","type","colour","clarity","carat","shape","measurement","wholesale_aud","stone_price_retail_aud","image1"],
            filters: [],     // Add an array to store filters
            filterarray: [],
            filterindex: 0,
            filtertitle: '',
            filtervalue: '',
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
            if (!this.filterarray[this.filterindex]) {
                this.filterarray[this.filterindex] = ['', ''];
            } else {
                // If filterarray is already initialized, add a new empty filter
                this.filterarray.push(['', '']);
            }

        },
        updateindex(index){            
            this.filterindex = index;
        },
        updatetitle(title) {
            this.filtertitle = title;
        },
        updatevalue(value){
            this.filtervalue = value;
        },
        removeFilter(index) {
            // Remove the filter at the specified index from the array
            console.log('remove',index);
            this.filters.splice(index, 1);

        },
        applyFilters() {
            // Log the filter data for now, you can use it as needed
            var pimurl ='?';
            console.log('Filter Changed:', this.filterarray);
            this.filterarray.forEach((filter, index) => {
                const [title, value] = filter;
                pimurl += title +'='+value +'&';

                
                // You can perform additional actions with title and value as needed
            });
            window.open(pimurl);
        },
    },
    watch: {
        filtertitle() {
            // Watch for changes in filterindex and call updatetitle
            console.log('updatetitle', this.filtertitle, this.filterindex);
            this.filterarray[this.filterindex][0] = this.filtertitle;
        },
        filtervalue() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][1] = this.filtervalue;
        },
    },
});
