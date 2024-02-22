
const app = Vue.createApp({

    data() {
        return {
            productDetails: [],
            showFilters: 9,
            isEditing:0,
            username: 'mousam',
            rIndex:-1,
            cIndex:-1,
            formData:{}
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        async  controlFilters(filter_no) {
            const dataToSend = {
                filter_no: filter_no
            };

            try {
                const response = await fetch('../control_user_filters.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                if (!response.ok) {
                    throw new Error('Failed to update database');
                }

                console.log('Database updated successfully');
                location.reload();
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        convertToTitleCase(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        async fetchProducts() {
                this.productDetails= [];
                this.showFilters= 9;
                this.isEditing=0;
                this.rIndex=-1;
                this.cIndex=-1;
                this.formData={}
            const response = await fetch('products/fetch_product_details.php?page=1', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
                .then(data => {
                    this.products = data.products;
                    this.productDetails = data.product_details;
                    this.productValues = data.product_values;
                    this.totalRows = data.total_rows;
                    this.columnValues = data.column_values_row;
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

        },
        async saveEdit() {

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
                console.log(response)
                this.fetchProducts();
                console.log('Database updated successfully');
                // location.reload();
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        cancelEdit() {
            this.editingCell = null;
        },
    },
    template: `<div>
      <!-- Show Saved Filters -->
      <div v-for="(fvalue, fkey) in productDetails" class="tooltip-container">
        <button class="btn btn-primary" >
          Show Saved Filters {{ fkey + 1 }}
        </button>
        <div class="tooltip-content">
          <!-- Your filtered data goes here -->
          
        </div>
      </div>
      
      <!-- Table -->
      <table id="myTable" class="display">
        <thead>
          <tr>
            <th v-for="colName in columnValues">{{ convertToTitleCase(colName) }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row,rowIndex) in productValues">
           <template v-for="(colName,colIndex) in columnValues">
           <td>
          
            <div v-if="rIndex==rowIndex && colIndex==cIndex">
            <input type="hidden" v-model="formData.sku" value="row['sku']">
            <input type="hidden" v-model="formData.columnName" value="colName">
            <input type="hidden" v-model="formData.oldValue" value="row[colName]">
            <input type="text" v-model="formData.editedValue" value="row['colName']">
            <button @click="saveEdit()">Update</button>
            <button @click="cancelEdit">Cancel</button>
            </div>
            <template v-if="colName == 'sku'">
             {{ row[colName] }} 
            </template>
            <template v-else>
            
            <a class="editfield" @click="changeEditValue(rowIndex,colIndex,row[colName],row[colName],row['sku'],colName)">
                {{ row[colName] }} <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </a>
            </template>
            </td>
            </template>
          </tr>
        </tbody>
      </table>
      
    </div>`,
});


app.mount('#index');
