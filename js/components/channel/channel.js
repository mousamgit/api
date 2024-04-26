// channel.js
import AddChannel from './AddChannel.js';
import ChannelList from './ChannelList.js';

const app = Vue.createApp({
    data() {
        return {
            channels: [], // Initialize channels as an empty array
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        this.fetchChannels();
        console.log('js',channelName)
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

app.component('add-channel', AddChannel);
app.component('channel-list', ChannelList);

app.mount('#app');
