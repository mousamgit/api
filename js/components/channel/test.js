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
                type: 'equals',
                value: '',
            },
            filters: [{ column: '', type: 'equals', value: '', valueTo: '' }],
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
        toggleFilterForm() {
            this.showFilterForm = !this.showFilterForm;
        },
        addFilterRow() {
            this.filters.push({ column: '', type: 'equals', value: '', valueTo: '' });
        },

        removeLastFilterRow() {
            if (this.filters.length > 1) {
                this.filters.splice(this.filters.length - 1, 1);
            }
        },
        applyFilters() {
            // Implement logic to apply filters
            // You can use this.filterConditions to access the filter conditions
            // For now, let's log the filter conditions to the console
            console.log('Filter Conditions:', this.filterConditions);
        },
        clearFilters() {
            // Implement logic to clear filters
            this.filterConditions = [];
        },
    },
    template: `
    <div class="container mt-3 text-end">
      <a class="btn btn-success" :href="'/pim/channel_attribute_export.php?channel_id=' + channel_id">
        <i class="fas fa-file-export"></i> Export
      </a>

      <div class="showrows">
        <h2>Row Filter</h2>

        <!-- Show Add Condition, Filter, Clear Filter -->

        <!-- Show Filter Form if toggled -->
        <div v-if="showFilterForm" class="rowscontainer">
          <div class="filter-form card p-3 mb-3">
            <div class="row">
              <div class="col-md-3">
                <label for="filter-column">Filter by Column:</label>
                <select v-model="currentFilter.column" id="filter-column" class="form-select">
                  <option v-for="(output_label, index) in output_labels" :key="index" :value="heads[index]">{{ output_label }}</option>
                </select>
              </div>

              <div class="col-md-2">
                <label for="filter-type">Filter Type:</label>
                <select v-model="currentFilter.type" id="filter-type" class="form-select">
                  <option value="equals">equals</option>
                  <option value="range">range</option>
                </select>
              </div>

              <div class="col-md-2" v-if="currentFilter.type === 'equals'">
                <label for="filter-value">Value:</label>
                <input v-model="currentFilter.value" type="text" id="filter-value" class="form-control">
              </div>

              <div class="col-md-2" v-else-if="currentFilter.type === 'range'">
                <label for="filter-range-value">From:</label>
                <input v-model="currentFilter.value" type="number" id="filter-range-value" class="form-control">
                <label for="filter-range-value-to">To:</label>
                <input v-model="currentFilter.valueTo" type="number" id="filter-range-value-to" class="form-control">
              </div>

              <div class="col-md-1">
                <button class="btn btn-success mt-2" @click="addFilterRow">+</button>
                <button class="btn btn-danger mt-2" @click="removeLastFilterRow">-</button>
              </div>

            </div>
          </div>
        </div>

        <div class="filter-btn-container mt-3">
          <button class="btn btn-primary" @click="toggleFilterForm">Add Condition</button>
          <button class="btn btn-info" @click="applyFilters">Filter</button>
          <button class="btn btn-secondary" @click="clearFilters">Clear All Filters</button>
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
    </div>`,

};