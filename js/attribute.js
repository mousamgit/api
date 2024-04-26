// channel.js
import AttributeList from './components/channel/AttributeList.js';
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
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch('attribute_data_fetch.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the attributes data
                this.attributes = data;
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
        // handleChannelCreated(newChannel) {
        //     // Implement logic to add the new channel to your data source
        //     // For example, you can push it to the channels array
        //     this.channels.push(newChannel);
        // },
    },
});

app.component('attribute-list', AttributeList);
app.component('product-filter', ProductFilter);

app.mount('#app');
