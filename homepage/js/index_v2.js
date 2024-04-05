import list from '../../crud/list.js';
import ProductFilters from '../../products/js/ProductFilters.js?v=2';
import ProductFilterForm from '../../products/js/ProductFilterForm.js?v=2';

const app = Vue.createApp({
    props: {
        urlsku: {
            type: String,
        },
    },
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
            orderColumnName:'sku',
            orderColumnValue:'ASC'
        };
    },
    mounted() {
        this.clearCheckedState()
        this.fetchUserColumns();
        this.fetchProducts();
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
        getDataTypeValue(columnName,columnValue) {
            switch (columnName) {
                case 'carat':
                case 'purchase_cost_aud':
                case 'purchase_cost_usd':
                case 'manufacturing_cost_aud':
                case 'wholesale_aud':
                case 'wholesale_usd':
                case 'stone_price_wholesale_aud':
                case 'retail_aud':
                case 'retail_usd':
                case 'stone_price_retail_aud':
                case 'master_qty':
                case 'warehouse_qty':
                case 'mdqty':
                case 'psqty':
                case 'usdqty':
                case 'allocated_qty':
                case 'shopify_qty':
                case 'centre_stone_qty':
                case 'sales_percentage':
                case 'lot_number':
                case 'client_jim309_qty':
                case 'client_jim077_qty':
                case 'client_jim077_price':
                case 'product_id':
                case 'variant_id':
                    if(columnValue=='DESC'){
                        return 'High To Low'
                    }else{
                        return 'Low To High'
                    }
                case 'modified_date':
                    if(columnValue=='DESC'){
                        return 'A-Z'
                    }else{
                        return 'Z-A'
                    }
                case 'client_tags':
                    if(columnValue=='DESC'){
                        return 'A-Z'
                    }else{
                        return 'Z-A'
                    }
                default:
                    if(columnValue=='DESC'){
                        return 'A-Z'
                    }else{
                        return 'Z-A'
                    }
            }
        },
        updateFetchColumns(column_name,column_value){
            this.orderColumnName = column_name;
            this.orderColumnValue = column_value;
            this.fetchProducts();
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
                this.exportRows.push(this.productValues.find(row => row.sku === sku));
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
            const endIndex = Math.min(startIndex + this.pageSize, this.productValues.length);

            for (let i = startIndex; i < endIndex; i++) {
                const sku = this.productValues[i]['sku'];
                this.checkedRows[sku] = this.selectAllChecked[current_page];

                if (this.selectAllChecked[current_page]) {
                    // If Select All is checked, add the row to exportRows
                    if (!this.exportRows.some(row => row['sku'] === sku)) {
                        this.exportRows.push(this.productValues[i]);
                    }
                } else {
                    // If Select All is unchecked, remove the row from exportRows (if exists)
                    const exportIndex = this.exportRows.findIndex(row => row['sku'] === sku);
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
                    const sku = this.productValuesTotal[i]['sku'];
                    this.checkedRows[sku] = true;
                    this.exportRows.push(this.productValuesTotal[i]);
                }

                this.itemNo=this.exportRows.length;
            }
            else{
                this.clearCheckedState();
            }

        },
        exportToCSV() {
            if (this.exportRows.length === 0) {
                alert("Please Select Products To Export")
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
                'selectedStatus': selectedStatus
            }

            try {
                fetch('./users/save_user_columns.php', {
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
                            this.fetchProducts();
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
            try {
                const response = await fetch('./users/fetch_columns_user_wise.php');
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
        getProductUrl(sku){
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
            this.fetchProducts();
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
            this.fetchProducts();
        },
        lastPage(totalRows,itemsPerPage){
            this.initializeData();
            this.currentPage = Math.ceil(totalRows / itemsPerPage);
            this.fetchProducts();
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
        gotoPage(page) {
            this.initializeData();
            this.currentPage = page;
            this.fetchProducts();
        },
        initializeData()
        {
            this.showFilters= 9;
            this.isEditing=0;
            this.rIndex=-1;
            this.cIndex=-1;
            this.formData={}
        },

        async  controlFilters() {

            const dataToSend = {
                filter_no: this.filter_no
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


            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        convertToTitleCase(str) {
            return str.toLowerCase().split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        },
        async fetchProducts() {

            let dataToSend = {
                'order_column_name': this.orderColumnName,
                'order_column_value': this.orderColumnValue
            }
            const response = await fetch('./fetch_filtered_data.php?page=' + this.currentPage,  {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend)
            }).then(response => response.json())
                .then(data => {
                    console.log('urlsku:'+this.urlsku);
                    this.productDetails = data.product_details;
                    this.productValues = data.product_values;
                    this.productValuesTotal = data.product_values_total;
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

        cancelEdit() {
            this.initializeData();
            this.fetchProducts();
        },
        handleFiltersUpdated() {
            this.initializeData();
            this.initializePagination();
            console.log(this.fetchProducts());
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

                } catch (error) {
                    console.error('Error updating database:', error);
                }

            }
        },
        handleUpdatedData(value)
        {
            console.log(value)
            this.orderColumnName=value.column_name
            this.orderColumnValue=value.column_value
            this.currentPage=value.current_page
            this.fetchProducts();
        }
    },
    template: `
  
   
    <div class="bg-light shadow right-slider-container animation-mode" :class="{ 'is-open': showFilter }" ref="filterContainer">   
     <product-filters :productDetails="productDetails" :filters="filters" :showFilters="showFilters" @filters-updated="handleFiltersUpdated"></product-filters>
    </div>
     <d-list :table="pim"></d-list>

     
        
`,
});
app.mount('#index');
app.component('d-list', d-list);
app.component('product-filters', ProductFilters);
app.component('product-filter-form', ProductFilterForm);
