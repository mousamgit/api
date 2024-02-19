export default {
    props: ['productDetails','showFilters'],
    data() {
        return {
            channelAttribute:[],
            indexCheck:0,
            columns: [],
            attribute_values:[],
            showAttribute:0,
            showAttFilter:1,
            indexVal:-1
        };
    },
    mounted() {

        this.fetchAllColumns();
    },
    methods: {
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
            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
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

        async getAttributeValue(index,attributeName,attributeCondition){
            try {
                if(attributeCondition.length>2) {
                    // Make an AJAX request to your PHP file to fetch attributes
                    const response = await fetch('./fetch_attribute_values.php?attribute_name=' + attributeName + '&attribute_condition=' + attributeCondition);

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
                    const response = await fetch('./products/delete_product_filter.php', {
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
        async fetchAllColumns() {
            try {
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch('./channels/fetch_all_pim_columns.php');
                // Parse the JSON response
                const data = await response.json();
                // Update the attributes data
                this.columns = data;
                console.log(this.columns,'column_list');
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },


    },
    template: `
<div class="col-md-12 bg-light" style="min-height: 100vh">
    <div class="right-menu filters background-secondary-bg">
        <div class="flex-row vcenter filter-header">
            <span class="sub-heading" >FILTERS</span>
        </div>
        <hr>
            <div class="flex-row vcenter filter-header">
                <div class="row">
                    <!-- Container for both Attributes and "+" button -->
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center p-3 border">
                            <!-- Left column for Attributes -->
                            <div>
                                <span>Attributes</span>
                            </div>

                            <!-- Right column for the "+" button -->
                            <div>

                                <a class="sub-heading btn btn-primary" v-if="productDetails.length==0 && channelAttribute.length==0" @click="addChannelCondition('AND','normal',[])">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <hr>
        <div class="filter-column">
            <div class="flex-col filters-container flex-grow-1">
                <div class="filters-content">
                    <!-- Bootstrap Card Component -->
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group selected-filters">

                                <div class="row" v-for="(productDet,index) in productDetails" style="margin-bottom: 5px; !important">
                                      <span class="" v-if="showAttFilter==1">
                                         
                                        <span v-if="productDet.op_value == 'OR' && productDet.id != productDetails[0].id">
                                        
                                         <a  class="btn btn-link" @click="addChannelCondition('AND','middle',productDetails[index-1])" data-test-id="and" >
                                          <strong>AND</strong>
                                         </a>
                                        </span>
                                    <center><span class="value text-ellipsis" v-if="(productDet.attribute_name !='' && index !=0)">----------{{productDet.op_value}}------------</span>
                                    </span></center>

                                <div class="filter-clauses" v-if="showAttFilter==1">
                                    <div class="clause">
                                        <div class="filter flex-row border-default card position-relative">
                                            <div class="flex-grow-1">
                                                <div style="margin: 5px !important;">
                                                    <span class="alternative emphasis filter-field ">{{productDet.attribute_name}} </span> <br>
                                                    <span class="text-default mt-5" v-if="productDet.filter_type !=''">&nbsp;{{ getEmptyPrinted(productDet.filter_type) }}</span>
                                                    <span class="text-default" v-if="productDet.range_to !=''">&nbsp;  {{productDet.range_from}} to {{productDet.range_to}}</span>
                                                    <span class="text-default" v-if="productDet.attribute_condition !='' && productDet.attribute_condition != productDet.filter_type">&nbsp; {{getEmptyPrinted(productDet.attribute_condition)}} </span>
                                                </div>

                                            </div>
                                            <div class="delete-icon position-absolute top-0 end-0" data-test-id="delete" >
                                                <a @click="deleteFilter(productDet.id,productDet.product_id,index)">
                                                <i class="btn btn-danger fas fa-trash-alt"></i>
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
                                        <div class="col-md-12" v-if="showAttribute==1">
                                            <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
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
                                        <select v-model="cAttribute.filter_type" id="filter-type" class="form-control" >
                                            <option value="includes">includes</option>
                                            <option value="=">equal to</option>
                                            <option value="!=">not equal to</option>
                                            <option value="IS NOT NULL">is not empty</option>
                                        </select>
                                        <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!=' || cAttribute.filter_type == 'includes'" >
                                            <input type="text" v-model="cAttribute.attribute_condition" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_condition)" class="form-control" placeholder="Enter condition" required>
                                            <ul v-if="index == indexCheck && cAttribute.filter_type != 'includes'" class="autocomplete-suggestions">
                                                <li v-for="(value, vindex) in attribute_values" :key="vindex" @click="selectAutocompleteValue(index, value)">
                                                {{ value }}
                                            </li>
                                        </ul>
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

                        <template v-if="cAttribute.data_type != 'varchar' && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=' || cAttribute.filter_type == '>' || cAttribute.filter_type == '<')" >
                            <input type="text" v-model="cAttribute.attribute_condition" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_condition)" class="form-control" placeholder="Enter condition" required>
                            <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                <li v-for="(value, vindex) in attribute_values" :key="vindex" @click="selectAutocompleteValue(index, value)">
                                {{ value }}
                            </li>
                        </ul>
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
            <a  class="btn btn-link" @click="addChannelCondition('AND','normal',productDetails[productDetails.length - 1])" data-test-id="and" >
            <strong>AND</strong>
        </a>
        <a class="btn btn-link" @click="addChannelCondition('OR','group',productDetails[productDetails.length - 1])" data-test-id="or" >
        <strong>OR</strong>
    </a>
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
  `,
};