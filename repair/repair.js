const myapp = Vue.createApp({
    data() {
        return {
            // itemcode:'',
            // itemprice:'',
            // itemquantity:'',
            // searchQuery: '',
            // searchSku: [],
            // itemdetail: false,
            // price:0,
            // productname:'',
            // inputQuantity:0,
            // discount:0,
            // totalPrice:0,
        };
    },
    methods: {
        // searchItems() {
        //     axios.get('https://pim.samsgroup.info/autofill/resultlist.php', { params: { 
        //         query: this.searchQuery,
        //         col1: this.col1,
        //         col2: this.col2,
        //         db: this.db,
        //         } 
        //     })
        //         .then(response => {
        //             this.items = response.data;
        //             console.log(this.items);
        //         })
        //         .catch(error => {
        //             console.error('Error searching items:', error);
        //         });
        // },
        // selectItem(item) {
        //     this.searchQuery = item.sku;
        //     this.searchSku = []; // Clear search results
        //     this.itemdetail = true;
        //     this.price = item.wholesale_aud;
        //     this.productname = item.product_title;
        // },

    },

});

