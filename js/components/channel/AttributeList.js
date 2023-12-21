export default {
    data() {
        return {
            attributes: [],
            columns: [],
            channel_id: 0,
            heads: [],
            output_labels: [],
            currentPage: 1,
            itemsPerPage: 10,
            showFilterForm: false,
            filterConditions: [],
            currentFilter: {
                column: '',
                type: '=',
                value: '',
            },
            filters: [{ column: '', type: '=', value: '', valueTo: '' }],
        };
    },
    mounted() {
        this.fetchAttributes();
    },
    methods: {
        async fetchAttributes() {
            try {
                const response = await fetch('attribute_list_detail.php?page=' + this.currentPage);
                const data = await response.json();

                this.heads = data.heads;
                this.output_labels = data.output_labels;
                this.columns = data.columns;
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
        nextPage() {
            this.currentPage++;
            this.fetchAttributes();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchAttributes();
            }
        },
        addFilterRow() {
            this.filters.push({ column: '', type: '=', value: '', valueTo: '' });
        },
        removeFilterRow(index) {
            if (this.filters.length > 1) {
                this.filters.splice(index, 1);
            }
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
                    // Update your Vue data with the new data if needed
                    this.heads = data.heads;
                    this.output_labels = data.output_labels;
                    this.columns = data.columns;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        clearFilters() {
            // Implement logic to clear filters
            this.filters = [{ column: '', type: '=', value: '', valueTo: '' }];
        },
    },
    template: `
    <div class="container mt-3 text-end">
    <a class="btn btn-success" :href="'/pim/channel_attribute_export.php?channel_id=' + channel_id">
      <i class="fas fa-file-export"></i> Export
    </a>

    <div class="showrows">
      <h2>Row Filter</h2>

      <div  class="rowscontainer card row justify-content-end">
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
                  <option value="=">equals</option>
                  <option value="between">range</option>
                </select>
              </div>

              <div class="col-md-2" v-if="filter.type === '='">
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

    <table class="table table-responsive mt-3">
      <thead>
        <tr>
          <th>S.N</th>
          <th v-for="(output_label, index) in output_labels" :key="index">{{ output_label }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(column, index) in columns" :key="index">
          <td>{{ index + 1 }}</td>
          <td v-for="(head, hindex) in heads" :key="hindex">
            <span v-if="head.includes('image')">
              <img :src="column[head]" alt="Image" width="100" height="100">
            </span>
            <span v-else>
              {{ column[head] }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination controls -->
    <div class="mt-3">
      <div class="btn-group" role="group" aria-label="Pagination">
        <button class="btn btn-primary" @click="prevPage">Prev</button>
        <button class="btn btn-success ml-2 mr-2">Page {{ currentPage }}</button>
        <button class="btn btn-primary" @click="nextPage">Next</button>
      </div>
    </div>
  </div>
  `,
};
