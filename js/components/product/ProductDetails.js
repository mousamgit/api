export default {
    data() {
        return {
            channelAttribute:[],
            indexCheck:0,
            columns: [],
            attribute_values:[],
            productId:0,
            products:[],
            productDetails:[],
            productValues:[]
        };
    },
    mounted() {
        this.channelAttribute.push({
            id:0,
            attribute_name:'',
            data_type: '',
            attribute:'',
            attribute_condition:'IS NOT NULL'
        });
        this.fetchAllColumns();
        this.fetchProducts();
    },
    methods: {
        async fetchAllColumns() {
            try {
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch('../channels/fetch_all_pim_columns.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the attributes data
                this.columns = data;

                console.log(this.columns,'column_list');
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
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
        // Method to handle selecting a value from autocomplete suggestions
        selectAutocompleteValue(index, selectedValue) {
            this.channelAttribute[index].attribute_condition = selectedValue;
            this.attribute_values = [];
        },
        addChannelCondition(op_value) {
            this.channelAttribute.push({
                id:0,
                attribute_name:'',
                data_type: '',
                attribute:'',
                attribute_condition:'IS NOT NULL',
                operator:op_value
            });
        },
        async fetchProducts() {
                fetch('fetch_product_details.php?page=' + this.currentPage, {
                    method: 'POST', // or 'GET' depending on your server setup
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        this.products = data.products;
                        this.productDetails = data.product_details;
                        this.productValues = data.product_values;
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
        async fetchChannelAttributeFilter(channel_id=2){
            try {
                const response = await fetch(`get-attribute_filter_channelwise.php?channelId=${channel_id}`);
                const data = await response.json();

                this.channelIdGlobal = channel_id;

                if(data.length>0)
                {
                    this.channelAttribute=data;
                }
                console.log('hello',this.channelAttribute)

            } catch (error) {
                console.error('Error fetching attribute filter:', error);
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
                    console.error('Error saving channel:', data.error);
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
        }


    },
    template: `
    <div style="padding-right: 48px;padding-bottom: 0px;padding-left: 48px;">
    <div class="row">
        <div class="col-md-9 ">
        <div class="row">
           <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <a href="/products/product.php" class="btn btn-light" data-toggle="tooltip" title="Back to Lists">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <span class="pl-2" > Pink Kimberley Channel</span>
                </div>
            </div>
 
            <div class="col-md-8 d-flex justify-content-end">
                <span class="pl-2" > Created By MS Feb1, 2024</span>
            </div>
        </div>    
        <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by sku or label">
            </div>
        </div>

        <div class="col-md-3">
            <div class="ml-16">
            <button class="btn btn-light" data-test-id="">
                <span class="mr-1"><i class="fas fa-filter"></i></span>
                <span>  Filters </span>
            </button>
            </div>
         </div>

        <div class="col-md-5 d-flex justify-content-end">
            <div class="mt-8 d-flex justify-content-end">
                <button class="btn btn-primary mr-3" data-toggle="tooltip" title="Duplicate">
                    <i class="fas fa-copy"></i>
                </button>
                
                <button class="btn btn-danger" data-toggle="tooltip" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
   
            <!-- Bootstrap Table -->
        <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">SKU</th>
                        <th scope="col">THUMBNAIL</th>
                        <th scope="col">LABEL</th>
                        <th scope="col">CATEGORIES</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">CREATED</th>
                        <th scope="col">LAST MODIFIED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(values,index) in productValues" :key="index">
                        <td scope="col">{{values.sku}}</td>
                        <td scope="col">-</td>
                        <td scope="col">-</td>
                        <td scope="col">test category</td>
                        <td scope="col">Active</td>
                        <td scope="col">{{getCurrentDate()}}</td>
                        <td scope="col">{{getCurrentDate()}}</td>
                    </tr>
                    <!-- Add other rows as needed -->
                </tbody>
            </table>
        
        </div> 
        <div class="col-md-3 bg-light" style="min-height: 100vh">
            <div class="right-menu filters background-secondary-bg">
                <div class="flex-row vcenter filter-header">
                  
                    <span class="sub-heading" >FILTERS</span>
                </div>
                <hr>
                <div class="filter-column">
                    <div class="flex-col filters-container flex-grow-1">
                        <div class="filters-content">
                            <!-- Bootstrap Card Component -->
                            <div class="card">
                                <div class="card-body">
                                   
                                    <div class="form-group selected-filters">
                                   
                                      <div class="row" v-for="(productDet,index) in productDetails">
                                        <div class="filter-clauses">
                                            <div class="clause">
                                                <div class="filter flex-row border-default card position-relative">
                                                    <div class="flex-grow-1">
                                                        <div style="margin: 3px;">
                                                            <span class="alternative emphasis filter-field ">{{productDet.attribute_name}} </span> <br>
                                                            <span class="text-default mt-5" v-if="productDet.filter_type !=''">&nbsp;{{getEmptyPrinted(productDet.filter_type)}}</span>
                                                            <span class="text-default" v-if="productDet.range_to !=''">&nbsp; from {{productDet.range_from}} To {{productDet.range_to}}</span>
                                                            <span class="text-default" v-if="productDet.attribute_condition !='' && productDet.attribute_condition != productDet.filter_type">&nbsp; {{getEmptyPrinted(productDet.attribute_condition)}} </span>
                                                        </div>
                                                        <div class="value text-ellipsis"  style="margin: 3px;">  </div>
                                                    </div>
                                                    <div class="delete-icon position-absolute top-0 end-0" data-test-id="delete">
                                                        <a href="" class="btn btn-danger">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       </div>
                                        <form @submit.prevent="submitForm">
                                        <div class="row">
                                            <!-- Bootstrap Form Group Component -->
                                            <div class="form-group filter-clauses" style="max-height: 90vh; overflow-y: auto;">
                                                <fieldset>                                                  
                                                        <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition">
                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                            <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
                                                            <div class="mb-3"> 
                                                            <select v-model="cAttribute.attribute" class="form-control" @change="handleChangeAttribute(index)" required>
                                                            <option v-for="column in columns" :key="column.column_name" :value="column.column_name + ',' + column.data_type">
                                                            {{ column.column_name }}
                                                            </option>
                                                            </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" v-if="cAttribute.attribute!=''">
                                                       
                                                        <div class="mb-3">
                                                      
                                                         <template v-if="cAttribute.data_type == 'varchar'">
                                                         <select v-model="cAttribute.filter_type" id="filter-type" class="form-control" >
                                                            <option value="includes">Includes</option>
                                                            <option value="=">equals</option>
                                                            <option value="IS NOT NULL">Is Not Empty</option>
                                                         </select>
                                                        <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == 'includes'" >
                                                         <input type="text" v-model="cAttribute.attribute_condition" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_condition)" class="form-control" placeholder="Enter condition" required>
                                                         <ul v-if="index == indexCheck && cAttribute.filter_type != 'includes'" class="autocomplete-suggestions">
                                                            <li v-for="(value, vindex) in attribute_values" :key="vindex" @click="selectAutocompleteValue(index, value)">
                                                             {{ value }}
                                                            </li>
                                                          </ul>
                                                        </template>
                                                        
                                                        </template>
                                                        
                                                        <template v-else>
                                                        <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                            <option value="between">Range</option>
                                                            <option value="IS NOT NULL">IS NOT EMPTY</option>
                                                         </select>
                                                        <template v-if="cAttribute.filter_type == 'between'">
                                                            <div class="row">
                                                            <div class="col-md-6">
                                                            <input type="text" v-model="cAttribute.rangeFrom" class="form-control" placeholder="From" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                            <input type="text" v-model="cAttribute.rangeTo" class="form-control" placeholder="To" required>
                                                            </div>
                                                            </div>
                                                            </div>
                                                         </template>
                                                        
                                                        </div>
                                                    <div class="submit-form" v-if="cAttribute.attribute!=''">                                                 
                                                    <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
                                                    </div>
                                               </div>
                                                <div class="operators">
                                                            <a  class="btn btn-link" @click="addChannelCondition('AND')" data-test-id="and" v-if="index === channelAttribute.length - 1">
                                                                <strong>AND</strong>
                                                            </a>
                                                            <a class="btn btn-link" @click="addChannelCondition('OR')" data-test-id="or" v-if="index === channelAttribute.length - 1">
                                                                <strong>OR</strong>
                                                            </a>
                                                </div>
                                                 <div class="submit-form">
                                                           
                                                </div>
                                            </div>
                                                       
                                            
                                            </fieldset>  
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
</div>
  `,
};
