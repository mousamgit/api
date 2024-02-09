myapp.component('approitem', {
    props: {
        dataindex: {
            type: Number,
          },
      },
    data() {
        return {
            itemcode:'',
            itemprice:'',
            itemquantity:'',
        };
    },
    mounted() {
        this.$emit('item-added', { itemcode: this.itemcode, itemprice: this.itemprice, itemquantity: this.itemquantity });
    },



    template: /*html*/ `
  

        <div class="item-row" :key="dataindex">
        <label>Item Code:</label>
        <input type="text" name="items[][itemcode]" required>
        <label>Price:</label>
        <input type="text" name="items[][itemprice]" required>
        <label>Quantity:</label>
        <input type="text" name="items[][itemquantity]" required>
        </div>

    `,
    methods: {

        // updatespec(){
        //     this.$emit('spec-changed', this.itemcode);
        //     this.$emit('findindex', this);
        // },
        // updateprice(){
        //     this.$emit('price-changed', this.itemprice);
        //     this.$emit('findindex', this);
        // },
        // updatequantity(){
        //     this.$emit('quantity-changed', this.itemquantity);
        //     this.$emit('findindex', this);
        // },

        removeItem() {
            // You can implement logic to remove the filter component

            // this.$emit('findindex', this);
            // this.$emit('remove-filter');
            // console.log('index',this.dataindex);
        }
    }
});