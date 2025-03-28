import ProductFilters from '../../products/js/ProductFilters.js';

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
            showFilter:true,
            showSavedFilters:false,
            draggedIndex: null
        };
    },
    mounted() {
        this.fetchProducts();
    },

    methods: {
        showHideFilter(){
            this.showFilter = !this.showFilter;
        },
        selectFilter(){
            this.showSavedFilters = !this.showSavedFilters;
        },
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
                    this.filters = data.filter_names;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
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
            // Get current date and time
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
        }
    },
    template: `<div>
    
    <div class=" toolbar pim-padding">
    
        <div class="saved-filter-container">
       
        <select name="" id="" style="width:20% !important;">
            <option value="0" @click="fetchProducts()" selected><a class="btn" >All Product   <i class="fa-solid fa-caret-down"></i></a> </option>
            <template v-for="(fvalue, fkey) in filters">
              <option value="{{fvalue['id']}}" @click="controlFilters(fvalue['id'])"><a class="btn" >{{fvalue['filter_name']}}   </a> </option>
            </template>
        </select>
      
        <a class="btn btn-success" @click="exportToCSV">Export to CSV</a>
        <a class="btn" @click="showHideFilter">Filter</a>
        </div>
        </div>

    
    
    </div>
    <div style="height:100px"></div>
    <div class="bg-light shadow filter-container animation-mode" :class="{ 'active': showFilter }">
    <product-filters :productDetails="productDetails" :showFilters="showFilters" @filters-updated="handleFiltersUpdated"></product-filters>
    </div>
        <div class="pim-padding home-table-container">   
        

         <div class="table-responsive">
          <table class="pimtable  display homepage-table">
            <thead>
              <tr>
                <th class="hidden">S.N</th>
                 <th :col="colName" v-for="(colName, index) in columnValues" :key="index" 
                :draggable="true" @dragstart="handleDragStart(index)" 
                @dragover="handleDragOver(index)" @drop="handleDrop(index)" :style="{ backgroundColor: draggedIndex === index ? 'lightblue' : 'inherit' }">
                {{ convertToTitleCase(colName) }}
                </th>
                
              </tr>
            </thead>
            <tbody>
            
              <tr v-for="(row,rowIndex) in productValues">
              <td class="hidden">{{rowIndex+1}}</td>
               <template v-for="(colName,colIndex) in columnValues">
               <td  :col="colName">              
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
                  <template v-if="row[colName]">
                  <a :href="row[colName]" target="_blank">
                  <img :src="row[colName]" :alt="row['product_title']">
                  </a>
                  </template>
                  <template v-else> <img src="/css/no-image.png?v=1" alt="no image"> </template>
                </template>
                <template v-else>
                
                <a class="editfield" @click="changeEditValue(rowIndex,colIndex,row[colName],row[colName],row['sku'],colName)">
                    {{ row[colName] }} <i class="fa fa-pencil" aria-hidden="true"></i></i>
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
       

      </div>

`,
});
app.mount('#index');
app.component('product-filters', ProductFilters);

