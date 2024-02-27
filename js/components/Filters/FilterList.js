
const app = Vue.createApp({
    data() {
        return {
            currentPage:1,
            items:[],
            rows:[],
            columns:[],
            isEditing:0,
            rIndex:-1,
            cIndex:-1,
            formData:{},
            currentPage: 1,
            itemsPerPage: 10,
            totalRows:0,
        };
    },
    mounted() {
        this.fetchData();
    },


    methods: {
        changePage()
        {
            this.initializeData()
            this.fetchData();
        },
        totalPages(totalRows,itemsPerPage){
            return Math.ceil(totalRows / itemsPerPage);
        },
        initializePagination()
        {
            this.currentPage=1,
                this.itemsPerPage= 10,
                this.totalRows=0
        },
        nextPage() {
            this.initializeData();
            this.currentPage++;
            this.fetchData();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.initializeData();
                this.currentPage--;
                this.fetchData();
            }
        },
        initializeData()
        {
            this.isEditing=0;
            this.rIndex=-1;
            this.cIndex=-1;
            this.formData={}
        },
        async saveEdit() {
            this.formData.table='user_filters'
            this.formData.pr_key='id';

            try {
                const response = await fetch('../updatetablevalue.php', {
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
                    this.rows[this.rIndex][this.columns[this.cIndex]] = this.formData.editedValue;
                }
                this.initializeData();
                console.log('Database updated successfully');
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        async fetchData() {
            const response = await fetch('./fetch_filter_list.php?page=' + this.currentPage,  {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
                .then(data => {
                    this.rows = data.rows;
                    this.columns = data.columns;
                    this.totalRows = data.total_rows;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        convertToTitleCase(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        changeEditValue(rowIndex,columnIndex,oldValue,editedValue,id,colName)
        {
            this.isEditing = 1;
            this.rIndex = rowIndex;
            this.cIndex = columnIndex;
            this.formData.oldValue = oldValue;
            this.formData.editedValue = editedValue;
            this.formData.id = id;
            this.formData.colName = colName;
            setTimeout(() => {
                document.getElementById('editInput').focus();
            }, 0);

        },
    },
    template: `<div class="table-responsive">
              <h2>Filter List</h2>
                <table class="table table-stribe">
                  <thead>
                    <tr>              
                      <th v-for="colName in columns">
                      {{ convertToTitleCase(colName) }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                  
                  <tr v-for="(row,rowIndex) in rows">
                       <template v-for="(colName,colIndex) in columns">
                       <td>   
                        
                        <div v-if="rIndex==rowIndex && colIndex==cIndex ">
                        <input type="hidden" v-model="formData.id" value="row['filter_no']">
                        <input type="hidden" v-model="formData.columnName" value="colName">
                        <input type="hidden" v-model="formData.oldValue" value="row[colName]">
                        <input id="editInput" type="text" v-model="formData.editedValue" value="row['colName']" @keydown.tab.prevent="saveEdit()" @mouseleave="saveEdit()" @keyup.enter="saveEdit()">
                        </div>
                        <div v-else>
                        <span v-if="colName == 'user_name' || colName == 'filter_no'">
                         {{ row[colName] }} 
                        </span>
                        <span v-else>
                         <a class="editfield" @click="changeEditValue(rowIndex,colIndex,row[colName],row[colName],row['filter_no'],colName)">
                            {{ row[colName] }} <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        </span>
                        
                        </div>
                        </td>
                        </template>
                    </tr>
                  </tbody>
                </table>
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
                <button class="btn btn-primary" @click="nextPage" :disabled="rows.length < itemsPerPage">Next</button>
              </div>
              <div class="text-muted mt-2">
                Showing {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ (currentPage - 1) * itemsPerPage + rows.length }} of {{totalRows}} records
              </div>
        </div>
    </div>
`,
});
app.mount('#filterList');
