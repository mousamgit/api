myapp.component('rowfilter', {
    props: {

      },
    data() {
        return {
            filterTitle: '',
            filterValue: '',
            filterType: '',
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
            <label for="filter-column">Filter type:</label>
            <select v-model="filterType" id="filter-column" @change="updatefilterType">
                <option value="equals">equals</option>
                <option value="range">range</option>
            </select>
            <label v-if="this.filterType === 'equals'"> for="filter-value">Filter Value:</label>
            <input v-if="this.filterType === 'equals'" v-model="filterValue" type="text" id="filter-value" @input="updatefiltervalue">
            <label v-if="this.filterType === 'range'"> for="filter-from">from:</label>
            <input v-if="this.filterType === 'range'" v-model="filterValue" type="text" id="filter-from" @input="updatefilterfrom">
            <label v-if="this.filterType === 'range'"> for="filter-to">to:</label>
            <input v-if="this.filterType === 'range'" v-model="filterValue" type="text" id="filter-to" @input="updatefilterto">
            <button @click="removeFilter">Remove</button>
        </div>
    `,
    methods: {
        updatefilterTitle(){
            this.$emit('title-changed', this.filterTitle);
            this.$emit('findindex', this);
        },
        updatefilterType(){

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
