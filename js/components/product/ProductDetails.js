import ProductFilters from './ProductFilters.js';
export default {
    components: {
        'product-filters': ProductFilters,
    },
    data() {
        return {
            channelAttribute:[],
            indexCheck:0,
            columns: [],
            attribute_values:[],
            productId:0,
            products:[],
            showAttribute:0,
            productDetails:[],
            productValues:[],
            showFilters:9,
            attributeNameSearch:'',
            currentPage: 1,
            itemsPerPage: 10,
            totalRows:0,
            columnValues:[],
            showAttFilter:1,
            indexVal:-1
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        nextPage() {

            this.currentPage++;
            alert(this.currentPage)
            this.fetchProducts();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchProducts();
            }
        },

        async fetchProducts() {
            const response = await fetch('fetch_product_details.php?page=' + this.currentPage, {
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

        getCurrentDate() {
            const today = new Date();
            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            return today.toLocaleDateString(undefined, options);
        },


        async searchProductFilters(attribtueName)
        {
            try {
                const response = await fetch('search_product_details.php?page=1', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        attributeName: this.attributeNameSearch,
                        filterCondition:this.products[0]['filter_condition']
                    }),
                });
                const data = await response.json();

                // Update the product data
                this.productValues = data;

            } catch (error) {
                console.error('Error fetching product:', error);
            }
        },

        async deleteProduct(product) {
            try {
                // Display a confirmation dialog
                const confirmed = window.confirm(`Are you sure you want to delete the product "${product.name}" and its linked filters?`);

                if (confirmed) {
                    const response = await fetch('delete_product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            productName: product.name,
                            productId: product.id
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        console.log('Product deleted successfully!');

                        var previousUrl = "/products/product.php";
                        window.location.href = previousUrl;

                    } else {
                        console.error('Error deleting product:', data.error);
                    }
                } else {
                    // User canceled, do nothing or provide feedback
                    console.log('Deletion canceled by the user.');
                }
            } catch (error) {
                console.error('Error deleting product:', error);
            }
        },
        controlFilters(filterValue){
            this.showFilters=filterValue;
        },

        //filters functions from here
        handleChangeAttribute(index) {
            this.attribute_values = [];
            this.channelAttribute[index].filter_type = 'IS NOT NULL';
            this.channelAttribute[index].attribute_condition = '';
            // Get the selected value from the attribute dropdown
            const selectedValue = this.channelAttribute[index].attribute;

            // Split the selected value based on the comma
            const [att_name, d_type] = selectedValue.split(',');

            // Update the attribute_name and data_type properties separately
            this.channelAttribute[index].attribute_name = att_name;
            this.channelAttribute[index].data_type = d_type;
            console.log(this.channelAttribute)
        },
        async getAttributeValue(index,attributeName,attributeCondition){
            try {
                if(attributeCondition.length>2) {
                    // Make an AJAX request to your PHP file to fetch attributes
                    const response = await fetch('../fetch_attribute_values.php?attribute_name=' + attributeName + '&attribute_condition=' + attributeCondition);

                    // Parse the JSON response
                    const data = await response.json();
                    this.attribute_values = data;
                    this.indexCheck = index;
                }

            } catch (error) {
                console.error('Error fetching values:', error);
            }
        },

        selectAutocompleteValue(index, selectedValue) {
            this.channelAttribute[index].attribute_condition = selectedValue;
            this.attribute_values = [];
        },

        addChannelCondition(op_value,condition_type,previous_row) {
            this.channelAttribute.push({
                id:0,
                attribute_name:'',
                data_type: '',
                attribute:'',
                attribute_condition:'IS NOT NULL',
                operator:op_value,
                condition_type:condition_type,
                previous_row:previous_row
            });
            this.showAttribute = 1;
            this.showAttFilter = 0;
        },
        async deleteFilter(productDetId,productId,indexVal) {

            try {
                if(indexVal ==0)
                {
                    this.indexVal = productDetId;
                }
                else
                {
                    this.indexVal = -1;
                }
                // Display a confirmation dialog
                const confirmed = window.confirm(`Are you sure you want to remove this filter?`);

                if (confirmed) {
                    const response = await fetch('../products/delete_product_filter.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            productId: productId,
                            productDetId: productDetId,
                            indexVal:this.indexVal
                        }),
                    });
                    const data = await response.json();
                    if (data.success)
                    {
                        console.log('Filters deleted successfully!');
                        location.reload();
                    }
                    else
                    {
                        console.error('Error deleting filter:', data.error);
                    }
                } else {
                    console.log('Deletion canceled by the user.');
                }
            } catch (error) {
                console.error('Error deleting channel:', error);
            }
        },
        async submitForm() {
            try {
                const response = await fetch('save_product_filter.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        attribute:this.channelAttribute
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                } else {
                    console.error('Error saving filters:', data.error);
                }

            } catch (error) {
                console.error('Error saving channel:', error);
            }
        },
        getEmptyPrinted(value)
        {
            if(value == 'IS NOT NULL')
            {
                return 'IS NOT EMPTY';
            }
            return value;
        },

    },
    template: `<div style="padding-right: 48px;padding-bottom: 0px;padding-left: 48px;">
    <div class="row">
        <div :class="'col-md-' + showFilters">
        <div class="row">
           <div class="col-md-4">
                <div class="d-flex align-items-center" v-if="products.length>0">
                    <a href="/products/product.php" class="btn btn-light" data-toggle="tooltip" title="Back to Lists">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <span class="pl-2" > {{products[0]['name']}}</span>
                </div>
            </div>
 
            <div class="col-md-8 d-flex justify-content-end">
                <span class="pl-2" > Created By MS Feb12, 2024</span>
            </div>
        </div>    
        <div class="row">
        <div class="col-md-4">
            <div class="input-group" >
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control"  v-model="attributeNameSearch" placeholder="Search by sku" @keyup="searchProductFilters(productName)">
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="ml-16">
            <button class="btn btn-light" @click="controlFilters(12)"  v-if="showFilters==9" data-test-id="">
                <span class="mr-1"><i class="fas fa-filter"></i></span>
                <span> Hide Filters </span>
            </button>
             <button class="btn btn-light" @click="controlFilters(9)" v-if="showFilters==12" data-test-id="">
                <span class="mr-1"><i class="fas fa-filter"></i></span>
                <span> Show Filters </span>
            </button>
            </div>
         </div>

        <div class="col-md-5 d-flex justify-content-end">
            <div class="mt-8 d-flex justify-content-end">
<!--                <button class="btn btn-primary mr-3" data-toggle="tooltip" title="Duplicate">-->
<!--                    <i class="fas fa-copy"></i>-->
<!--                </button>-->
                
                <button @click="deleteProduct(products[0])" class="btn btn-danger" data-toggle="tooltip" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
</div>
  
<table class="table mt-3">
                <thead>
                    <tr>
                   
                     <template v-for="(cval,cindex) in columnValues">
                       <th scope="col">{{cval}}</th>
                     </template>                       
                        <th scope="col">THUMBNAIL </th>
                        <th scope="col">LABEL</th>
                        <th scope="col">CATEGORIES</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">CREATED</th>
                        <th scope="col">LAST MODIFIED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(values,index) in productValues" :key="index">                       
                        <td scope="col" v-for="(cval,index) in columnValues">{{values[cval]}}</td>
                        <td scope="col">-</td>
                        <td scope="col">-</td>
                        <td scope="col">test category</td>
                        <td scope="col">Active</td>
                        <td scope="col">{{getCurrentDate()}}</td>
                        <td scope="col">{{getCurrentDate()}}</td>
                    </tr>
                </tbody>
</table>
<div class="mt-3">
<div class="btn-group" role="group" aria-label="Pagination">
    <button class="btn btn-primary" @click="prevPage" :disabled="currentPage === 1">Prev</button>
    <button class="btn btn-success ml-2 mr-2">Page {{ currentPage }}</button>
    <button class="btn btn-primary" @click="nextPage()" :disabled="productValues.length < itemsPerPage">Next</button>
  </div>
<div class="text-muted mt-2">
    Showing {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ (currentPage - 1) * itemsPerPage + productValues.length }} of {{totalRows}} records
  </div>
</div>
       
</div> 

<product-filters :productDetails=productDetails :showFilters=showFilters></product-filters>
</div>
</div>`,
};
