myapp.component('rowfilter', {
    props: {
        dataindex: {
            type: Number,
          },
      },
    data() {
        return {
            filterTitle: '',
            filterValue: '',
            filterType: '',
            filterFrom: 0,
            filterTo: 0,
            options: [], // Add an array to store options

        };
    },
    mounted() {
        console.log('this is',this.options)
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
        <div class="filter-form" :key="dataindex">
            <label for="filter-column">Filter by Column:</label>
            <select v-model="filterTitle" id="filter-column" @change="updatefilterTitle">
                <option v-for="option in options" :value="option">{{ option }}</option>
            </select>
            
            <select v-model="filterType" id="filter-column"  @change="updatefilterType">
                <option value="" selected disabled hidden>Choose here</option>
                <option value="equals">equals</option>
                <option value="range">range</option>
            </select>
            <label v-if="this.filterType === 'equals'" for="filter-value">Filter Value:</label>
            <input v-if="this.filterType === 'equals'" v-model="filterValue" type="text" id="filter-value" @input="updatefiltervalue">
            <label v-if="this.filterType === 'range'" for="filter-from">from:</label>
            <input v-if="this.filterType === 'range'" v-model="filterFrom" type="text" class="inputnumber" id="filter-from" @input="updatefilterfrom">
            <label v-if="this.filterType === 'range'" for="filter-to">to:</label>
            <input v-if="this.filterType === 'range'" v-model="filterTo" type="text" class="inputnumber" id="filter-to" @input="updatefilterto">
            
        </div>
    `,
    methods: {
        updatefilterTitle(){
            this.$emit('title-changed', this.filterTitle);
            this.$emit('findindex', this);
        },
        updatefilterType(){
            this.$emit('type-changed', this.filterType);
            this.$emit('findindex', this);
        },
        updatefilterfrom(){
            this.$emit('from-changed', this.filterFrom);
            this.$emit('findindex', this);
        },
        updatefilterto(){
            this.$emit('to-changed', this.filterTo);
            this.$emit('findindex', this);
        },
        updatefiltervalue(){
            this.$emit('value-changed', this.filterValue);
            this.$emit('findindex', this);
        },

        removeFilter() {
            // You can implement logic to remove the filter component
            // For example, emit an event to the parent component
            this.$emit('findindex', this);
            this.$emit('remove-filter');
            console.log('index',this.dataindex);
        }
    }
});