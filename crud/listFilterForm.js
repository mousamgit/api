export default {
    props: ['channelAttribute','listDetailValue','selectedValues','showAttributeFirst','primary_table'],
    data() {
        return {
            showAttribute: 0,
            columns: [],
            showManualValidationMessage: 0,
            indexCheck: 0,
            attribute_values: [],
            showAttFilter: 1,
            op_show_value:'AND',
            rootURL:''
        };
    },
    mounted() {
        const { protocol, host } = window.location;
        this.rootURL = `${protocol}//${host}`;
        this.fetchAllColumns();
        if(this.showAttributeFirst==1)
        {
            this.showAttribute=1;
        }

    },
    computed: {
        sortedAttributeValues() {
            const selected = this.selectedValues.slice();
            const unselected = this.attribute_values.filter(value => !selected.includes(value));

            return selected.concat(unselected);
        }
    },

    methods: {
        //c
        updateSelectedValues(index,value) {
            const indexToRemove = this.selectedValues.indexOf(value);
            if (indexToRemove !== -1) {
                this.selectedValues.splice(indexToRemove, 1);
            } else {
                this.selectedValues.push(value);
            }

            const nonEmptyValues = this.selectedValues.filter(value => value.trim() !== ""); // Filter out empty values
            this.channelAttribute[index].attribute_condition = "(" + nonEmptyValues.map(value => `"${value}"`).join(',') + ")";
            console.log(this.channelAttribute);

        },

        //c
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
            this.showAttribute =1;
        },

        async getAttributeValue(index, attributeName, attributeCondition) {

            try {
                if (attributeCondition.length > 0) {
                    // Make an AJAX request to  PHP file to fetch attributes
                    const response = await fetch(this.rootURL+'/crud/fetch_attribute_values.php?table_name=' +this.primary_table+' &attribute_name=' + attributeName + '&attribute_condition=' + attributeCondition);

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

        //c
        refreshAttributeAgain() {
            this.$emit('form-updated');
            this.showFilter=true;
        },

        async submitForm() {
            try {
                console.log(this.channelAttribute);
                if ((this.channelAttribute[0].attribute_condition.trim() == '' || this.channelAttribute[0].attribute_condition.trim() == '()') && (!(this.channelAttribute[0].filter_type == 'IS NOT NULL' || this.channelAttribute[0].filter_type == 'IS NULL')) && (this.channelAttribute[0].filter_type != 'between')){
                    this.showManualValidationMessage=1
                    this.channelAttribute[0].attribute_condition='';
                } else {
                    this.showManualValidationMessage=0
                    const response = await fetch(this.rootURL+'/crud/save_list_filter.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            attribute: this.channelAttribute,
                            table_name:this.primary_table
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.initializeData()
                        this.$emit('form-updated');

                    } else {
                        console.error('Error saving filters:', data.error);
                    }
                }


            } catch (error) {
                console.error('Error saving :', error);
            }
        },

        //c
        async fetchAllColumns() {
            try {
                const dataToSend = {
                    table_name: this.primary_table
                };
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch(this.rootURL+'/crud/fetch_all_filter_columns.php',{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                const data = await response.json();
                // Update the attributes data
                this.columns = data;

            } catch (error) {
                console.error('Error fetching filter columns:', error);
            }
        },
        initializeData() {
            this.indexCheck = 0,
                this.columns = [],
                this.attribute_values = [],
                this.showAttribute = 0,
                this.showAttFilter = 1,
                this.op_show_value = 'AND'
                this.fetchAllColumns();
        }
    },
    template: ` <form @submit.prevent="submitForm">
                                        <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition card">
                                            <div>
                                                <label for="attribute" class="form-label">SELECT ATTRIBUTE:</label>
                                                <label class="delete-icon position-absolute top-0 end-0">
                                                    <a @click="refreshAttributeAgain">
                                                        <i class="fa fa-times animation-mode" aria-hidden="true"></i>
                                                    </a> 
                                                </label>
                                               
                                                <select class="form-control" v-model="cAttribute.attribute" @change="handleChangeAttribute(index)" required="">
                                                    <template v-for="column in columns">
                                                        <template v-if="column.column_name == listDetailValue.attribute_name">
                                                            <option :value="column.column_name+ ',' +column.data_type" selected>{{column.column_name}}</option>
                                                        </template>
                                                        <template v-else>
                                                            <option :value="column.column_name+ ',' +column.data_type">{{column.column_name}}</option>
                                                        </template>
                                                    </template>
                                                </select>
                                                <div v-if="showAttribute==1">
                                                    <div class="mb-3">
                                                        <template v-if="cAttribute.data_type == 'varchar' || cAttribute.data_type == 'text' || cAttribute.data_type == 'longtext'">
                                                            <select v-model="cAttribute.filter_type" id="filter-type" class="form-control">
                                                                <option value="includes" :selected="listDetailValue.filter_type === 'includes'">includes</option>
                                                                <option value="dont_includes" :selected="listDetailValue.filter_type === 'dont_includes'">doesn't include</option>
                                                                <option value="=" :selected="listDetailValue.filter_type === '='">equal to</option>
                                                                <option value="!=" :selected="listDetailValue.filter_type === '!='">not equal to</option>
                                                                <option value="IS NOT NULL" :selected="listDetailValue.filter_type === 'IS NOT NULL'">is not empty</option>
                                                                <option value="IS NULL" :selected="listDetailValue.filter_type === 'IS NULL'">is empty</option>
                                                            </select>
                                                            <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == '!='">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" readonly required>
                                                                <span v-if="showManualValidationMessage==1" class="danger">Search and Tick Condition below</span>
                                                                <input type="text" v-model="cAttribute.attribute_current" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_current)" class="form-control" placeholder="Search condition">
                                                             
                                                                <ul v-if="index == indexCheck && (cAttribute.filter_type == '=' || cAttribute.filter_type == '!=')" class="autocomplete-suggestions">
                                                               
                                                                    <li v-for="(value, vindex) in sortedAttributeValues" :key="vindex">
                                                                        <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index,value)" :checked="selectedValues.includes(value)">
                                                                        <label :for="'checkbox_' + vindex">{{ value }} </label>
                                                                    </li>
                                                                </ul>
                                                            </template>
                                                            <template v-else-if="cAttribute.filter_type == 'includes' || cAttribute.filter_type == 'dont_includes'">
                                                                <input type="text" v-model="cAttribute.attribute_condition" class="form-control" required>
                                                            </template>
                                                        </template>
                                                        <template v-if="cAttribute.data_type !== 'varchar' && cAttribute.data_type !== 'text' && cAttribute.data_type !== 'longtext'">
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
                                                                    
                                                                    <li v-for="(value, vindex) in sortedAttributeValues" :key="vindex">
                                                                        <input type="checkbox" :id="'checkbox_' + vindex" :value="value" v-model="selectedValues" @change="updateSelectedValues(index,value)" :checked="selectedValues.includes(value)">
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
`
};