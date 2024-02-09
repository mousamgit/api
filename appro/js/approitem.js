
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
            searchResults: [],
        };
    },
    mounted() {
        this.$emit('item-added', { itemcode: this.itemcode, itemprice: this.itemprice, itemquantity: this.itemquantity });
    },



    template: /*html*/ `
  

        <div class="item-row" :key="dataindex">
        <input type="text" v-model="searchQuery" @input="searchItems" required>
        <div class="autofill">
            <ul v-if="searchResults.length > 0">
                <li v-for="result in searchResults" :key="result.id" @click="selectItem(result)">
                    {{ result.sku }}
                </li>
            </ul>
        </div>
        <label>Item Code:</label>
        <input type="text" name="items[][itemcode]" required>
        <label>Price:</label>
        <input type="text" name="items[][itemprice]" required>
        <label>Quantity:</label>
        <input type="text" name="items[][itemquantity]" required>
        </div>

    `,
    methods: {

        searchItems() {
            axios.get('../searchsku.php', { params: { query: this.searchQuery } })
                .then(response => {
                    this.searchResults = response.data;
                })
                .catch(error => {
                    console.error('Error searching items:', error);
                });
        },
        selectItem(item) {
            this.searchQuery = item.sku;
            this.searchResults = []; // Clear search results
        },

        removeItem() {
            // You can implement logic to remove the filter component

            // this.$emit('findindex', this);
            // this.$emit('remove-filter');
            // console.log('index',this.dataindex);
        }
    }
});