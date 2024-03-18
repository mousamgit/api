const myapp = Vue.createApp({
    data() {
        return {
            editingCell: [],
            items: [],  
            itemarray: [],
            itemindex: 0,
            itemtotal:0,
            itemcode:'',
            itemprice:0,
            itemquantity:0,

            totals: [],
            quantitytotals:[],
        };
    },
    methods: {
          calculateTotal(quantity, price,discount) {
            const qty = Number(quantity);
            const total = price * quantity*(1-discount/100);
            this.totals.push(total);
            this.quantitytotals.push(qty);
            return total;
          },
          calculateSum() {
            return this.totals.reduce((acc, cur) => acc + cur, 0);
          },
          calculateQty() {
            return this.quantitytotals.reduce((acc, cur) => acc + cur, 0);
          },

          editdata(row, col) {
            // Set the editingCell to the combination of colName and row
            this.editingCell = [row,col];
          },
        
          isediting(row, col) {
            if(this.editingCell[0] == row && this.editingCell[1] == col){                return true;            }
            else{                return false;            }
          },

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
            return this.itemarray.reduce((acc, item) => acc + parseFloat(item.itemprice), 0);
        },     
      },


});

