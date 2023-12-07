const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: ["sku","product_title","brand","type","colour","clarity","carat","shape","measurement","wholesale_aud","stone_price_retail_aud","image1"],
            filters: [],     // Add an array to store filters
            filterarray: [],
            filterindex: 0,
            filtertitle: '',
            filtervalue: '',
            filtertype: '',
            filterfrom:0,
            filterto:99999999,
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

            // Update filterarray with an empty filter at the correct index
            const newIndex = this.filters.length - 1; // Get the last index
            if (!this.filterarray[newIndex]) {
                this.filterarray[newIndex] = ['', ''];
            }
            
            // Use $nextTick to ensure DOM has been updated
            this.$nextTick(() => {
                // Update the filterindex to the new index
                this.filterindex = newIndex;
            });
            
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
        updatetitle(value) {
            this.filtertitle = value;
        },
        updatetype(value) {
            this.filtertype = value;
        },
        updatevalue(value){
            this.filtervalue = value;
        },
        updatefrom(value){
            this.filterfrom = value;
        },
        updateto(value){
            this.filterto = value;
        },
        removeFilter() {
            // Remove the filter at the specified index from the array
           
            // this.filters.forEach((filter, index) =>{
            //     console.log(index, filter);
            // });
            // this.filters.splice(this.filterindex, 1);
                // Find the index of the filter in filters array
            const indexToRemove = this.filters.indexOf(this.filters[this.filterindex]);
            console.log('indexToRemove',indexToRemove);
            if (indexToRemove !== -1) {
                this.filters.splice(indexToRemove, 1);
            }

        },
        applyFilters() {
            // Log the filter data for now, you can use it as needed
            var pimurl ='?';
            console.log('Filter Changed:', this.filterarray);
            this.filterarray.forEach((filter, index) => {
                const [title, value,type, from, to] = filter;
                if(type == 'equals'){
                    pimurl += title +'='+value +'&';
                }
                if(type == 'range'){
                    pimurl += title +'<'+from +'&'+title +'>'+ to +'&';
                }

                
                // You can perform additional actions with title and value as needed
            });
            window.location.replace(pimurl);
        },
    },
    watch: {
        filtertitle() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatetitle', this.filtertitle, this.filterindex);
            this.filterarray[this.filterindex][0] = this.filtertitle;
        },
        filtervalue() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][1] = this.filtervalue;
        },
        filtertype() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][2] = this.filtertype;
        },
        filterfrom() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][3] = this.filterfrom;
        },
        filterto() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][4] = this.filterto;
        },
    },
});
