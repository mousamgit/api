// product.js
import AddProduct from './AddProduct.js';
import ProductList from './ProductList.js';

const app = Vue.createApp({
    data() {
        return {
            products: [], // Initialize products as an empty array
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        this.fetchProducts();

    },
    methods: {
        async fetchProducts() {
            try {
                // Make an AJAX request to your PHP file
                const response = await fetch('fetch_product.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the products data
                this.products = data;
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        },

    },
});

app.component('add-product', AddProduct);
app.component('product-list', ProductList);

app.mount('#app');
