
myapp.component('certlink', {
    props: {
        sku: {
            type: String,
          }
    },
    data() {
        return {
            urlExists: true, // Assume URL exists initially
        };
    },
    mounted() {
        this.checkUrlExists(); 
    },  
    computed: {
        certurl(){return 'https://samsgroup.sirv.com/pdf/' + this.sku + '.pdf'; }
    },
    template: /*html*/ ` 
    <a v-if="urlExists" :href="certurl" target="_blank">Click Here to view PDF</a>
    <div v-else class="no-cert">No Cert</div>
    `,
    methods: {
        checkUrlExists() {
            axios.head(this.certurl) // Send a HEAD request to check URL existence
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