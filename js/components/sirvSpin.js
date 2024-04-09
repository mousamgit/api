
myapp.component('sirvspin', {
    props: {
        sku: {
            type: String,
          },
          brand:{
            type: String,
          },
          multispins:{
            type: Boolean,
          }
    },
    data() {
        return {
            urlExists: true, // Assume URL exists initially
        };
    },
    mounted() {
        this.checkUrlExists(); // Call the method when the component is mounted
        const script = document.createElement('script');
        script.src = 'https://scripts.sirv.com/sirv.js';
        script.async = true;
        document.body.appendChild(script);
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
    <div v-if="urlExists" class="sirv360" :multi="multispins" :url="urlExists">
        <iframe  v-if="multispins" :src="sirvurl"  width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
        <div  v-else class="sirv-container">
            <div class="Sirv" id="sirv-spin" :data-src="sirvurl"></div>
            <div class="sirv-controls ">
            <a onclick="Sirv.instance('sirv-spin').play(-1); return false;" href="#" class="button flaticon-keyboard54" title="Left"></a>
            <a id="pause-button-sirv-spin" onclick="Sirv.instance('sirv-spin').pause(); return false;" href="#" class="button flaticon-pause44" title="Pause"></a>
            <a id="play-button-sirv-spin" onclick="Sirv.instance('sirv-spin').play(); return false;" href="#" class="button flaticon-play106" title="Play"></a>
            <a onclick="Sirv.instance('sirv-spin').play(1); return false;" href="#" class="button flaticon-keyboard53" title="Right"></a>
            <a onclick="Sirv.instance('sirv-spin').zoomIn(); return false;" href="#" class="button flaticon-round57" title="Zoom In"></a>
            <a onclick="Sirv.instance('sirv-spin').zoomOut(); return false;" href="#" class="button flaticon-round56" title="Zoom Out"></a>
            <a onclick="Sirv.instance('sirv-spin').fullscreen('sirv-spin'); return false;" href="#" class="button flaticon-move26" title="Full Screen"></a>
            <div class="clear"></div>
            </div>
            <link rel="stylesheet" href="https://demo.sirv.com/sirv-controls/sirv-controls.css">

        </div>     
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