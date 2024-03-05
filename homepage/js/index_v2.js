import ProductFilters from '../../products/js/ProductFiltersV2.js';

const app = Vue.createApp({
    data() {
        return {
            productDetails: [],
            productValues:[],
            productValuesTotal:[],
            showFilters: 9,
            isEditing:0,
            rIndex:-1,
            cIndex:-1,
            formData:{},
            filters:[],
            currentPage: 1,
            itemsPerPage: 100,
            totalRows:0,
            filterList:[],
            draggedIndex: null
        };
    },
    mounted() {
        this.fetchProducts();
    },

    methods: {
        changePage()
        {
            this.initializeData()
            this.fetchProducts();
        },
        totalPages(totalRows,itemsPerPage){
            return Math.ceil(totalRows / itemsPerPage);
        },
        initializePagination()
        {
            this.currentPage=1,
            this.itemsPerPage= 100,
            this.totalRows=0
        },
        nextPage() {
            this.initializeData();
            this.currentPage++;
            this.fetchProducts();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.initializeData();
                this.currentPage--;
                this.fetchProducts();
            }
        },
        initializeData()
        {
            this.showFilters= 9;
            this.isEditing=0;
            this.rIndex=-1;
            this.cIndex=-1;
            this.formData={}
        },

        async  controlFilters(filter_no) {
            const dataToSend = {
                filter_no: filter_no
            };

            try {
                const response = await fetch('./control_user_filters.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                if (!response.ok) {
                    throw new Error('Failed to update database');
                }
                this.initializeData();
                this.initializePagination();
                this.fetchProducts();
                console.log('Database updated successfully');

            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        convertToTitleCase(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        async fetchProducts() {
            const response = await fetch('./fetch_filtered_data.php?page=' + this.currentPage,  {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
                .then(data => {
                    this.products = data.products;
                    this.productDetails = data.product_details;
                    this.productValues = data.product_values;
                    this.productValuesTotal = data.total_product_values;
                    this.totalRows = data.total_rows;
                    this.columnValues = data.column_values_row;
                    this.filters = data.filter_ids;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        handleDragStart(index) {
            this.draggedIndex = index;
        },
        handleDragOver(index) {
            event.preventDefault();
        },
        handleDrop(index) {
            if (this.draggedIndex !== null && index !== this.draggedIndex) {
                const removed = this.columnValues.splice(this.draggedIndex, 1)[0];
                this.columnValues.splice(index, 0, removed);
                this.draggedIndex = null;
                const dataToSend = {
                    column_values: this.columnValues
                };
                try {
                    const response =  fetch('./save_column_order_values.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    if (!response.ok) {
                        throw new Error('Failed to order columns');
                    }
                    // alert('its done')


                } catch (error) {
                    console.error('Error updating database:', error);
                }

            }
        },
        changeEditValue(rowIndex,columnIndex,oldValue,editedValue,sku,colName)
        {
            this.isEditing = 1;
            this.rIndex = rowIndex;
            this.cIndex = columnIndex;
            this.formData.oldValue = oldValue;
            this.formData.editedValue = editedValue;
            this.formData.sku = sku;
            this.formData.colName = colName;
            setTimeout(() => {
                document.getElementById('editInput').focus();
            }, 0);

        },
        async  getTooltipDetails(filter_no) {
            const dataToSend = {
                filter_no: filter_no
            };

            try {
                const response = await fetch('./fetch_tooltip_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                })

                if (!response.ok) {
                    throw new Error('Failed to fetch tooltip details');
                }
                const data = await response.json();
                console.log('list', data);
                this.filterList = data;

            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        async saveEdit() {
            this.formData.table='pim'
            this.formData.pr_key='sku';
            try {
                const response = await fetch('./updatetablevalue.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        formData:this.formData
                    }),
                });
                if (!response.ok) {
                    throw new Error('Failed to update database');
                }
                if (this.rIndex !== -1 && this.cIndex !== -1) {
                    console.log(this.formData.editedValue)
                    this.productValues[this.rIndex][this.columnValues[this.cIndex]] = this.formData.editedValue;
                }
                this.initializeData();
                console.log('Database updated successfully');
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        exportToCSV() {
            let csvContent = "data:text/csv;charset=utf-8," + this.getHeaderRowCSV() + "\n";

            const rows = this.productValuesTotal.map(row => {
                return this.columnValues.map(colName => row[colName]);
            });
            csvContent += rows.map(e => e.join(",")).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            var now = new Date();
            var formattedDateTime = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate() + '_' + now.getHours() + '-' + now.getMinutes() + '-' + now.getSeconds();
            var filename = "export_filter_" + formattedDateTime + ".csv";
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
        },
        getHeaderRowCSV() {
            return this.columnValues.map(colName => '"' + colName + '"').join(","); // Surround column names with double quotes
        },
        cancelEdit() {
            this.initializeData();
            this.fetchProducts();
        },
        handleFiltersUpdated() {
            console.log('filters updated event received in parent component');
            this.initializeData();
            this.initializePagination();
            this.fetchProducts().then(() => {
                console.log('Products fetched successfully');
            }).catch(error => {
                console.error('Error fetching products:', error);
            });
        }
    },
    template: `<div>
      <div class="row">
        <div class="row">      
        <div class="col-md-2 justify-content-end">
         <a class="btn btn-success" @click="exportToCSV" style="background: #41b883 !important;">
              Export
         </a>
         </div>
        </div>
    
        <div class="col-md-9 home-table-container">   
        <div v-for="(fvalue, fkey) in filters" class="tooltip-container" @mouseover="getTooltipDetails(fvalue)">
            <button class="btn btn-primary" @click="controlFilters(fvalue)">
              Show Saved Filters {{ fkey + 1 }}
            </button>
            <div class="tooltip-content">
             <div v-for="(value,index) in filterList">
             <template v-if="index==0">
             <p>
             <span>{{ value.attribute_name }}</span> 
             <span> &nbsp;&nbsp;{{ value.filter_type }}</span> 
             <span> &nbsp;&nbsp;{{ value.attribute_condition }}</span> 
             </p>
             </template>
             <template v-else>
             <p>
            <strong>{{ value.op_value }}</strong>
             </p>
             <p>
             <span>{{ value.attribute_name }}</span> 
             <span> &nbsp;&nbsp;{{ value.filter_type }}</span> 
             <span> &nbsp;&nbsp;{{ value.attribute_condition }}</span> 
             </p>
             </template>
             </div>
            </div>
            
          </div>
       
         <div class="table-responsive">
          <table id="myTable" class="table display homepage-table">
            <thead>
              <tr>
                <th>S.N</th>
                 <th v-for="(colName, index) in columnValues" :key="index" 
                :draggable="true" @dragstart="handleDragStart(index)" 
                @dragover="handleDragOver(index)" @drop="handleDrop(index)" :style="{ backgroundColor: draggedIndex === index ? 'lightblue' : 'inherit' }">
                {{ convertToTitleCase(colName) }}
                </th>
              </tr>
            </thead>
            <tbody>
            
              <tr v-for="(row,rowIndex) in productValues">
              <td>{{rowIndex+1}}</td>
               <template v-for="(colName,colIndex) in columnValues">
               <td>              
                <div v-if="rIndex==rowIndex && colIndex==cIndex">
                <input type="hidden" v-model="formData.sku" value="row['sku']">
                <input type="hidden" v-model="formData.columnName" value="colName">
                <input type="hidden" v-model="formData.oldValue" value="row[colName]">
                <input id="editInput" type="text" v-model="formData.editedValue" value="row[colName]" @keydown.tab.prevent="saveEdit()" @mouseleave="saveEdit()" @keyup.enter="saveEdit()">
                </div>
                <div v-else>
                <template v-if="colName == 'sku'">
                 {{ row['sku'] }} 
                </template>
                <template v-else-if="colName.includes('imag')">
                  <template v-if="row[colName].length>0">
                  <a :href="row[colName]" target="_blank">
                  <img :src="row[colName]" :alt="row['product_title']">
                  </a>
                  </template>
                  <template v-else> No Image </template>
                </template>
                <template v-else>
                <a class="editfield" @click="changeEditValue(rowIndex,colIndex,row[colName],row[colName],row['sku'],colName)">
                    {{ row[colName] }} <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                </div>
                </template>
                </td>
                </template>
              </tr>
            </tbody>
          </table>
          </div>
           <div class="mt-3">
                <div class="btn-group" role="group" aria-label="Pagination">
                <button class="btn btn-primary" @click="prevPage" :disabled="currentPage === 1">Prev</button>
                <select v-model="currentPage" @change="changePage" class="page-dropdown">
                    <template v-for="(value,index) in totalPages(totalRows,itemsPerPage)" :key="index" >
                    <template v-if="currentPage==index+1">                 
                    <option :value="index+1" selected>Page {{ index +1 }}</option>
                    </template>                   
                    <template v-else>
                    <option :value="index+1">Page {{ index +1 }}</option>
                    </template>                   
                </select>
                <button class="btn btn-primary" @click="nextPage" :disabled="productValues.length < itemsPerPage">Next</button>
              </div>
              <div class="text-muted mt-2">
                Showing {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ (currentPage - 1) * itemsPerPage + productValues.length }} of {{totalRows}} records
              </div>
        </div>
        </div>
       
        <div class="col-md-3">
            <product-filters :productDetails="productDetails" :showFilters="showFilters" @filters-updated="handleFiltersUpdated"></product-filters>
        </div>
      </div>
    </div>
`,
});
app.mount('#index');
app.component('product-filters', ProductFilters);
