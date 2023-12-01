myapp.component('rowfilter', {
    props: {

      },
    data() {
        return {
            filterTitle: '',
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


    template: /*html*/ `
        <div class="filter-form">
            <label for="filter-column">Filter by Column:</label>
            <select v-model="filterTitle" id="filter-column" @change="updatefilterTitle">
                <option v-for="option in options" :value="option">{{ option }}</option>
            </select>
            <label for="filter-value">Filter Value:</label>
            <input v-model="filterValue" type="text" id="filter-value" @input="updatefiltervalue">
            <button @click="removeFilter">Remove</button>
        </div>
    `,
    methods: {
        updatefilterTitle(){
            this.$emit('title-changed', this.filterTitle);
            this.$emit('findindex', this);
        },
        updatefiltervalue(){
            this.$emit('value-changed', this.filterValue);
            this.$emit('findindex', this);
        },

        removeFilter() {
            // You can implement logic to remove the filter component
            // For example, emit an event to the parent component
            this.$emit('remove-filter', this);
        }
    }
});
