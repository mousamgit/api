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
    <div class="ml-10 mr-10 mb-10 mt-5">
  <div class="card">
    <div class="card-header bg-light">
      <div class="d-flex justify-content-between align-items-center">
        <div class="entity-info d-flex align-items-center">
          <div class="logo"></div>
          <div class="entity-name ml-2">{{channel_name}}</div>
        </div>
        <div class="entity-actions">
          <button class="btn btn-outline-primary me-2" data-test-id="process-start">Process now</button>
          <button class="btn btn-primary" data-test-id="preview-channel">Preview</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
          <a class="nav-link active" id="instructions-tab" data-bs-toggle="tab" href="#instructions">Instructions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="attributes-tab" data-bs-toggle="tab" href="#attributes">Attributes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings">Settings</a>
        </li>
      </ul>
      <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="instructions">
          <div class=" mt-5">
            <div class="tab-container active mt-32">
              <h2>Welcome to {{channel_name}} Channel!</h2>
              <p class="emphasis secondary mt-32 mb-16">How to set up your channel:</p>
              <div class="flex-row">
                <p class="mb-50">In the "Attributes" tab, choose the attributes you want to include in this channel's feed. There you can create transformations to customize your content export.</p>
              </div>
              <div class="flex-row">
                <p class="mb-50">Go to the "Products tab" to add a product list. This defines which items you want to include in this feed.</p>
              </div>
              <div class="flex-row">
                
                <p class="mb-50">In the "Format tab", you will be able to order your columns.</p>
              </div>
              <div class="flex-row">
               
                <p class="mb-50">In "Settings" you can schedule your feed updates and set up the export file type.</p>
              </div>
              <div class="flex-row">
                
                <p class="mb-50">Once you're ready, You can export the channels</p>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="products">
          <h2>Welcome to Products</h2>
          <!-- Add tab content for Products -->
        </div>
        <div class="tab-pane fade" id="attributes">
          <h2>Welcome to Attributes</h2>
          <!-- Add tab content for Attributes -->
        </div>
        <div class="tab-pane fade" id="settings">
          <h2>Welcome to Settings</h2>
          <!-- Add tab content for Settings -->
        </div>
      </div>
    </div>
  </div>
</div>
  `,
};
