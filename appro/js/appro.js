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
        updateindex(value){
            this.itemindex  = value;
            },
        updateqty(value){
            this.itemarray[this.itemindex].itemquantity = value;
            // console.log('updateqty'+this.itemarray[this.itemindex].itemquantity+'index'+this.itemindex);
            // console.log('updateitem:'+ JSON.stringify(this.itemarray));
        },
        updateprice(value){
            this.itemarray[this.itemindex].itemprice = value;
        },

    },
    computed: {
        totalQuantity() {
            return this.itemarray.reduce((acc, item) => acc + parseInt(item.itemquantity), 0);
        },
        totalPrice() {
            return this.itemarray.reduce((acc, item) => acc + parseInt(item.itemprice), 0);
        },     
      },


});

