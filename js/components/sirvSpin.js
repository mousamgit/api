
myapp.component('sirvspin', {
    props: {
        sku: {
            type: String,
          },
          brand:{
            type: String,
          }
    },
    data() {
        return {
            urlExists: true, // Assume URL exists initially
        };
    },
    mounted() {
        this.checkUrlExists(); // Call the method when the component is mounted
    },  
    computed: {
        sirvurl(){
            if(this.brand.toLowerCase()  == 'pink kimberley' || this.brand.toLowerCase()  == 'pink kimberley diamonds' || this.brand.toLowerCase()  == 'argyle pink diamonds' || this.brand.toLowerCase()  == 'blush pink diamonds' ){
                return 'https://samsgroup.sirv.com/products/' + this.sku + '/' + this.sku + '.spin';
            }
            if(this.brand.toLowerCase()  == 'sapphire dreams' || this.brand.toLowerCase()  == 'loose sapphires'){
                return 'https://samsgroup.sirv.com/SD-Product/Sapphire%20Dreams%20Products/' + this.sku + '/' + this.sku + '.spin';
            }
        }
    },
    template: /*html*/ ` 
    <div v-if="urlExists" class="sirv360" :url="sirvurl">
        <iframe  :src="sirvurl"  width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
    </div>
    <div v-else class="no-spin-container">
    <img src="../../css/noimg.jpg" alt="Image Not Found" @error="handleImgError">
    </div>
    `,
    methods: {
        checkUrlExists() {
            axios.head(this.sirvurl) // Send a HEAD request to check URL existence
                .then(() => {
                    // URL exists, set urlExists to true
                    this.urlExists = true;
                })
                .catch(() => {
                    // URL does not exist, set urlExists to false
                    this.urlExists = false;
                });
        },
    }
});