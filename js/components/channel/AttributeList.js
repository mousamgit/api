export default {
    data() {
        return {
            attributes: [],
            columns: [],
            channel_id: 0,
            channel_name:'test_channel',
            heads: [],
            headvals: [],
            output_labels: [],
            total_records: [],
            currentPage: 1,
            itemsPerPage: 10,
            showFilterForm: false,
            filterConditions: [],
            currentFilter: {
                column: '',
                type: '=',
                value: '',
            },
            filters: [{ column: '', type: 'includes', value: '', valueTo: '' }],
        };
    },
    mounted() {
        this.fetchAttributes();
    },
    methods: {
        async fetchAttributes() {
            fetch('attribute_list_detail.php?page=' + this.currentPage, {
                method: 'POST', // or 'GET' depending on your server setup
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(this.filters),
            })
                .then(response => response.json())
                .then(data => {
                    this.heads = data.heads;
                    this.headvals = data.head_vals;
                    this.output_labels = data.output_labels;
                    this.columns = data.columns;
                    this.channel_name = data.channel_name;
                    this.total_records = data.total_records;
                    console.log(this.heads)
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
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
            this.filters.push({ column: '', type: 'includes', value: '', valueTo: '' });
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
        handleFiltersApplied(data) {
            // Update your Vue data with the new data
            this.heads = data.heads;
            this.output_labels = data.output_labels;
            this.columns = data.columns;

        },
        async exportData() {
            // Assuming `this.filters` is an array of filters you want to send as query parameters
            const queryParams = new URLSearchParams(this.filters.reduce((acc, filter, index) => {
                Object.entries(filter).forEach(([key, value]) => {
                    acc[`filter_column_${index + 1}_${key}`] = value;
                });
                return acc;
            }, {})).toString();

            const exportUrl = `channel_attribute_export.php?${queryParams}`;

            fetch(exportUrl)
                .then(response => {
                    // Check if the response is successful (status code 2xx)
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    // Trigger download
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(new Blob([blob]));
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = this.channel_name+'.csv';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.textContent = 'Data Exported Successfully';
                    document.body.appendChild(successMessage);

                    // Hide success message after 5 seconds
                    setTimeout(() => {
                        document.body.removeChild(successMessage);
                    }, 5000);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        clearFilters() {
            // Implement logic to clear filters
            this.filters = [{ column: '', type: 'includes', value: '', valueTo: '' }];
        },
    },
    template: `
    <div class="container mt-3 text-end">
    <a class="btn btn-success" @click="exportData">
      <i class="fas fa-file-export"></i> Export
    </a>
    <a class="btn btn-primary" href="/channels/channel.php">
      <i class="fas fa-arrow-left">Go Back</i> 
    </a>
<!--   <product-filter :filters="filters" :output_labels="output_labels" :heads="heads" @filters-applied="handleFiltersApplied"></product-filter>-->
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
          <td v-for="(headval, hindex) in headvals" :key="hindex">
            <span v-if="headval.includes('image')">
              <img :src="column[headval]" alt="Image" width="100" height="100">
            </span>
            <span v-else>
            
              {{ column[headval] }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination controls -->
    <div class="mt-3">
  <div class="btn-group" role="group" aria-label="Pagination">
    <button class="btn btn-primary" @click="prevPage" :disabled="currentPage === 1">Prev</button>
    <button class="btn btn-success ml-2 mr-2">Page {{ currentPage }}</button>
    <button class="btn btn-primary" @click="nextPage" :disabled="columns.length < itemsPerPage">Next</button>
  </div>
  <div class="text-muted mt-2">
    Showing {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ (currentPage - 1) * itemsPerPage + columns.length }} of {{ total_records }} records
  </div>
</div>
  </div>
  `,
};
