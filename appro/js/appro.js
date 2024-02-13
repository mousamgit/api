const myapp = Vue.createApp({
    data() {
        return {

            items: [],  
            itemarray: [],
            itemindex: 0,
            itemtotal:0,
            itemcode:'',
            itemprice:0,
            itemquantity:0,
            itemindex:'',
            totalQuantity: 0,
            totalPrice: 0,
        };
    },
    methods: {

        additem() {
            // Create a new app instance for the rowfilter component
            const itemApp = Vue.createApp({});
            // Mount the rowitem component and push it to the items array
            this.items.push(itemApp.component('approitem'));
            this.itemarray.push({ itemcode: this.itemcode, itemprice: this.itemprice, itemquantity: this.itemquantity });
            // console.log('item:'+ JSON.stringify(this.itemarray));
        
            itemApp.mount(); // Mount the component (this is required to create a new instance)

            this.itemtotal ++;
        },
        updateqty(value){
            this.itemquantity = value;
        },
        updateqty(value){
            this.itemquantity = value;
        },
        updateqty(value){
            this.itemquantity = value;
        },

    },
    // watch:{
    //     itemprice(){
    //         this.filterarray.itemprice = this.itemprice;
    //     },
    //     itemquantity(){
           
    //         this.filterarray.itemquantity = this.itemquantity;
    //         console.log('updateqty', this.filterarray.itemquantity);
    //     },
    // },

});

