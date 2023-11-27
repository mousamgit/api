myapp.component('rowfilter', {
    props: {

    },
    data() {
        return {

        };
    },
    template: /*html*/ `
        <div class="filter-form">
            <label for="filter-column">Filter by Column:</label>
            <select v-model="selectedFilterColumn" id="filter-column">
                <option v-for="column in columns" :value="column">{{ column }}</option>
            </select>
            <label for="filter-value">Filter Value:</label>
            <input v-model="filterValue" type="text" id="filter-value">
            <button @click="removeFilter">Remove</button>
        </div>
    `,
    methods: {
        removeFilter() {
            // You can implement logic to remove the filter component
            // For example, emit an event to the parent component
            this.$emit('remove-filter', this);
        }
    }
});
