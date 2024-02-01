import ProductDetails from './ProductDetails.js';

const app = Vue.createApp({
    data() {
        return {
            products: [], // Initialize channels as an empty array
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        // this.fetchProducts();

    },
    methods: {
        // async fetchProducts() {
        //     try {
        //         const response = await fetch('attribute_data_fetch.php');
        //         const data = await response.json();
        //         this.attributes = data;
        //     } catch (error) {
        //         console.error('Error fetching attributes:', error);
        //     }
        // },
    },
});

app.component('product-details', ProductDetails);

app.mount('#app');
