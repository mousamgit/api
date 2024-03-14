
myapp.component('autofill', {
    props: {
        col1: {
            type: String,
            required: true
          },
        col2:{
            type: String
        },
        db:{
            type: String,
            required: true
        },
        inputname:{
            type: String
        },  
      },
    data() {
        return {
            searchQuery: '',
            items: [],
            selectedValue: ''
        };
    },



    template: /*html*/ `

    <input type="text" v-model="searchQuery" @input="searchItems"  name="inputname" autocomplete="off"  >
    <div class="autofill">
    <ul v-if="items.length > 0">
        <li v-for="item in items" :key="item.id" @click="selectItem(item)">
            {{ item.value }}
        </li>
    </ul>
    </div>

    `,
    methods: {

        searchItems() {
            axios.get('./resultlist.php', { params: { 
                query: this.searchQuery,
                col1: col1,
                col2: col2,
                db: db,
                } 
            })
                .then(response => {
                    this.searchSku = response.data;
                    console.log(this.searchSku);
                })
                .catch(error => {
                    console.error('Error searching items:', error);
                });
        },
        selectItem(item){
            this.selectedValue = item.val;
            this.items = []; // Clear search results
        },
        
    }
});