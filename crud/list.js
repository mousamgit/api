import listFilters from '../../crud/listFilters.js?v=2';
import listFilterForm from '../../crud/listFilterForm.js?v=2';

const List = {
    props: ['urlsku','primary_table','key_name','show_filter_button'],
    data() {
        return {
            rootURL:'',
            listDetails: [],
            listValues:[],
            listValuesTotal:[],
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
            showFilter:false,
            showSavedFilters:false,
            draggedIndex: null,
            isDragging: false,
            startClientX: 0,
            startScrollLeft: 0,
            tableWidth: 0,
            filter_no:0,
            showColumnSelector: false,
            columns: [],
            selectedRows: [],
            pageSize:100,
            selectAllChecked:{},
            itemNo:0,
            isLoading:false,
            exportRows: [], // Array to store data for export
            checkedRows: {}, // Object to track checked rows
            selectAllCheckbox: false,
            dataTypeValue:'varchar',
            orderColumnName:this.key_name,
            orderColumnValue:'ASC',
        };
    },
    mounted() {
        const { protocol, host } = window.location;
        this.rootURL = `${protocol}//${host}`
        this.clearCheckedState()
        this.fetchUserColumns();
        this.fetchlists();
        document.addEventListener('click', this.handleClickOutside);
    },
    computed: {
        isExportDisabled() {
            return this.exportRows.length === 0;
        }
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },


    methods: {
         clacwidth(name,short,long){
            if (long.includes(name)) {return 400;}
            else if  (short.includes(name)) {return 35;}
            else if  (name.length<6) {return 115;}
            else{return name.length*6+80}
         },
         getDataTypeValue(columnName,columnValue) {
             for (const column of this.columns) {
                 if (column.column_name === columnName) {

                     if(column.data_type=='varchar' || column.data_type=='text' || column.data_type=='longtext')
                     {
                          if(columnValue=='DESC'){
                            return 'A-Z'
                          }else{
                          return 'Z-A'
                          }
                      }else{
                          if(columnValue=='DESC'){
                            return 'High To Low'
                          }else{
                            return 'Low To High'
                          }
                      }
                 }
             }

        },
        updateFetchColumns(column_name,column_value){
            this.orderColumnName = column_name;
            this.orderColumnValue = column_value;
            this.fetchlists();
        },
        clearCheckedState() {
            this.itemNo=0
            this.checkedRows = {};
            this.selectAllChecked = {};
            this.selectAllCheckbox = {};
            this.exportRows = [];
            localStorage.removeItem('checkedRows');
        },
        toggleRowSelection(sku) {
            this.checkedRows[sku] = !this.checkedRows[sku];
            localStorage.setItem('checkedRows', JSON.stringify(this.checkedRows));
            if (this.checkedRows[sku]) {
                this.exportRows.push(this.listValues.find(row => row.sku === sku));
            } else {
                const index = this.exportRows.findIndex(row => row.sku === sku);
                if (index !== -1) {
                    this.exportRows.splice(index, 1);
                }
            }
            this.itemNo= this.exportRows.length;
        },
        selectAllRows(current_page) {
            const startIndex = 0;
            const endIndex = Math.min(startIndex + this.pageSize, this.listValues.length);

            for (let i = startIndex; i < endIndex; i++) {
                const sku = this.listValues[i][this.key_name];
                this.checkedRows[sku] = this.selectAllChecked[current_page];

                if (this.selectAllChecked[current_page]) {
                    // If Select All is checked, add the row to exportRows
                    if (!this.exportRows.some(row => row[this.key_name] === sku)) {
                        this.exportRows.push(this.listValues[i]);
                    }
                } else {
                    // If Select All is unchecked, remove the row from exportRows (if exists)
                    const exportIndex = this.exportRows.findIndex(row => row[this.key_name] === sku);
                    if (exportIndex !== -1) {
                        this.exportRows.splice(exportIndex, 1);
                    }
                }
            }
            this.itemNo=this.exportRows.length;

        },
        selectAllPagesRow() {
            if (this.selectAllCheckbox) {
                this.SelectAllPagesRow(1); // Select All
            } else {
                this.SelectAllPagesRow(0); // Unselect All
            }
        },
        SelectAllPagesRow(value)
        {
            if(value==1)
            {
                this.exportRows=[];
                const startIndex = 0;
                const endIndex = this.totalRows;
                const totalPages = parseInt(endIndex/100);
                for (let i = startIndex; i < totalPages; i++) {
                    this.selectAllChecked[i]=true;
                }

                for (let i = startIndex; i < endIndex; i++) {
                    const sku = this.listValuesTotal[i][this.key_name];
                    this.checkedRows[sku] = true;
                    this.exportRows.push(this.listValuesTotal[i]);
                }

                this.itemNo=this.exportRows.length;
            }
            else{
                this.clearCheckedState();
            }

        },
        exportToCSV() {
            if (this.exportRows.length === 0) {
                alert("Please Select Items From Table To Export")
                // Export cannot proceed if there are no rows to export
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8," + this.getHeaderRowCSV() + "\n";
            const columnNames = this.columnValues; // Get the column names in the correct order
            console.log(this.exportRows);
            this.exportRows.forEach(row => {
                if(row)
                {
                    const rowData = columnNames.map(colName => {
                        let value = row[colName];
                        if (typeof value === 'string' && value.includes(',')) {
                            // If the value contains a comma, enclose it in double quotes and escape any existing double quotes
                            value = '"' + value.replace(/"/g, '""') + '"';
                        }
                        return value;
                    });
                    csvContent += rowData.join(",") + "\n";
                }

            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);

            const now = new Date();
            const formattedDateTime = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate() + '_' + now.getHours() + '-' + now.getMinutes() + '-' + now.getSeconds();
            const filename = "export_filter_" + formattedDateTime + ".csv";

            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            this.clearCheckedState();
        },

        getHeaderRowCSV() {
            return this.columnValues.map(colName => '"' + colName + '"').join(","); // Surround column names with double quotes
        },
        updateColumns(selectedColumns, selectedStatus) {
            if (selectedStatus == true) {
                selectedStatus = 1;
            } else {
                selectedStatus = 0;
            }
            let dataToSend = {
                'column_name': selectedColumns,
                'selectedStatus': selectedStatus,
                'table_name': this.primary_table
            }

            try {
                fetch(this.rootURL+'/users/save_user_columns.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to save columns');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.fetchUserColumns();
                            this.fetchlists();
                        } else {
                            console.error('Error updating database:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating database:', error);
                    });
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        toggleCheckbox(column) {
            column.selected = !column.selected;
            this.updateColumns(column.column_name, column.selected);
        },
        toggleColumnSelector() {
            this.showColumnSelector = !this.showColumnSelector;
        },
        async fetchUserColumns() {
            const dataToSend = {
                table_name: this.primary_table
            };

            try {
                const response = await fetch(this.rootURL+'/users/fetch_columns_user_wise.php',{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });
                const data = await response.json();
                this.columns = data;
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
        handleClickOutside(event) {
            const isInsideFilterContainer = event.target.closest('.right-slider-header');
            if ((event.target.tagName == 'HEADER' || event.target.tagName == 'NAV' || event.target.tagName == 'TABLE' || event.target.tagName == 'TR' || event.target.tagName == 'TD' || event.target.tagName == 'TH') && !isInsideFilterContainer) {
                this.showFilter = false;
                this.showColumnSelector= false;
            }
            else{

            }
        },
        getlistUrl(sku){
            return('/product.php?sku='+sku);
        },
        handleMouseDown(event) {
            this.isDragging = true;
            this.startClientX = event.clientX;
            this.startScrollLeft = this.$refs.overflowContainer.scrollLeft;
            this.tableWidth = this.$refs.overflowContainer.scrollWidth;
        },
        handleMouseMove(event) {
            if (!this.isDragging) return;
            const dx = this.startClientX - event.clientX;
            this.$refs.overflowContainer.scrollLeft = this.startScrollLeft + dx;
        },
        handleMouseUp() {
            this.isDragging = false;
        },
        showHideFilter(){
            this.showFilter = !this.showFilter;
        },
        selectFilter(){
            this.showSavedFilters = !this.showSavedFilters;
        },
        changePage()
        {
            this.initializeData()
            this.fetchlists();
        },
        totalPages(totalRows,itemsPerPage){
            return Math.ceil(totalRows / itemsPerPage);
        },
        visiblePages(totalRows,itemsPerPage) {
            const pages = [];
            const startPage = Math.max(1, this.currentPage - 2);
            const endPage = Math.min(Math.ceil(totalRows / itemsPerPage), startPage + 4);
            console.log('current'+this.currentPage+'start'+startPage+'end'+endPage);

            for (let i = startPage; i <= endPage; i++) {
                pages.push(i);
            }
            return pages;
        },

        initializePagination()
        {
            this.currentPage=1,
            this.itemsPerPage= 100,
            this.totalRows=0
        },
        firstPage(){
            this.initializeData();
            this.currentPage = 1;
            this.fetchlists();
        },
        lastPage(totalRows,itemsPerPage){
            this.initializeData();
            this.currentPage = Math.ceil(totalRows / itemsPerPage);
            this.fetchlists();
        },
        nextPage() {
            this.initializeData();
            this.currentPage++;
            this.fetchlists();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.initializeData();
                this.currentPage--;
                this.fetchlists();
            }
        },
        gotoPage(page) {
            this.initializeData();
            this.currentPage = page;
            this.fetchlists();
        },
        initializeData()
        {
            this.showFilters= 9;
            this.isEditing=0;
            this.rIndex=-1;
            this.cIndex=-1;
            this.formData={}
        },

        convertToTitleCase(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        async fetchlists() {

            let dataToSend = {
                'order_column_name': this.key_name,
                'order_column_value': this.orderColumnValue,
                'primary_table':this.primary_table,
                'key_name':this.key_name
            }
            const response = await fetch(this.rootURL+'/fetch_filtered_data.php?page=' + this.currentPage,  {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend)
            }).then(response => response.json())
                .then(data => {
                    console.log('urlsku:'+this.urlsku);
                    this.listDetails = data.list_details;
                    this.listValues = data.list_values;
                    this.listValuesTotal = data.list_values_total;
                    this.totalRows = data.total_rows;
                    this.columnValues = data.column_values_row;
                    this.filters = data.filter_names;
                    const storedCheckedRows = localStorage.getItem('checkedRows');
                    if (storedCheckedRows) {
                        this.checkedRows = JSON.parse(storedCheckedRows);
                    }
                    this.isLoading=true;
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

        async saveEdit() {
            this.formData.table=this.primary_table
            this.formData.pr_key=this.key_name;
            try {
                const response = await fetch(this.rootURL+'/updatetablevalue.php', {
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
                    this.listValues[this.rIndex][this.columnValues[this.cIndex]] = this.formData.editedValue;
                }
                this.initializeData();
                console.log('Database updated successfully');
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },

        cancelEdit() {
            this.initializeData();
            this.fetchlists();
        },
        handleFiltersUpdated() {
            console.log('filters updated event received in parent component');
            this.initializeData();
            this.initializePagination();
            console.log(this.fetchlists());
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
                    column_values: this.columnValues,
                    table_name:this.primary_table
                };
                try {
                    const response =  fetch(this.rootURL+'/save_column_order_values.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    if (!response.ok) {
                        throw new Error('Failed to order columns');
                    }

                } catch (error) {
                    console.error('Error updating database:', error);
                }

            }
        }
    },
    template: `
    <nav class=" toolbar pim-padding">    
        <div class="selectbox"> <input type="checkbox" v-model="selectAllCheckbox" @change="selectAllPagesRow"><span v-if="itemNo >0">{{itemNo}} items selected </span> </div>
        <a class="icon-btn btn-col" title="Columns" @click="toggleColumnSelector"><i class="fa fa-columns" aria-hidden="true"></i></a>
          
        <a class="icon-btn show-filter" v-if="show_filter_button==true" @click="showHideFilter" title="Filter"><i class="fa fa-filter" aria-hidden="true"></i></a>
    </nav>
    </div>
  
    <div class="bg-light shadow right-slider-container animation-mode" :class="{ 'is-open': showFilter }" ref="filterContainer">
    
    <list-filters :primary_table="primary_table" :listDetails="listDetails" :filters="filters" :showFilters="showFilters" @filters-updated="handleFiltersUpdated"></list-filters>
    </div>
     
          
        
         
          <div class="overflow-container" ref="overflowContainer"  @mousedown="handleMouseDown"        @mousemove="handleMouseMove"        @mouseup="handleMouseUp">
          <table class="pimtable  display list-table">
            <thead>
              <tr>
                <th class="hidden">S.N</th>
                <th col="checkbox">
                <input type="checkbox" v-model="selectAllChecked[currentPage]" @change="selectAllRows(currentPage)"> </th>               </th>
                 
                 <th :col="colName" v-for="(colName, index) in columnValues" :key="index" 
                :draggable="true" @dragstart="handleDragStart(index)" 
                @dragover="handleDragOver(index)" @drop="handleDrop(index)" 
                :style="{ backgroundColor: draggedIndex === index ? 'lightblue' : 'inherit', minWidth: clacwidth(colName,['checkbox'],['description', 'specifications']) + 'px' }">
                
                 
                  <a v-if="dataTypeValue=='varchar'"  class="sorting-btn">
                        <template v-if="orderColumnValue=='ASC'"><span @click="updateFetchColumns(colName,'DESC')"><i class="fa fa-angle-down" ></i></span><div class="box-content" >{{getDataTypeValue(colName,'ASC')}}</div></template>
                        <template v-else><span @click="updateFetchColumns(colName,'ASC')"><i class="fa fa-angle-up" ></i></span><div class="box-content" >{{getDataTypeValue(colName,'DESC')}}</div></template>      
                  </a>
                  <a v-else class="sorting-btn">
                   <template v-if="orderColumnValue=='ASC'"><span @click="updateFetchColumns(colName,'DESC')"><i class="fa fa-angle-down" ></i></span><div class="box-content" >{{getDataTypeValue(colName,'ASC')}}</div></template>
                   <template v-else><span @click="updateFetchColumns(colName,'DESC')"><i class="fa fa-angle-down" ></i></span><div class="box-content" >{{getDataTypeValue(colName,'DESC')}}</div></template>  
                  </a>
                {{ convertToTitleCase(colName) }} &nbsp; <a @click="updateColumns(colName,false)"><i class="fa fa-close"></i></a>
                 </th>                
              </tr>
            </thead>
            <tbody>
            
              <tr v-for="(row,rowIndex) in listValues">
              <td class="hidden">{{rowIndex+1}}</td>
              <td>
                <input type="checkbox" :id="currentPage" :checked="checkedRows[row[key_name]]"  @change="toggleRowSelection(row[key_name])">
              </td>
               <template v-for="(colName,colIndex) in columnValues">
               <td  :col="colName">              
                <div v-if="rIndex==rowIndex && colIndex==cIndex">
                <input type="hidden" v-model="formData.sku" value="row[key_name]">
                <input type="hidden" v-model="formData.columnName" value="colName">
                <input type="hidden" v-model="formData.oldValue" value="row[colName]">
                <input id="editInput" type="text" v-model="formData.editedValue" value="row[colName]" @keydown.tab.prevent="saveEdit()" @mouseleave="saveEdit()" @keyup.enter="saveEdit()">
                </div>
                <div v-else>
                <template v-if="colName == key_name">
                 <a :href="getlistUrl(row[key_name])">{{ row[key_name] }} </a>
                </template>
                
                <template v-else-if="colName.includes('imag')">
                  <template v-if="row[colName]">
                  <a :href="row[colName]" target="_blank">
                  <img :src="row[colName]" :alt="row['list_title']">
                  </a>
                  </template>
                  <template v-else> <img src="/css/no-image.png?v=1" alt="no image"> </template>
                </template>
                <template v-else>
                
                <a class="editfield" @click="changeEditValue(rowIndex,colIndex,row[colName],row[colName],row[key_name],colName)">
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
            <div  class="bottombar">
           <div class="mt-3 row ">
                <div class="btn-group pagination-container col-md-4" role="group" aria-label="Pagination">
                
                <select v-model="currentPage" @change="changePage" class="page-dropdown hidden">
                    <template v-for="(value,index) in totalPages(totalRows,itemsPerPage)" :key="index" >
                    <template v-if="currentPage==index+1">                 
                    <option :value="index+1" selected>Page {{ index +1 }}</option>
                    </template>                   
                    <template v-else>
                    <option :value="index+1">Page {{ index +1 }}</option>
                    </template>                   
                </select>

                <a class="page-btn" @click="firstPage" :class="{ 'disabled': currentPage === 1 }" ><i class="fa fa-step-backward" aria-hidden="true"></i></a>
                <a class="page-btn" @click="prevPage" :class="{ 'disabled': currentPage === 1 }"><i class="fa fa-caret-left" aria-hidden="true"></i></a>
                <span v-if="this.currentPage>3">...</span>
                <a class="page-btn"  v-for="(page, index) in visiblePages(totalRows,itemsPerPage)"  :key="index" :class="{ 'active': currentPage == page }"  @click="gotoPage(page)">{{page}}</a>
                <span v-if="this.currentPage<totalPages(totalRows,itemsPerPage)-2">...</span>
                <a class="page-btn" @click="nextPage" :class="{ 'disabled': currentPage >= totalPages(totalRows,itemsPerPage) }" ><i class="fa fa-caret-right" aria-hidden="true"></i></a>
                <a class="page-btn" @click="lastPage(totalRows,itemsPerPage)" :class="{ 'disabled': currentPage >= totalPages(totalRows,itemsPerPage) }"><i class="fa fa-step-forward" aria-hidden="true"></i></a>
                


              </div>
              <div class="text-muted col-md-4 text-center p-2">
                {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ (currentPage - 1) * itemsPerPage + listValues.length }} / {{totalRows}} records
              </div>
              <div class="text-muted col-md-4 text-end">
                <a class="icon-btn btn-col"  title="Columns" @click="toggleColumnSelector"><i class="fa fa-columns" aria-hidden="true"></i></a>
                <a class="icon-btn" @click="exportToCSV" title="Export to CSV" :disabled="isExportDisabled"><i class="fa fa-download" aria-hidden="true"></i></a>
              </div>
              </div>
        </div>
        <div class="bg-light shadow right-slider-container animation-mode" :class="{ 'is-open': showColumnSelector }" >
            <div class="ui-widget-content">
              <div class="flex-row vcenter right-slider-header" tabindex="0"><span class="sub-heading">Columns</span></div>
                <div class="select-btn" v-for="(column, index) in columns" :key="index" @click="toggleCheckbox(column)" :class="{'selected': column.selected }">
                  <input type="checkbox" class="button-menu-item-checkbox hidden" v-model="column.selected"  @change="updateColumns(column.column_name,column.selected)">
                  <label> &nbsp; {{ column.column_name }}</label>
                </div>
       
            </div>
        </div>
      </div>
`,
};

const app = Vue.createApp({
    components: {
        List
    }
});

app.mount('#list');
app.component('list-filters', listFilters);
app.component('list-filter-form', listFilterForm);