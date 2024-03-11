
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
            searchQuery: '',
            searchSku: [],
            itemdetail: false,
            price:0,
            productname:'',
            inputQuantity:0,
            discount:0,
            totalPrice:0,
        };
    },
    mounted() {
        this.$emit('item-added', { itemcode: this.itemcode, itemprice: this.itemprice, itemquantity: this.itemquantity });
    },



    template: /*html*/ `
  
    <div class="row item-row"  :key="dataindex">
        <div class="cell inputcell">
            <input type="text" v-model="searchQuery" @input="searchItems"  name="items[][itemcode]"  autocomplete="off"  >
            <div class="autofill">
            <ul v-if="searchSku.length > 0">
                <li v-for="result in searchSku" :key="result.id" @click="selectItem(result)">
                    {{ result.sku }}
                </li>
            </ul>
            </div>
        </div>
        <div class="cell">{{ productname }}</div>
        <div class="cell inputcell"><input  v-if="this.itemdetail" type="text" name="items[][itemprice]" v-model="this.price"  @input="calculateTotal"></div>
        <div class="cell inputcell"><input  v-if="this.itemdetail" type="text" name="items[][discount]" v-model="this.discount"  @input="calculateTotal"></div>
        <div class="cell inputcell"><input  v-if="this.itemdetail" type="text" name="items[][itemquantity]" v-model="this.inputQuantity" @input="calculateTotal"></div>
        <div class="cell inputcell"><input  v-if="this.itemdetail" type="text" name="items[][itemtotal]" :value="this.totalPrice" readonly></div>
    </div>

    `,
    methods: {

        searchItems() {
            axios.get('../searchsku.php', { params: { query: this.searchQuery } })
                .then(response => {
                    this.searchSku = response.data;
                    console.log(this.searchSku);
                })
                .catch(error => {
                    console.error('Error searching items:', error);
                });
        },
        selectItem(item) {
            this.searchQuery = item.sku;
            this.searchSku = []; // Clear search results
            this.itemdetail = true;
            this.price = item.wholesale_aud;
            this.productname = item.product_title;
        },

        removeItem() {
            // You can implement logic to remove the filter component

            // this.$emit('findindex', this);
            // this.$emit('remove-filter');
            // console.log('index',this.dataindex);
        },
        calculateTotal: function() {
            // Convert price and quantity to numbers
            var inputprice = parseFloat(this.price);
            var quantity = parseFloat(this.inputQuantity);
            var discount = parseFloat(this.discount);

            
            // Check if both price and quantity are valid numbers
            if (!isNaN(inputprice) && !isNaN(quantity)) {
                // Calculate total price
                this.totalPrice = inputprice * quantity * (1-discount/100);
            } else {
                this.totalPrice = 0; // Reset total price if inputs are not valid numbers
            }
            this.$emit('update-index', this);
            this.$emit('update-qty', quantity);
            this.$emit('update-price', this.totalPrice);
            

        }
    }
});