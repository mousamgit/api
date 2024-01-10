const myapp = Vue.createApp({
    data(){
        return{
            activeColumns: usercol,
            filters: [],     // Add an array to store filters
            filterarray: [],
            filterindex: 0,
            filtertitle: '',
            filtervalue: '',
            filtertype: '',
            filtercontains: '',
            filterfrom:0,
            filtertotal:-1,
            filterto:99999999,
            show_col_filter: false,
            show_row_filter: true,
        }
    },

    methods: {
        showhidecols() {
            this.show_col_filter = !this.show_col_filter;
        },
        showhiderows() {
            this.show_row_filter = !this.show_row_filter;
        },
        toggleColumn(colName) {
            const index = this.activeColumns.indexOf(colName);
            if (index !== -1) {
                this.activeColumns.splice(index, 1);
            } else {
                this.activeColumns.push(colName);
            }

        },
        addFilter() {
            // Create a new app instance for the rowfilter component
            const filterApp = Vue.createApp({});
            // Mount the rowfilter component and push it to the filters array
            this.filters.push(filterApp.component('rowfilter'));
            console.log(this.filters);
        
            filterApp.mount(); // Mount the component (this is required to create a new instance)
            if (!this.filterarray[this.filterindex]) {
                this.filterarray[this.filterindex] = ['', '', 'equals', '', '',''];
            } else {
                // If filterarray is already initialized, add a new empty filter
                this.filterarray.push(['', '', 'equals', '', '']);
            }
            this.filtertotal ++;
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
        updatecontains(value){
            this.filtercontains = value;
        },
        removeFilter() {
            // Remove the filter at the specified index from the array
           
            // this.filters.forEach((filter, index) =>{
            //     console.log(index, filter);
            // });
            // this.filters.splice(this.filterindex, 1);
                // Find the index of the filter in filters array



            this.filters.splice( this.filtertotal, 1);
            this.filterarray.splice( this.filtertotal, 1);
            this.filtertotal --;


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
            console.log('updatetitle', this.filtertitle, this.filterindex);
            this.filterarray[this.filterindex][0] = this.filtertitle;
        },
        filtervalue() {
            // Watch for changes in filterindex and call updatetitle
            console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][1] = this.filtervalue;
        },
        filtertype() {
            // Watch for changes in filterindex and call updatetitle
            console.log('filtertype', this.filtertype, this.filterindex);
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
        updatecontains() {
            // Watch for changes in filterindex and call updatetitle
            // console.log('updatevalue', this.filtervalue, this.filterindex);
            this.filterarray[this.filterindex][5] = this.updatecontains;
        },
    },
});
