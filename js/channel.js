const app = Vue.createApp({
    data() {
        return {
            channels: null // Initialize channels as null
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
                const response = await fetch('channel_data_detch.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the channels data
                this.channels = data;
            } catch (error) {
                console.error('Error fetching channels:', error);
            }
        }
    }
});

app.component('channels-table', {
    props: ['channels'],
    template: `
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Last Time Proceed</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="channel in channels" :key="channel.id">
                    <td>{{ channel.id }}</td>
                    <td>{{ channel.name }}</td>
                    <td>{{ channel.type }}</td>
                    <td>{{ channel.status === 1 ? 'Active' : 'Inactive' }}</td>
                    <td>{{ channel.last_time_proceed }}</td>
                </tr>
            </tbody>
        </table>
    `
});

app.mount('#app');

