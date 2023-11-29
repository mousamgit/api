import { createApp } from 'vue';
import AddChannel from './Components/Channel/AddChannel.vue';
import ChannelsList from './Components/Channel/ChannelsList.vue';

const app = createApp({
    data() {
        return {
            channels: null, // Initialize channels as null
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        this.fetchChannels();
    },
    methods: {
        async fetchChannels() {
            try {
                // Make an AJAX request to your PHP file
                const response = await fetch('channel_data_fetch.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the channels data
                this.channels = data;
            } catch (error) {
                console.error('Error fetching channels:', error);
            }
        },
    },
});

app.component('add-channel-modal', AddChannel);
app.component('channels-list', ChannelsList);

app.mount('#app');
