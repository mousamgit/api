import ProductFilters from './ProductFilters.js';

const app = Vue.createApp({
    components: {
        'product-filters': ProductFilters,
    },
    data() {
        return {
            productDetails: [], // Initialize channels as an empty array
            showFilters: 9, // Initialize channels as an empty array
        };
    },
    mounted() {
            this.fetchProducts();
    },
    methods: {
        async fetchProducts() {
            const response = await fetch('products/fetch_product_details.php?page=1', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
                .then(data => {
                    this.products = data.products;
                    this.productDetails = data.product_details;
                    this.productValues = data.product_values;
                    this.totalRows = data.total_rows;
                    this.columnValues = data.column_values_row;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
    },
    template: `
 <product-filters :productDetails="productDetails" :showFilters="showFilters" @filters-updated="fetchProducts"></product-filters>
`,
});

app.component('product-filters', ProductFilters);

app.mount('#filter');
