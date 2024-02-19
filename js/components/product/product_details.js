import ProductDetails from './ProductDetails.js';

const app = Vue.createApp({
    data() {
        return {
            products: [], // Initialize channels as an empty array
        };
    },
    mounted() {
    },
    methods: {

    },
});

app.component('product-details', ProductDetails);

app.mount('#app');
