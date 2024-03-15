const myapp = Vue.createApp({
    data() {
        return {
            custcode:'',
            custname:'',
            contact:'',
            address:'',
            searchQuery: '',
            searchCode: [],
            itemdetail: false,

        };
    },
    methods: {
        searchItems() {
            axios.get('https://pim.samsgroup.info/autofill/resultlist.php', { params: { 
                query: this.searchQuery,
                col1: 'code',
                col2: 'company',
                cola: 'contact',
                colb: 'email',
                colc: 'address1',
                cold: 'city',
                cole: 'state',
                } 
            })
                .then(response => {
                    this.items = response.data;
                    console.log(this.items);
                })
                .catch(error => {
                    console.error('Error searching items:', error);
                });
        },
        selectItem(item) {
            this.searchQuery = item.sku;
            this.searchCode = []; // Clear search results
            this.price = item.wholesale_aud;
            this.productname = item.product_title;
        },

    },

});

