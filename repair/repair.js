const myapp = Vue.createApp({
    data() {
        return {
            custcode:'',
            custname:'',
            contact:'',
            address:'',
            email:'',
            searchQuery: '',
            items: [],
            itemdetail: false,

        };
    },
    methods: {
        searchItems() {
            console.log(this.searchQuery);
            axios.get('https://pim.samsgroup.info/autofill/resultlist.php', { params: { 
                query: this.searchQuery,
                db:'customer',
                col1: 'code',
                col2: 'company',
                cola: 'contact',
                colb: 'email',
                colc: 'address_1',
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
            this.searchQuery = item.val;
            this.items = []; // Clear search results
            this.contact = item.contact;
            this.email = item.email;
            this.address = item.address_1 + ' ' + item.city + ' ' + item.state;
        },

    },

});

