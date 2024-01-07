export default  {
    props: ['filters', 'output_labels', 'heads'],
    methods: {
        addFilterRow() {
            this.filters.push({ column: '', type: 'includes', value: '', valueTo: '' });
        },
        removeLastFilterRow() {
            if (this.filters.length > 1) {
                this.filters.pop();
            }
        },
        async applyFilters() {
            fetch('attribute_list_detail.php', {
                method: 'POST', // or 'GET' depending on your server setup
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(this.filters),
            })
                .then(response => response.json())
                .then(data => {
                    // Handle the response from the server
                    console.log('Response from attribute_list_detail.php:', data);
                    this.$emit('filters-applied', data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        clearFilters() {
           location.reload();
        },
    },
    template: `
    <div class="showrows">
      <h2>Row Filter</h2>

      <div class="rowscontainer card row justify-content-end">
        <div class="filter-form card p-3 mb-3 " v-for="(filter, rowIndex) in filters" :key="rowIndex">
          <div class="row justify-content-center">
            <div class="col-md-2">
              <center><label for="filter-column">Filter by Column:</label><center>
              <select v-model="filter.column" id="filter-column" class="form-select">
                <option v-for="(output_label, index) in output_labels" :key="index" :value="heads[index]">{{ output_label }}</option>
              </select>
            </div>

            <div class="col-md-2">
              <center><label for="filter-type">Filter Type:</label><center>
              <select v-model="filter.type" id="filter-type" class="form-select">
                <option value="includes">includes</option>
                <option value="=">equals</option>
                <option value="between">range</option>
              </select>
            </div>

            <div class="col-md-2" v-if="filter.type === '='">
              <center><label for="filter-value">Value:</label><center>
              <input v-model="filter.value" type="text" id="filter-value" class="form-control">
            </div>

            <div class="col-md-2" v-else-if="filter.type === 'includes'">
              <center><label for="filter-value">Value:</label><center>
              <input v-model="filter.value" type="text" id="filter-value" class="form-control">
            </div>

            <div class="col-md-2" v-else-if="filter.type === 'between'">
              <center><label for="filter-range-value">From:</label><center>
              <input v-model="filter.value" type="number" id="filter-range-value" class="form-control">
              <center><label for="filter-range-value-to">To:</label><center>
              <input v-model="filter.valueTo" type="number" id="filter-range-value-to" class="form-control">
            </div>

            <div class="col-md-1">
              <button class="btn btn-success mt-2" @click="addFilterRow">+</button>
              <button class="btn btn-danger mt-2" @click="removeLastFilterRow">-</button>
            </div>
          </div>
        </div>

        <center>
          <div class="filter-btn-container mt-3 justify-content-center">
            <button class="btn btn-info" @click="applyFilters">Filter List</button>
            <button class="btn btn-secondary" @click="clearFilters">Clear All Filters</button>
          </div>
        </center>
      </div>
    </div>
`,
};
