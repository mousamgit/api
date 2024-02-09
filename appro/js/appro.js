const myapp = Vue.createApp({
    data() {
        return {

            items: [],  
            itemarray: [],
            itemindex: 0,
            itemtotal:0,
            itemcode:'',
            itemprice:'',
            itemquantity:'',
        };
    },
    methods: {

        additem() {
            // Create a new app instance for the rowfilter component
            const itemApp = Vue.createApp({});
            // Mount the rowitem component and push it to the items array
            this.items.push(itemApp.component('approitem'));
            // this.itemarray.push({ itemcode: this.itemcode, itemprice: this.itemprice, itemquantity: this.itemquantity });
            // console.log('item:'+ JSON.stringify(this.itemarray));
        
            itemApp.mount(); // Mount the component (this is required to create a new instance)

            this.itemtotal ++;
        },
        // updateindex(index){            
        //     this.filterindex = index;
        // },
        // updatespec(value) {
        //     this.itemcode = value;
        // },
        // updateprice(value) {
        //     this.itemprice = value;
        // },
        // updatequantity(value){
        //     this.itemquantity = value;
        // },
    }
});

