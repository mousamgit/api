export default {
    props: ['productDetails', 'showFilters'],
    data() {
        return {
            channelAttribute: [],
            indexCheck: 0,
            columns: [],
            attribute_values: [],
            showAttribute: 0,
            showAttFilter: 1,
            indexVal: -1,
            showAttributeMid: 0,
            op_show_value: 'AND',
            selectedValues:[],
            showManualValidationMessage:0,
            filter_name:'',
            showInput:0,
            editForm:-1,
            filters:[]
        };
    },
    mounted() {
        this.fetchAllColumns();
    },
    methods: {
        handleInput(){
            this.showInput=1;
        },
        async updateStatus(value) {
            try {

                const response = await fetch('update_filter_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        value: value,
                        filter_name:this.filter_name,
                        product_details:this.productDetails
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    this.initializeData()
                    this.$emit('filters-updated');
                    this.showInput =0;
                    this.filter_name ='';
                } else {
                    console.error('Error updating status:', data.error);
                }
            } catch (error) {
                console.error('Error updating status:', error);
            }
        },
        //v2
        editFilter(productDet,index)
        {
            let attribute_condition_value = productDet.attribute_condition.slice(1, -1).split(',');
            attribute_condition_value = attribute_condition_value.map(function(value) {
                value = value.trim();

                // Remove leading and trailing double quotes
                if (value.startsWith('"')) {
                    value = value.substring(1);
                }
                if (value.endsWith('"')) {
                    value = value.substring(0, value.length - 1);
                }

                return value;
            });
            this.selectedValues=[]
            // Reset the selectedValues array
            this.selectedValues = [];

            attribute_condition_value.forEach(function(value) {
                this.selectedValues.push(value);
            }, this);

            console.log(this.selectedValues);
            this.showAttFilter =0;
            this.editForm=index;
            this.channelAttribute = [{
                id: productDet.id,
                attribute_name: productDet.attribute_name,
                data_type: productDet.data_type_value,
                filter_type: productDet.filter_type,
                attribute: productDet.attribute_name +','+productDet.data_type_value,
                attribute_condition: productDet.attribute_condition,
                attribute_current: '',
                rangeFrom: productDet.range_from,
                rangeTo:productDet.range_to,
                operator: productDet.op_value,
                condition_type: 'abc',
                previous_row: [],
                type:'edit'
            }];

        },
        updateSelectedValues(index) {
            this.channelAttribute[index].attribute_condition = "("+this.selectedValues.map(value => `"${value}"`).join(',')+")";
        },
        nextPage() {
            this.currentPage++;
            this.fetchProducts();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchProducts();
            }
        },

        getCurrentDate() {
            const today = new Date();
            const options = {year: 'numeric', month: 'numeric', day: 'numeric'};
            return today.toLocaleDateString(undefined, options);
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

        async getAttributeValue(index, attributeName, attributeCondition) {
            try {
                if (attributeCondition.length > 0) {
                    // Make an AJAX request to  PHP file to fetch attributes
                    const response = await fetch('./fetch_attribute_values.php?attribute_name=' + attributeName + '&attribute_condition=' + attributeCondition);

                    // Parse the JSON response
                    const data = await response.json();
                    this.attribute_values = data;
                    this.showManualValidationMessage=0;
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

        addChannelCondition(op_value, condition_type, previous_row) {
            this.op_show_value = op_value;
            this.showAttributeMid = previous_row['id'];
            this.selectedValues=[];
            this.channelAttribute = [{
                id: 0,
                attribute_name: '',
                data_type: '',
                attribute: '',
                attribute_condition: 'IS NOT NULL',
                operator: op_value,
                condition_type: condition_type,
                previous_row: previous_row,
                type:'insert'

            }];
            this.showAttribute = 1;
            this.showAttFilter = 1;

        },
        refreshAttributeAgain() {
            this.initializeData()
            this.$emit('filters-updated');
            this.showFilter=true;
        },
        async deleteFilter(productDetId, productId, indexVal) {

            try {
                if (indexVal == 0) {
                    this.indexVal = productDetId;
                } else {
                    this.indexVal = -1;
                }

                // Display a confirmation dialog
                const confirmed = window.confirm(`Are you sure you want to remove this filter?`);

                if (confirmed) {
                    const response = await fetch('./products/delete_product_filter.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            productId: productId,
                            productDetId: productDetId,
                            indexVal: this.indexVal
                        }),
                    });
                    const data = await response.json();
                    if (data.success) {
                        console.log('filters deleted successfully!');
                        this.initializeData()
                        this.$emit('filters-updated');
                        this.showFilter=true;
                    } else {
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
                console.log(this.channelAttribute[0].filter_type)
                if ((this.channelAttribute[0].attribute_condition.trim() == '' || this.channelAttribute[0].attribute_condition.trim() == '()') && (!(this.channelAttribute[0].filter_type == 'IS NOT NULL' || this.channelAttribute[0].filter_type == 'IS NULL')) && (this.channelAttribute[0].filter_type != 'between')){
                    this.showManualValidationMessage=1
                    this.channelAttribute[0].attribute_condition='';
                } else {
                    this.showManualValidationMessage=0
                    const response = await fetch('products/save_product_filter.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            attribute: this.channelAttribute
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.initializeData()
                        this.$emit('filters-updated');
                    } else {
                        console.error('Error saving filters:', data.error);
                    }
                }


            } catch (error) {
                console.error('Error saving channel:', error);
            }
        },
        getEmptyPrinted(value) {
            if (value == 'IS NOT NULL') {
                return 'IS NOT EMPTY';
            }
            else if(value == 'IS NULL')
            {
                return 'IS EMPTY';
            }
            return value;
        },
        async fetchAllColumns() {
            try {
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch('./channels/fetch_all_pim_columns.php');
                // Parse the JSON response
                const data = await response.json();
                // Update the attributes data
                this.columns = data;

            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },

        initializeData() {
            this.channelAttribute = [],
                this.indexCheck = 0,
                this.columns = [],
                this.attribute_values = [],
                this.showAttribute = 0,
                this.showAttFilter = 1,
                this.indexVal = -1,
                this.showAttributeMid = 0,
                this.op_show_value = 'AND'
            this.fetchAllColumns();
        }


    },
    template: `
    <div class="right-menu filters background-secondary-bg">
        <div class="flex-row vcenter filter-header">
            <span class="sub-heading">FILTERS</span>
        </div>
        
        <div class="card" v-if="productDetails.length==0 && channelAttribute.length==0">


                        <!-- Left column for Attributes -->
                        <div>
                            New Condition
                        </div>

                        <a class="position-absolute add-icon" @click="addChannelCondition('AND','normal',[])">
                                <i class="fa fa-plus"></i>
                        </a>
                       
                            
        </div>
        <div class="card" v-if="productDetails.length==0 && channelAttribute.length==0">
         <select class="btn" v-model="filter_no" @change="controlFilters">
                            <option value="0"  selected><a class="btn" >All Product   <i class="fa-solid fa-caret-down"></i></a> </option>
                            <template v-for="(fvalue, fkey) in filters">
                              <option :value="fvalue.id"><a class="btn" >{{fvalue['filter_name']}}   </a> </option>
                            </template>
                        </select>
                            
        </div>

                            <div class="form-group selected-filters">
                                <div v-for="(productDet,index) in productDetails" class="filter-condition">
                                    <span class="" v-if="showAttFilter==1">
                                        <span v-if="productDet.op_value == 'OR' && productDet.id != productDetails[0].id">
                                            <a class="btn white-btn" @click="addChannelCondition('AND','middle',productDetails[index-1])" data-test-id="and">
                                                <strong>AND</strong>
                                            </a>
                                        </span>
                                        <div v-if="productDet.op_value== 'OR' && productDet.id != productDetails[0].id && showAttributeMid==productDetails[index-1].id">
                                           
                                            <form @submit.prevent="submitForm">
                                                <span>----- {{op_show_value}} -------</span> 
                                                <div class="row">
                                                    <!-- Bootstrap Form Group Component -->
                                                    <div class="form-group filter-clauses" >
                                                        <fieldset>
                                                            <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12" v-if="showAttribute==1">
                                                                        <!-- <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>-->
                                                                        <div class="col-md-12 position-relative">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
                                                                                <label class="delete-icon" style=" position: absolute;top: -10px;  right: 0;">
                                                                                    <a @click="refreshAttributeAgain">
                                                                                        <i class="btn btn-danger fas fa-trash-alt"></i>
                                                                                    </a>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <select v-model="cAttribute.attribute" class="form-control" @change="handleChangeAttribute(index)" required>
                                                                                <option v-for="column in columns" :key="column.column_name" :value="column.column_name + ',' + column.data_type">
                                                                                    {{ column.column_name }}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-12" v-if="cAttribute.attribute != ''">
                                                                        <div class="mb-3">
                                                                            <template v-if="cAttribute.data_type == 'varchar'">
                                                                                <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                                    <option value="includes">includes</option>
                                                                                    <option value="dont_includes">doesn't include</option>
                                                                                    <option value="=">equal to</option>
                                                                                    <option value="!=">not equal to</option>
                                                                                    <option value="IS NOT NULL">is not empty</option>
                                                                                    <option value="IS NULL">is empty</option>
                                                                                </select>
                                                                                <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">
                                                                                     <input type="text" v-model="cAttribute.attribute_condition" class="form-control hidden" readonly required>
                                                                                     <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
                                                                                     <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition" >
                                                                                     <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                                       <li v-for="(value, vindex) in attribute_values" :key="vindex" >
                                                                                          <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
                                                                                          <label :for="'checkbox_' + vindex">{{ value }}</label>
                                                                                        </li>    
                                                                                     </ul>
                                                                                </template>
                                                                                <template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">
                                                                                     <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    
                                                                                </template>
                                                                            </template>
                                                                            <template v-if="cAttribute.data_type != 'varchar'">
                                                                                <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                                    <option value="=">equal to</option>
                                                                                    <option value="!=">not equal to</option>
                                                                                    <option value=">">is greater than</option>
                                                                                    <option value="<">is less than</option>
                                                                                    <option value="between">range</option>
                                                                                    <option value="IS NOT NULL">is not empty</option>
                                                                                    <option value="IS NULL">is empty</option>
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
                                                                        <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')">
                                                                            <input type="text" v-model="cAttribute.attribute_condition"  class="form-control hidden" readonly required>
                                                                            <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
                                                                            <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition" >
                                                                            <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                                <li v-for="(value, vindex) in attribute_values" :key="vindex" >
                                                                                          <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
                                                                                          <label :for="'checkbox_' + vindex">{{ value }}</label>
                                                                                </li> 
                                                                            </ul>
                                                                        </template>
                                                                        <template v-else-if="cAttribute.filter_type == '>' || cAttribute.filter_type == '<'">
                                                                          <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    
                                                                        </template>
                                                                    </div>
                                                                    <div class="submit-form" v-if="cAttribute.attribute!=''">
                                                                        <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
                                                                    </div>
                                                                </div>
                                                                <div class="submit-form">
                                                                </div>
                                                            </div>
                                                            <div class="operators" v-if="productDetails.length>0 && channelAttribute.length==0">
                                                                <a class="btn white-btn" @click="addChannelCondition('AND','normal',productDetails[productDetails.length - 1])" data-test-id="and">
                                                                    <strong>AND</strong>
                                                                </a>
                                                                <a class="btn white-btn" @click="addChannelCondition('OR','group',productDetails[productDetails.length - 1])" data-test-id="or">
                                                                    <strong>OR</strong>
                                                                </a>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <center v-if="productDet.op_value == 'OR'"><span class="value text-ellipsis" v-if="(productDet.attribute_name !='' && index !=0)">---------- {{productDet.op_value}} ----------</span>
                                    </span></center>
                                    <div class="filter-clauses card position-relative" v-if="showAttFilter==1" @click="editFilter(productDet,index)">
                                                <div class="flex-grow-1">
                                                    
                                                        <span class="alternative emphasis filter-field ">{{productDet.attribute_name}} </span> 
                                                        <span class="text-default mt-5" v-if="productDet.filter_type !=''">&nbsp;{{ getEmptyPrinted(productDet.filter_type) }}</span>
                                                        <span class="text-default" v-if="productDet.range_to !=''">&nbsp; {{productDet.range_from}} to {{productDet.range_to}}</span>
                                                        <span class="text-default" v-if="productDet.attribute_condition !='' && productDet.attribute_condition != productDet.filter_type">&nbsp; {{getEmptyPrinted(productDet.attribute_condition)}} </span>
                                                    
                                                </div>
                                                <div class="delete-icon position-absolute end-0" data-test-id="delete">
                                                    <a @click="deleteFilter(productDet.id,productDet.product_id,index)">
                                                        <i class="fa fa-times animation-mode" aria-hidden="true"></i>
                                                    </a>
                                                </div>
            
                                    </div>
                                    <div class="editForm" v-if="showAttFilter==0 && editForm===index">
                                    <form @submit.prevent="submitForm">
                                        <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition card">
                                            <div>
                                                <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
                                                <label class="delete-icon position-absolute top-0 end-0">
                                                    <a @click="refreshAttributeAgain">
                                                        <i class="fa fa-times animation-mode" aria-hidden="true"></i>
                                                    </a>
                                                </label>
                                                <select class="form-control" @change="handleChangeAttribute(index)" required="">
                                                    <template v-for="column in columns">
                                                        <template v-if="column.column_name == productDet.attribute_name">
                                                            <option :value="column.column_name+ ',' +column.data_type" selected>{{column.column_name}}</option>
                                                        </template>
                                                        <template v-else>
                                                            <option :value="column.column_name+ ',' +column.data_type">{{column.column_name}}</option>
                                                        </template>
                                                    </template>
                                                </select>
                                                <div>
                                                    <div class="mb-3">
                                                        <template v-if="cAttribute.data_type == 'varchar'">
                                                            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                <option value="includes" :selected="productDet.filter_type === 'includes'">includes</option>
                                                                <option value="dont_includes" :selected="productDet.filter_type === 'dont_includes'">doesn't include</option>
                                                                <option value="=" :selected="productDet.filter_type === '='">equal to</option>
                                                                <option value="!=" :selected="productDet.filter_type === '!='">not equal to</option>
                                                                <option value="IS NOT NULL" :selected="productDet.filter_type === 'IS NOT NULL'">is not empty</option>
                                                                <option value="IS NULL" :selected="productDet.filter_type === 'IS NULL'">is empty</option>
                                                            </select>
                                                            <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" readonly required>
                                                                <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
                                                                <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
                                                                <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                    <li v-for="(value, vindex) in attribute_values" :key="vindex">
                                                                        <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)" :checked="selectedValues.includes(value)">
                                                                        <label :for="'checkbox_' + vindex">{{ value }} </label>
                                                                    </li>
                                                                </ul>
                                                            </template>
                                                            <template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" required>
                                                            </template>
                                                        </template>
                                                        <template v-if="cAttribute.data_type != 'varchar'">
                                                            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                <option value="=">equal to</option>
                                                                <option value="!=">not equal to</option>
                                                                <option value=">">is greater than</option>
                                                                <option value="<">is less than</option>
                                                                <option value="between">range</option>
                                                                <option value="IS NOT NULL">is not empty</option>
                                                                <option value="IS NULL">is empty</option>
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
                                                            </template>
                                                            <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" readonly required>
                                                                <span v-if="showManualValidationMessage==1" class="alert-danger">Search and Tick Condition below</span>
                                                                <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
                                                                <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                    <li v-for="(value, vindex) in attribute_values" :key="vindex">
                                                                        <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)" :checked="selectedValues.includes(value)">
                                                                        <label :for="'checkbox_' + vindex">{{ value }}</label>
                                                                    </li>
                                                                </ul>
                                                            </template>
                                                            <template v-else-if="cAttribute.filter_type == '>' || cAttribute.filter_type == '<'">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" required>
                                                            </template>
                                                    </div>
                                                    <div class="submit-form" v-if="cAttribute.attribute!=''">
                                                        <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
                                                    </div>
                                                </div>
                                    </form>
                                </div>
                                </div>
                                <div class="form-group filter-clauses">

                                            <fieldset v-if="(productDetails.length>0 && (showAttributeMid == productDetails[productDetails.length-1].id)) || (productDetails.length==0)">
                                                <form @submit.prevent="submitForm">
                                                <center class="hidden"><span v-if="productDetails.length>0">---------- {{op_show_value}} ----------</span></center>
                                                    <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition card">
                                                        
                                                            <div  v-if="showAttribute==1">
                                                                        <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
                                                                        <label class="delete-icon position-absolute top-0 end-0" >
                                                                            <a @click="refreshAttributeAgain">
                                                                                <i class="fa fa-times animation-mode" aria-hidden="true"></i>
                                                                            </a>
                                                                        </label>
                                                                    
                                                                
                                                                
                                                                    <select v-model="cAttribute.attribute" class="form-control" @change="handleChangeAttribute(index)" required>
                                                                        <option v-for="column in columns" :key="column.column_name" :value="column.column_name + ',' + column.data_type">
                                                                            {{ column.column_name }}
                                                                        </option>
                                                                    </select>
                                                               
                                                                <div  v-if="cAttribute.attribute != ''">
                                                                    <div class="mb-3">
                                                                        <template v-if="cAttribute.data_type == 'varchar'">
                                                                            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                                <option value="includes">includes</option>
                                                                                <option value="dont_includes">doesn't include</option>
                                                                                <option value="=">equal to</option>
                                                                                <option value="!=">not equal to</option>
                                                                                <option value="IS NOT NULL">is not empty</option>
                                                                                <option value="IS NULL">is empty</option>
                                                                            </select>
                                                                            <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">
                                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control hidden" readonly required>
                                                                                  <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
                                                                                  <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
                                                                                <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                                   <li v-for="(value, vindex) in attribute_values" :key="vindex" >
                                                                                      <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
                                                                                      <label :for="'checkbox_' + vindex">{{ value }}</label>
                                                                                    </li>
                                                                                </ul>  
                                                                            </template>
                                                                            <template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">
                                                                              <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    
                                                                            </template>
                                                                        </template>
                                                                        <template v-if="cAttribute.data_type != 'varchar'">
                                                                            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                                <option value="=">equal to</option>
                                                                                <option value="!=">not equal to</option>
                                                                                <option value=">">is greater than</option>
                                                                                <option value="<">is less than</option>
                                                                                <option value="between">range</option>
                                                                                <option value="IS NOT NULL">is not empty</option>
                                                                                <option value="IS NULL">is empty</option>
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
                                                                            </template>
                                                                            <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')">
                                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" readonly  required>
                                                                                <span v-if="showManualValidationMessage==1" class="alert-danger">Search and Tick Condition below</span>
                                                                                <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">   
                                                                                <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                                                    <li v-for="(value, vindex) in attribute_values" :key="vindex" >
                                                                                          <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index)">
                                                                                          <label :for="'checkbox_' + vindex">{{ value }}</label>
                                                                                    </li> 
                                                                                </ul>
                                                                            </template>
                                                                            <template v-else-if="cAttribute.filter_type == '>' || cAttribute.filter_type == '<'">
                                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control"  required>    
                                                                            </template>
                                                                    </div>
                                                                    <div class="submit-form" v-if="cAttribute.attribute!=''">
                                                                        <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
                                                                    </div>
                                                                </div>
                                                          
                                                </form>
                                            </fieldset>
                                            <div class="operators" v-if="productDetails.length>0 && channelAttribute.length==0">
                                                <a class="btn white-btn" @click="addChannelCondition('AND','normal',productDetails[productDetails.length - 1])" data-test-id="and">
                                                    <strong>AND</strong>
                                                </a>
                                                <a class="btn white-btn" @click="addChannelCondition('OR','group',productDetails[productDetails.length - 1])" data-test-id="or">
                                                    <strong>OR</strong>
                                                </a>
                                            </div>

                                        </div>

                            </div>
                    

</div>
<div class="submit-form" v-if="productDetails.length>0 && showAttributeMid == 0">
<template class="filter-input" v-if="showInput==1">
<input type="text" v-model="filter_name" @keyup.enter="updateStatus(1)" placeholder="Enter Filter Name and Save" class="form-control" required>
<a class="btn btn-primary mt-3" @click="updateStatus(1)">Save Filters</a>
</template>
<template class="filter-input" v-else>
<a class="btn btn-primary mt-3"  @click="handleInput()">Save Filters</a>
</template>

<a class="btn btn-primary mt-3" @click="updateStatus(0)">Clear Filters</a>
</div>
  `,
};