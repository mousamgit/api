import ChannelDetails from './components/channel/ChannelDetails.js';
import ProductFilter from "./components/Filters/ProductFilter.js";

const app = Vue.createApp({
    data() {
        return {
            channels: [], // Initialize channels as an empty array
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        this.fetchAttributes();

    },
    methods: {
        async fetchAttributes() {
            try {
                const response = await fetch('attribute_data_fetch.php');
                const data = await response.json();
                this.attributes = data;
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
    },
});

app.component('channel-details', ChannelDetails);
app.component('product-filter', ProductFilter);

app.mount('#app');
