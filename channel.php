
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channels List</title>
    <!-- Include Vue.js -->
    <script src="https://unpkg.com/vue@3"></script>
    <!-- Add some styling (you can adjust this based on your preferences) -->
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div id="app">
    <h1>Channels List</h1>
    <add-channel-modal></add-channel-modal>

    <!-- ChannelsList.vue component -->
    <channels-list :channels="channels"></channels-list>
</div>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./js/channel.js"></script>
<script>
    // Your existing scripts, Vue CDN, etc.

    // Ensure that Vue app is initialized in the 'app' element
    const app = Vue.createApp({
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
</script>
</body>
</html>

