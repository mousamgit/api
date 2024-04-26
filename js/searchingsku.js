const myapp = Vue.createApp({
    data() {
        return {
            sku: '',
            results: []
        };
    },
    methods: {
        searchSKU() {
            // Simulate a database lookup using the SKU
            // Replace this with your actual API endpoint
            // For simplicity, let's assume you're using fetch
            fetch('searchingsku_handler.php?sku=' + this.sku)
                .then(response => response.json()) // Assuming the response is JSON
                .then(data => {
                    // Append the result to the results array
                    this.results.push(data);

                    // Clear the input for the next SKU
                    this.sku = '';
                })
                .catch(error => console.error('Error:', error));
        }
    }
});

