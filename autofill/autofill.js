
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
        placeholder:{
            type: String
        },  
        req:{
            type: Boolean
        }, 
      },
    data() {
        return {
            searchQuery: '',
            items: [],
        };
    },



    template: /*html*/ `

    <input type="text" v-model="searchQuery" @input="searchItems" :placeholder="placeholder" :name="inputname" autocomplete="off" :required="req ? 'required' : null" >
    <div class="autofill">
    <ul v-if="items.length > 0">
        <li v-for="item in items" @click="selectItem(item)">
            {{ item.val }}
        </li>
    </ul>
    </div>

    `,
    methods: {

        searchItems() {
            axios.get('https://pim.samsgroup.info/autofill/resultlist.php', { params: { 
                query: this.searchQuery,
                col1: this.col1,
                col2: this.col2,
                db: this.db,
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
        selectItem(item){
            this.searchQuery = item.val;
            this.items = []; // Clear search results
        },
        
    }
});
