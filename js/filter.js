myapp.component('rowfilter', {
    props: {

    },
    data() {
        return {
            selectedFilterColumn: '',
            filterValue: '',
            options: [], // Add an array to store options
        };
    },
    mounted() {
        // Get the colscontainer element
        const colsContainer = document.querySelector('.colscontainer');

        // Get all the colfilter buttons within colscontainer
        const colFilterButtons = colsContainer.querySelectorAll('.colfilter');

        // Iterate over the colfilter buttons and push their text content to the options array
        colFilterButtons.forEach(button => {
            this.options.push(button.textContent.trim());
        });
    },
    applyFiltersFromHtml() {
        // Emit a custom event with selectedFilterColumn and filterValue
        this.$emit('filter-changed', {
            selectedColumn: this.selectedFilterColumn,
            filterValue: this.filterValue,
        });
    },
    template: /*html*/ `
        <div class="filter-form">
            <label for="filter-column">Filter by Column:</label>
            <select v-model="selectedFilterColumn" id="filter-column">
                <option v-for="option in options" :value="option">{{ option }}</option>
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
