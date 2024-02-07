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

    },


    template: /*html*/ `
  

        <div class="item-row" :key="dataindex">
            <label>Item Code:</label>
            <input type="text" v-model="itemcode" required>

            <label>Price:</label>
            <input type="text" v-model="itemprice" required>

            <label>Quantity:</label>
            <input type="text" v-model="itemquantity" required>
        </div>

    `,
    methods: {

        updatespec(){
            // this.$emit('title-changed', this.filterTitle);
            // this.$emit('findindex', this);
        },
        updateprice(){
            // this.$emit('type-changed', this.filterType);
            // this.$emit('findindex', this);
        },
        updatequantity(){
            // this.$emit('from-changed', this.filterFrom);
            // this.$emit('findindex', this);
        },

        removeItem() {
            // You can implement logic to remove the filter component

            // this.$emit('findindex', this);
            // this.$emit('remove-filter');
            // console.log('index',this.dataindex);
        }
    }
});